import { Engine } from "json-rules-engine";

/**
 * @param {Array} rulesFromApi
 * @param {Object} facts
 * @returns Promise<{ totalDiscount: number, appliedRules: Array }>
 */
export async function evaluateRules(rulesFromApi, facts) {
  if (facts.line.quantity > 100 || facts.line.quantity < 1) {
    alert("Quantity must be between 1 and 100.");
    return;
  }
  
  const engine = new Engine();
  const appliedRules = [];
  let totalDiscount = 0;

  rulesFromApi.forEach((rule) => {
    const parsedCondition = JSON.parse(rule.condition);
    const parsedAction = JSON.parse(rule.action);

    const structuredRule = {
      name: rule.name,
      priority: 1000 - rule.salience,
      conditions: laravelConditionToJsonEngine(parsedCondition),
      event: {
        type: parsedAction.type || "unknown",
        params: {
          ...parsedAction,
          ruleId: rule.id,
          ruleName: rule.name,
          stackable: rule.stackable,
        },
      },
    };
    engine.addRule(structuredRule);
  });

  let remainingPrice = (facts.line.unitPrice || 0) * (facts.line.quantity || 0);

  engine.on("success", (event, almanac, ruleResult) => {
    let discount = calculateDiscount(event.params, facts, remainingPrice);

    totalDiscount += discount;
    appliedRules.push({
      ruleId: event.params.ruleId,
      ruleName: ruleResult.name,
      discount,
    });

    remainingPrice -= discount;

    if (event.params.stackable === false) {
      engine.stop();
    }
  });

  // Register dynamic fact
  engine.addFact("dynamic", async (params, almanac) => {
    const factData = await almanac.factValue("facts");
    return getNestedValue(factData, params.path);
  });

  // Convert all facts to use 'dynamic'
  engine.rules.forEach((rule) => {
    const convert = (conditions) => {
      if (conditions.all) {
        conditions.all.forEach(convert);
      } else if (conditions.any) {
        conditions.any.forEach(convert);
      } else if (conditions.fact) {
        const original = conditions.fact;
        conditions.fact = "dynamic";
        conditions.params = { path: original };
      }
    };
    convert(rule.conditions);
  });

  // Register custom operator before engine.run
  engine.addOperator("endsWith", (factValue, jsonValue) => {
    if (typeof factValue !== "string" || typeof jsonValue !== "string") {
      return false;
    }
    return factValue.endsWith(jsonValue);
  });

  await engine.run({ facts });

  return {
    totalDiscount,
    appliedRules,
  };
}

// split field
function getNestedValue(obj, path) {
  return path.split(".").reduce((acc, part) => acc?.[part], obj);
}

// make the conditions convenable with the engine
function laravelConditionToJsonEngine(condition) {
  if (!condition) return { all: [] };

  // Check if it's a composite condition with nested rules
  if (condition.rules) {
    const { operator, rules } = condition;
    const group = operator === "AND" ? "all" : "any"; // if and then all true, else then any true

    return {
      [group]: rules
        .filter((r) => r && r.field && r.operator && r.value)
        .map((r) => {
          return {
            fact: r.field,
            operator: mapOperator(r.operator),
            value: r.value,
          };
        }),
    };
  } else {
    // if it's a single condition
    if (condition.field && condition.operator && condition.value) {
      return {
        all: [
          {
            fact: condition.field,
            operator: mapOperator(condition.operator),
            value: condition.value,
          },
        ],
      };
    } else {
      console.warn("Malformed single condition encountered:", condition);
      return { all: [] };
    }
  }
}

// operators
function mapOperator(op) {
  if (!op) {
    console.warn("Undefined or null operator encountered in rule engine.", op);
    return "equal";
  }
  switch (op) {
    case "EQUALS":
      return "equal";
    case "GTE":
      return "greaterThanInclusive";
    case "LTE":
      return "lessThanInclusive";
    case "LT_DATE":
      return "lessThan";
    case "ENDS_WITH":
      return "endsWith";
    default:
      return op.toLowerCase();
  }
}

// actions
function calculateDiscount(action, facts, remainingPrice) {
  const line = facts.line || {};

  switch (action.type) {
    case "applyFreeUnits":
      return action.quantity * line.unitPrice;

    case "applyPercent":
      return (remainingPrice * action.percent) / 100;

    default:
      return 0;
  }
}

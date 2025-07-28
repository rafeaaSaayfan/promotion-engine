<?php

namespace App\Services\Rules;

use App\Models\Rule;
use App\Services\Rules\Compilers\ConditionCompiler;
use App\Services\Rules\Compilers\ActionCompiler;

class RuleEngineService
{
    public function evaluateRules(array $context): array
    {
        $rules = Rule::orderBySalience()->get();
        
        $appliedRules = [];
        $totalDiscount = 0;
        $lineTotal = $context['line']['quantity'] * $context['line']['unitPrice'];

        foreach ($rules as $rule) {
            $condition = ConditionCompiler::compile(json_decode($rule->condition, true));
            if (!$condition->evaluate($context)) continue;

            $action = ActionCompiler::compile(json_decode($rule->action, true));
            $discount = $action->apply($context, $lineTotal);

            if ($discount > 0) {
                $appliedRules[] = [
                    'ruleId' => $rule->id,
                    'ruleName' => $rule->name,
                    'discount' => round($discount, 2)
                ];
                $totalDiscount += $discount;
                $lineTotal -= $discount;

                if (!$rule->stackable) break;
            }
        }

        return [
            'applied' => $appliedRules,
            'totalDiscount' => round($totalDiscount, 2),
            'finalLineTotal' => round($lineTotal, 2)
        ];
    }
}
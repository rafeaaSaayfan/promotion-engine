<?php

namespace App\Services\Rules\Conditions;

class CompositeCondition
{
    public function __construct(protected string $operator, protected array $conditions) {}

    public function evaluate(array $context)
    {
        $operator = strtoupper($this->operator);

        if ($operator === 'AND') {
            foreach ($this->conditions as $condition) {
                if (!$condition->evaluate($context)) {
                    return false; 
                }
            }
            return true; 
        }

        if ($operator === 'OR') {
            foreach ($this->conditions as $condition) {
                if ($condition->evaluate($context)) {
                    return true;
                }
            }
            return false;
        }
    }
}

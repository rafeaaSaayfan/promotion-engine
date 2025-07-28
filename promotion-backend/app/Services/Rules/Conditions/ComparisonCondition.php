<?php 

namespace App\Services\Rules\Conditions;

class ComparisonCondition
{
    public function __construct(protected string $field, protected mixed $value, protected string $operator) {}

    public function evaluate(array $context): bool
    {
        if($this->operator === 'lte') {
            return data_get($context, $this->field) <= $this->value;
        }

        return data_get($context, $this->field) >= $this->value;
    }
}

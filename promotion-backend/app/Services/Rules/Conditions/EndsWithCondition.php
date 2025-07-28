<?php

namespace App\Services\Rules\Conditions;

class EndsWithCondition
{
    public function __construct(protected string $field, protected string $value) {}

    public function evaluate(array $context): bool
    {
        $fieldValue = data_get($context, $this->field);

        if (!is_string($fieldValue)) {
            return false; 
        }

        return str_ends_with($fieldValue, $this->value);
    }
}

<?php 

namespace App\Services\Rules\Conditions;

class EqualsCondition
{
    public function __construct(protected string $field, protected mixed $value, ) {}

    public function evaluate(array $context): bool
    {
        return data_get($context, $this->field) == $this->value;
    }
}
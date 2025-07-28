<?php 

namespace App\Services\Rules\Conditions;

use Carbon\Carbon;

class DateLessThanCondition
{
    public function __construct(protected string $field, protected string $value) {}

    public function evaluate(array $context): bool
    {
        $current = $this->field === 'now' ? Carbon::now() : Carbon::parse(data_get($context, $this->field));
        return $current->lt(Carbon::parse($this->value));
    }
}

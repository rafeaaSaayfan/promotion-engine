<?php 

namespace App\Services\Rules\Compilers;

use App\Services\Rules\Conditions\EqualsCondition;
use App\Services\Rules\Conditions\ComparisonCondition;
use App\Services\Rules\Conditions\CompositeCondition;
use App\Services\Rules\Conditions\DateLessThanCondition;
use App\Services\Rules\Conditions\EndsWithCondition;

class ConditionCompiler
{
    public static function compile(array $data)
    {
        if (isset($data['rules'])) {
            $children = array_map(fn($rule) => self::compile($rule), $data['rules']);
            return new CompositeCondition($data['operator'], $children);
        }

        return match (strtolower($data['operator'])) {
            'equals' => new EqualsCondition($data['field'], $data['value']),

            'gte', 'lte' => new ComparisonCondition($data['field'], $data['value'], strtolower($data['operator'])),

            'ends_with' => new EndsWithCondition($data['field'], $data['value']),

            'lt_date' => new DateLessThanCondition($data['field'], $data['value']),

            default => throw new \Exception("Unsupported operator: {$data['operator']}"),
        };
    }
}

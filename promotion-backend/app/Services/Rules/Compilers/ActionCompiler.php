<?php

namespace App\Services\Rules\Compilers;

use App\Services\Rules\Actions\PercentAction;
use App\Services\Rules\Actions\FreeUnitsAction;
use App\Services\Rules\Actions\IfElseAction;

class ActionCompiler
{
    public static function compile(array $data)
    {
        return match ($data['type']) {
            'applyPercent' => new PercentAction($data['percent']),

            'applyFreeUnits' => new FreeUnitsAction($data['quantity']),

            'ifElse' => new IfElseAction(
                ConditionCompiler::compile($data['condition']),
                self::compile($data['ifTrue']),
                self::compile($data['ifFalse']),
            ),

            default => throw new \Exception("Unsupported action type: {$data['type']}"),
        };
    }
}

<?php 

namespace App\Services\Rules\Actions;

class IfElseAction
{
    public function __construct(
        protected $condition,
        protected $ifTrue,
        protected $ifFalse
    ) {}

    public function apply(array $context, float $currentLineTotal): float
    {
        return $this->condition->evaluate($context)
            ? $this->ifTrue->apply($context, $currentLineTotal)
            : $this->ifFalse->apply($context, $currentLineTotal);
    }
}

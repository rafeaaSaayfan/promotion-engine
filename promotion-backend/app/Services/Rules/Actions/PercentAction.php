<?php 

namespace App\Services\Rules\Actions;

class PercentAction
{
    public function __construct(protected float $percent) {}

    public function apply(array $context, float $currentLineTotal): float
    {
        return ($currentLineTotal * $this->percent) / 100;
    }
}

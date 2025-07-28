<?php 

namespace App\Services\Rules\Actions;

class FreeUnitsAction
{
    public function __construct(protected int $quantity) {}

    public function apply(array $context, float $currentLineTotal): float
    {
        return $this->quantity * $context['line']['unitPrice'];
    }
}

<?php

namespace App\Http\Controllers\Api\PromotionRules;

use App\Http\Controllers\Controller;
use App\Services\Rules\RuleEngineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RulesEvaluationController extends Controller
{
    private RuleEngineService $ruleEngine;

    public function __construct(RuleEngineService $ruleEngine)
    {
        $this->ruleEngine = $ruleEngine;
    }

    public function evaluate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'line' => 'required|array',
            'line.productId' => 'required|integer|exists:products,id',
            'line.quantity' => 'required|integer|min:1',
            'line.unitPrice' => 'required|numeric|min:0',
            'line.categoryId' => 'sometimes|integer|exists:categories,id',
            'customer' => 'required|array',
            'customer.email' => 'sometimes|email|exists:customers,email',
            'customer.type' => 'sometimes|string',
            'customer.loyaltyTier' => 'sometimes|string',
            'customer.ordersCount' => 'sometimes|integer|min:0',
            'customer.city' => 'sometimes|string',  
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $context = $request->all();
        $result = $this->ruleEngine->evaluateRules($context);

        return response()->json($result);
    }
}

<?php

namespace App\Http\Controllers\Api\PromotionRules;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $rules = Rule::orderBySalience()->get();
        return response()->json($rules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'salience' => 'required|integer',
            'stackable' => 'required|boolean',
            'condition' => 'required|json', 
            'action' => 'required|json',   
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Decode condition and action from JSON strings to arrays before saving
        $data = $request->all();
        $data['condition'] = json_decode($data['condition'], true);
        $data['action'] = json_decode($data['action'], true);

        $rule = Rule::create($data);

        return response()->json($rule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rule $rule): JsonResponse
    {
        return response()->json($rule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rule $rule): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'salience' => 'required|integer',
            'stackable' => 'required|boolean',
            'condition' => 'required|json',
            'action' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if (isset($data['condition'])) {
            $data['condition'] = json_decode($data['condition'], true);
        }
        if (isset($data['action'])) {
            $data['action'] = json_decode($data['action'], true);
        }

        $rule->update($data);

        return response()->json($rule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rule $rule): JsonResponse
    {
        $rule->delete();

        return response()->json(['message' => 'Rule deleted successfully']);
    }
}

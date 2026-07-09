<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRiskRequest;
use App\Http\Requests\UpdateRiskRequest;
use App\Http\Resources\RiskResource;
use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RiskController extends Controller
{
    public function __construct(private readonly RiskService $risks) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $risks = $this->risks->list($request->only(['status', 'category', 'per_page']));

        return RiskResource::collection($risks);
    }

    public function store(StoreRiskRequest $request): JsonResponse
    {
        $risk = $this->risks->create($request->validated());

        return RiskResource::make($risk)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Risk $risk): RiskResource
    {
        return RiskResource::make($risk->load('owner:id,name,email'));
    }

    public function update(UpdateRiskRequest $request, Risk $risk): RiskResource
    {
        return RiskResource::make($this->risks->update($risk, $request->validated()));
    }

    public function destroy(Risk $risk): JsonResponse
    {
        $this->risks->delete($risk);

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActionPlanRequest;
use App\Http\Requests\UpdateActionPlanRequest;
use App\Http\Resources\ActionPlanResource;
use App\Models\ActionPlan;
use App\Services\ActionPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActionPlanController extends Controller
{
    public function __construct(private readonly ActionPlanService $actionPlans) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $actionPlans = $this->actionPlans->list($request->only([
            'risk_id',
            'control_id',
            'status',
            'priority',
            'per_page',
        ]));

        return ActionPlanResource::collection($actionPlans);
    }

    public function store(StoreActionPlanRequest $request): JsonResponse
    {
        $actionPlan = $this->actionPlans->create($request->validated());

        return ActionPlanResource::make($actionPlan)
            ->response()
            ->setStatusCode(201);
    }

    public function show(ActionPlan $actionPlan): ActionPlanResource
    {
        return ActionPlanResource::make($actionPlan->load([
            'risk:id,title,category,status',
            'control:id,title,effectiveness,status',
            'owner:id,name,email',
        ]));
    }

    public function update(UpdateActionPlanRequest $request, ActionPlan $actionPlan): ActionPlanResource
    {
        return ActionPlanResource::make($this->actionPlans->update($actionPlan, $request->validated()));
    }

    public function destroy(ActionPlan $actionPlan): JsonResponse
    {
        $this->actionPlans->delete($actionPlan);

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreControlRequest;
use App\Http\Requests\UpdateControlRequest;
use App\Http\Resources\ControlResource;
use App\Models\Control;
use App\Services\ControlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ControlController extends Controller
{
    public function __construct(private readonly ControlService $controls) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $controls = $this->controls->list($request->only(['risk_id', 'status', 'effectiveness', 'per_page']));

        return ControlResource::collection($controls);
    }

    public function store(StoreControlRequest $request): JsonResponse
    {
        $control = $this->controls->create($request->validated());

        return ControlResource::make($control)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Control $control): ControlResource
    {
        return ControlResource::make($control->load(['risk:id,title,category,status', 'owner:id,name,email']));
    }

    public function update(UpdateControlRequest $request, Control $control): ControlResource
    {
        return ControlResource::make($this->controls->update($control, $request->validated()));
    }

    public function destroy(Control $control): JsonResponse
    {
        $this->controls->delete($control);

        return response()->json(null, 204);
    }
}

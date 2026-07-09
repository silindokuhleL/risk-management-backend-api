<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardSummaryResource;
use App\Services\DashboardSummaryService;

class DashboardSummaryController extends Controller
{
    public function __construct(private readonly DashboardSummaryService $summary) {}

    public function __invoke(): DashboardSummaryResource
    {
        return DashboardSummaryResource::make($this->summary->summary());
    }
}

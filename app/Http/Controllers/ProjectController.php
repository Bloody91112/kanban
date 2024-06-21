<?php

namespace App\Http\Controllers;

use App\Http\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        public ProjectService $service,
    ) {}
    public function show(Project $project): Response
    {
        $project = ProjectService::load($project);
        return Inertia::render('Project/Show', compact('project'));
    }
}

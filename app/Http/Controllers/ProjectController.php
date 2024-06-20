<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function show(Project $project): Response
    {
        $project = $project->load('columns.tasks');
        return Inertia::render('Project/Show', compact('project'));
    }
}

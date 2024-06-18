<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController
{
    public function index(): Response
    {
        $project = Project::where('id', '=', 1)->with('columns.tasks')->first();
        return Inertia::render('Dashboard', [
            'project' => $project
        ]);
    }
}

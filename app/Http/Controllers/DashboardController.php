<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController
{
    public function index(): Response
    {
        $projects = auth()->user()->projects()->with('columns.tasks')->get();

        return Inertia::render('Dashboard', compact('projects'));
    }
}

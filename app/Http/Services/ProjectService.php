<?php

namespace App\Http\Services;

use App\Models\Project;

class ProjectService
{
    public static function load(Project $project): Project
    {
       return $project->load('columns.tasks');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\MoveRequest;
use App\Http\Services\ProjectService;
use App\Http\Services\TaskService;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        public TaskService $service,
    ) {}
    public function move(Task $task, MoveRequest $request): JsonResponse
    {
        $data = $request->validated();
        $task = $this->service->move($task, $data);
        $project = ProjectService::load($task->column->project);
        return response()->json([
            'status' => true,
            'project' => $project
        ]);
    }
}

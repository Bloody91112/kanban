<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\MoveRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Services\ProjectService;
use App\Http\Services\TaskService;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $task = $this->service->store($data);
        $project = ProjectService::load($task->column->project);
        return response()->json([
            'status' => true,
            'project' => $project,
            'message' => 'Задача успешно создана!'
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->service->destroy($task);
        $project = ProjectService::load($task->column->project);

        return response()->json([
            'status' => true,
            'message' => 'Задача успешно удалена!',
            'project' => $project
        ]);
    }

    public function addFile(Task $task, Request $request): JsonResponse
    {
        $data = $request->validate(['image' => 'file']);
        $this->service->addFile($task, $data);

        return response()->json([
            'status' => true,
            'message' => 'Файл успешно добавлен!',
            'project' => $task->refresh()
        ]);
    }
}

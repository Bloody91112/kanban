<?php

namespace App\Http\Controllers;

use App\Http\Requests\Column\StoreRequest;
use App\Http\Services\ColumnService;
use App\Http\Services\ProjectService;
use App\Models\Column;
use Illuminate\Http\JsonResponse;

class ColumnController extends Controller
{
    public function __construct(
        public ColumnService $service,
    ) {}

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $column = $this->service->store($data);
        $project = ProjectService::load($column->project);

        return response()->json(
            ['status' => true, 'message' => 'Колонка успешно добавлена!', 'project' => $project]
        );
    }

    public function destroy(Column $column): JsonResponse
    {
        if ($column->name === Column::BACKLOG_COLUMN){
            return response()->json(
                ['status' => false, 'message' => 'Нельзя удалить']
            );
        }

        $this->service->destroy($column);

        $project = ProjectService::load($column->project);

        return response()->json(
            ['status' => true, 'message' => 'Колонка успешно удалена!', 'project' => $project]
        );

    }

}

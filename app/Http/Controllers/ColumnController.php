<?php

namespace App\Http\Controllers;

use App\Http\Requests\Column\StoreRequest;
use App\Models\Column;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ColumnController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $column = Column::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Колонка успешно добавлена!',
            'column' => $column
        ]);
    }

    public function destroy(Column $column): JsonResponse
    {
        if ($column->name === Column::EMPTY_COLUMN_NAME){
            return response()->json([
                'status' => false,
                'message' => 'Нельзя удалить',
            ]);
        }

        $emptyColumn = Column::firstOrCreate([
            'name' => Column::EMPTY_COLUMN_NAME,
            'project_id' => $column->project_id
        ]);

        $column->tasks()->update(['column_id' => $emptyColumn->id]);
        $column->delete();

        return response()->json([
            'status' => true,
            'message' => 'Колонка успешно удалена!',
            'column' => $column
        ]);

    }

}

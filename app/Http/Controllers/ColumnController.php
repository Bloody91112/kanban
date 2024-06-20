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
            'message' => 'Колонка добавлена!',
            'column' => $column
        ]);
    }
}

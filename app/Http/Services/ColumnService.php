<?php

namespace App\Http\Services;

use App\Models\Column;

class ColumnService
{
    public function destroy(Column $column): void
    {
        $backlogColumn = Column::firstOrCreate([
            'name' => Column::BACKLOG_COLUMN,
            'project_id' => $column->project_id
        ]);

        $column->tasks()->update(['column_id' => $backlogColumn->id]);
        $column->delete();
    }

    public function store(array $data): Column
    {
        return Column::create($data);
    }
}

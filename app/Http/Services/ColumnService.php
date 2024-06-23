<?php

namespace App\Http\Services;

use App\Models\Column;
use App\Models\Task;

class ColumnService
{
    public function destroy(Column $column): void
    {
        $backlogColumn = Column::firstOrCreate([
            'name' => Column::BACKLOG_COLUMN,
            'project_id' => $column->project_id
        ]);

        $column->tasks()->each(function (Task $task) use ($backlogColumn) {
            $task->update([
                'column_id' => $backlogColumn->id,
                'position' => $backlogColumn->tasks->count()
                    ? $backlogColumn->refresh()->tasks()->max('position') + 1
                    : 0
            ]);
        });

        $column->project->columns()
            ->where('position', '>', $column->position)
            ->each(function (Column $item) {
                $item->update(['position' => $item->position - 1]);
            });

        $column->delete();
    }

    public function store(array $data): Column
    {
        $column = Column::create($data);
        $column->update([
            'position' => $column->project->columns()->max('position') + 1
        ]);
        return $column;
    }

    public function swap(array $data): void
    {
        $firstColumn = Column::find($data['firstColumnId']);
        $secondColumn = Column::find($data['secondColumnId']);

        $firstColumnPosition = $firstColumn->position;
        $secondColumnPosition = $secondColumn->position;

        $firstColumn->update(['position' => $secondColumnPosition]);
        $secondColumn->update(['position' => $firstColumnPosition]);
    }
}

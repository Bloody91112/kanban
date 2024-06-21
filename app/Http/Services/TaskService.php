<?php

namespace App\Http\Services;

use App\Models\Column;
use App\Models\Project;
use App\Models\Task;

class TaskService
{
    public function move(Task $task, array $data): Task
    {
        /* Таски из старой колонки */
        Task::where('column_id', '=', $data['oldColumnId'])
            ->where('position', '>', $data['oldIndex'])
            ->get()
            ->filter(fn(Task $item) => $item->id != $task->id)
            ->each(fn (Task $item) => $item->update(['position' => $item->position - 1]));

        /* Таски из новой колонки */
        Task::where('column_id', '=', $data['newColumnId'])
            ->where('position', '>=', $data['newIndex'])
            ->get()
            ->filter(fn(Task $item) => $item->id != $task->id)
            ->each(fn (Task $item) => $item->update(['position' => $item->position + 1]));

        $task->update([
            'column_id' => $data['newColumnId'],
            'position' => $data['newIndex']
        ]);

        return $task;

    }
}

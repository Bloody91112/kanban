<?php

namespace App\Http\Services;

use App\Models\Column;
use App\Models\Image;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskService
{
    public function store(array $data): Task
    {
        $task = Task::create($data);
        $task->update([
            'position' => $task->column->tasks()->count()
                ? $task->column->tasks()->max('position') + 1
                : 0
        ]);
        return $task;
    }

    public function move(Task $task, array $data): Task
    {
        /* Сортировка в пределах одной колонки */
        if ($data['oldColumnId'] === $data['newColumnId']){
            Task::where('column_id', '=', $data['newColumnId'])
                ->where('position', '=', $data['newIndex'])
                ->first()
                ->update(['position' => $data['oldIndex']]);
            $task->update(['position' => $data['newIndex']]);
        /* Перемещение между колонками */
        } else {
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
        }

        return $task;
    }

    public function destroy(Task $task): void
    {
        Task::where('column_id', '=', $task->column_id)
            ->where('position', '>', $task->position)
            ->get()
            ->filter(fn(Task $item) => $item->id != $task->id)
            ->each(fn (Task $item) => $item->update(['position' => $item->position - 1]));

        $task->delete();
    }

    public function addFile(Task $task, array $data): void
    {
        $path = Storage::put("public/tasks/$task->id", $data['image']);

        Image::create([
            'imageable_id' => $task->id,
            'imageable_type' => Task::class,
            'path' => $path
        ]);
    }
}

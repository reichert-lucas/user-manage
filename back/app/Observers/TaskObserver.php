<?php

namespace App\Observers;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Carbon\Carbon;

class TaskObserver
{
    public function updating(Task $task)
    {
        if(!$task->isDirty('status_id')){
            return;
        }

        if ($task->status_id === TaskStatusEnum::CONCLUDED) {
            $task->concluded_at = Carbon::now();
            return;
        }

        $task->concluded_at = null;
    }
}

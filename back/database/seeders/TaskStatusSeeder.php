<?php

namespace Database\Seeders;

use App\Enums\TaskStatusEnum;
use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run()
    {
        TaskStatus::insert([
            [
                'id' => TaskStatusEnum::CREATED,
                'name' => TaskStatusEnum::CREATED->label(),
            ],
            [
                'id' => TaskStatusEnum::DOING,
                'name' => TaskStatusEnum::DOING->label(),
            ],
            [
                'id' => TaskStatusEnum::CONCLUDED,
                'name' => TaskStatusEnum::CONCLUDED->label(),
            ],
        ]);
    }
}

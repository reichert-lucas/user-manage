<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'concluded_at',
        'status_id',
    ];

    protected $casts = [
        'status_id' => TaskStatusEnum::class,
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }
}

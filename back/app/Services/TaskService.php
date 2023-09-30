<?php

namespace App\Services;

use App\Services\Service;

class TaskService extends Service
{    
    public static function search($filters, $query)
    {     
        self::applyIfInArray($filters, 'id', function ($id) use ($query) {
            $query->where('id', $id);
        });

        self::applyIfInArray($filters, 'search', function ($search) use ($query) {
            $query->where('name', 'LIKE', "%$search%");
        });

        self::applyIfInArray($filters, 'status_ids', function ($statusIds) use ($query) {
            $query->whereHas('status', function ($query) use ($statusIds) {
                $query->whereIn('status_id', $statusIds);
            });
        });

        return $query;
    }
}

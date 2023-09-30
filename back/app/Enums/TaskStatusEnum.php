<?php

namespace App\Enums;

enum TaskStatusEnum: int
{
    case CREATED = 1;
    case DOING = 2;
    case CONCLUDED = 3;

    public function label(): string 
    {
        return match ($this) {
            self::CREATED => 'Criada',
            self::DOING => 'Fazendo',
            self::CONCLUDED => 'Concluída',
        };
    }
}

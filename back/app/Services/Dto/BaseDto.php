<?php

namespace App\Services\Dto;

abstract class BaseDto
{
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (!property_exists(get_class($this), $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function toArray(): array
    {
        $array = [];
        
        foreach ($this as $key => $value) {
            if (!$value) {
                continue;
            }

            $array[$key] = $this->{'get' . ucfirst($key)}();
        }

        return $array;
    }
}

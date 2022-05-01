<?php

namespace App\Core\Database;

abstract class Dto
{
    public function isNew(): bool
    {
        return $this->getModel()->id === null;
    }

    abstract public function getModel(): \Illuminate\Database\Eloquent\Model;

}

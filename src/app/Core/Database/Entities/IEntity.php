<?php

namespace App\Core\Database\Entities;

use Illuminate\Database\Eloquent\Model;

interface IEntity
{
    public function getModel(): Model;
}

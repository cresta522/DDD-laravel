<?php

namespace App\Core\Database\Entities;

abstract class Entity implements IEntity
{
    abstract protected function getDefaultModel(): void;
}

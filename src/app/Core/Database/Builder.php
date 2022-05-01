<?php

namespace App\Core\Database;

use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class Builder extends \Illuminate\Database\Eloquent\Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }
}

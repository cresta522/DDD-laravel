<?php

namespace App\Core\Database\Repository;

interface IUseEloquent
{
    /**
     * Eloquentモデルクラス名を取得
     * @return string
     */
    public function modelClass(): string;
}

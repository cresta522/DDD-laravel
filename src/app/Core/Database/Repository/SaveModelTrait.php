<?php

namespace App\Core\Database\Repository;

use App\Exceptions\BusinessLogicException;
use Illuminate\Database\Eloquent\Model;

trait SaveModelTrait
{
    /**
     * 対象レコードを保存する
     * @param Model $model
     * @return Model
     * @throws BusinessLogicException
     */
    public function save(Model $model): Model
    {
//        $this->validate($model);
        if (!$model->save()) {
            throw new BusinessLogicException($this->modelClass() . ' has not been saved.');
        }

        return $model;
    }
}

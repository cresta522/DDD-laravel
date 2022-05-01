<?php

namespace App\Core\Database\Repository;

use App\Core\Database\Builder;

trait SelectModelTrait
{
    /**
     * 対象レコードを取得する
     * @param int $id
     * @param bool $withTrashed 削除済みのデータを取得するか否か
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function select(int $id, bool $withTrashed = false)
    {
        return $this->selectBuilder($id, $withTrashed)->first();
    }

    /**
     * 対象レコードのクエリビルダを取得する
     * @param int $id
     * @param bool $withTrashed 削除済みのデータを取得するか否か
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function selectBuilder(int $id, bool $withTrashed = false)
    {

        $query = $this->builder()->where('id', '=', $id);
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query;
    }

    /**
     * クエリビルダを取得する
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function builder(bool $withTrashed = false)
    {
        if ($withTrashed) {
            return ($this->modelClass())::query()->withTrached();
        }
        return ($this->modelClass())::query();
    }

    /**
     * 検索対象カラムを指定して取得する
     * @param string $key
     * @param $val
     * @param bool $withTrashed 削除済みのデータを取得するか否か
     * @return Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function selectBy(string $key, $val, bool $withTrashed = false)
    {
        if ($withTrashed) {
            return $this->builder()->where($key, '=', $val)->withTrached()->first();
        }
        return $this->builder()->where($key, '=', $val)->first();
    }

    public function isExists(string $key, $val, bool $withTrashed = false): bool
    {
        if ($withTrashed) {
            return $this->builder()->where($key, '=', $val)->withTrached()->exists();
        }
        return $this->builder()->where($key, '=', $val)->exists();
    }

    /**
     * 全てのレコードを取得する
     * @param bool $withTrashed 削除済みのデータを取得するか否か
     * @return Collection
     */
    public function selectAll(bool $withTrashed = false)
    {
        if ($withTrashed) {
            return $this->builder()->withTrashed()->get();
        }

        return $this->getModelClass()::all();
    }

    /**
     * モデルクラスの生成
     *
     */
    private function getModelClass(): string
    {
        return ($this->modelClass());
    }

    /**
     * 検索対象カラムが指定と一致する全てのレコードを取得する
     *
     * @param $key
     * @param $val
     * @param array|null $with
     * @param array|null $orderBy
     * @return Collection
     */
    public function selectAllBy($key, $val, ?array $with = null, ?array $orderBy = null)
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $query = $this->builder();

        if ($with) {
            $query = $this->getModelClass()::with($with);
        }

        if ($orderBy) {
            foreach ($orderBy as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        return $query->where($key, $val)->get();
    }

    /**
     * 検索対象カラムが指定の複数値と一致する全てのレコードを取得する
     * @param string $key
     * @param array $values
     * @return Collection
     */
    public function selectInBy(string $key, array $values)
    {
        return $this->getModelClass()->query()->whereIn($key, $values)->get();
    }
}

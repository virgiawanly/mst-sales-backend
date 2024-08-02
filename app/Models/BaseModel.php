<?php

namespace App\Models;

use App\Traits\DefaultActivityLogOptions;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    use DefaultActivityLogOptions;

    /**
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [];

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [];

    /**
     * The columns or expressions that will be sorted if the given parameter exists.
     *
     * @var array<string, string>
     */
    protected $sortColumns = [];

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        if (!$keyword) {
            return $query;
        }

        return $query->where(function ($subQuery) use ($keyword) {
            // escape the search query for % characters
            $keyword = str_replace('%', '\\%', $keyword);
            foreach ($this->searchables as $searchable) {
                $subQuery->orWhere($searchable, 'LIKE', "%{$keyword}%");
            }
        });
    }

    /**
     * Scope a query to order by a column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfOrder(Builder $query, string $sort, string $order): Builder
    {
        if (!$sort) {
            return $query;
        }

        $sort = $this->sortColumns[$sort] ?? $sort;

        if (!$order) {
            $order = 'asc';
        }

        return $query->orderBy(DB::raw($sort), $order);
    }

    /**
     * Scope a query to search for a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $queryParams
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchColumns(Builder $query, array $queryParams): Builder
    {
        return $query->where(function ($query) use ($queryParams) {
            foreach ($this->searchableColumns as $column => $operator) {
                if (isset($queryParams[$column])) {
                    $query->where($column, $operator, $queryParams[$column]);
                }
            }
        });
    }
}

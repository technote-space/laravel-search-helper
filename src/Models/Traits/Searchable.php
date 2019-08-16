<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Searchable
 * @package Technote\SearchHelper\Models\Traits
 * @mixin Model
 * @mixin Builder
 */
trait Searchable
{
    protected $likeSearch = [
        's',
        'search',
        'keyword',
    ];

    /**
     * @return array
     */
    public function getSearchRules(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return array
     */
    public function getSearchAttributes(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param  array  $conditions
     *
     * @return Model|Builder|Searchable
     */
    public function search(array $conditions)
    {
        $conditions = $this->filterConditions($conditions);
        $table      = $this->getTable();
        $query      = $this->newQuery();

        $this->setIdConditions($query, $conditions);
        $this->setLimitConditions($query, $conditions);
        $this->setConditions($query, $conditions);
        $this->joinTables($query);

        $selectTables = collect();
        foreach ($this->getSearchOrderBy() as $k => $v) {
            $query->orderByRaw("$k $v");
            if (preg_match_all('#(\w+)\.\w+#', $k, $matches) > 0) {
                $selectTables = $selectTables->concat($matches[1]);
            }
        }

        $query->select($selectTables->concat([$table])->unique()->map(function ($table) {
            return "{$table}.*";
        })->toArray());

        return $query->distinct();
    }

    /**
     * @param  Builder  $query
     * @param  array  $conditions
     */
    private function setIdConditions(Builder $query, array $conditions)
    {
        $table = $this->getTable();
        if (! empty($conditions['id'])) {
            if (is_array($conditions['id'])) {
                $conditions['ids'] = $conditions['id'];
            } else {
                $query->where("{$table}.id", $conditions['id']);
            }
        }
        if (! empty($conditions['ids'])) {
            $query->whereIn("{$table}.id", $conditions['ids']);
        }
        if (! empty($conditions['not_id'])) {
            if (is_array($conditions['not_id'])) {
                $conditions['not_ids'] = $conditions['not_id'];
            } else {
                $query->where("{$table}.id", '!=', $conditions['not_id']);
            }
        }
        if (! empty($conditions['not_ids'])) {
            $query->whereNotIn("{$table}.id", $conditions['not_ids']);
        }
    }

    /**
     * @param  Builder  $builder
     * @param  array  $conditions
     */
    private function setLimitConditions(Builder $builder, array $conditions)
    {
        if (! empty($conditions['count']) && $conditions['count'] > 0) {
            $builder->limit($conditions['count']);
        }
        if (! empty($conditions['offset'])) {
            $builder->offset($conditions['offset']);
        }
    }

    /**
     * @param  Builder  $query
     * @param  array  $conditions
     */
    abstract protected function setConditions(Builder $query, array $conditions);

    /**
     * @return array
     */
    protected function getSearchJoins(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getSearchOrderBy(): array
    {
        return [];
    }

    /**
     * @param  Builder  $query
     */
    private function joinTables(Builder $query)
    {
        $joined = [];
        foreach ($this->getSearchJoins() as $table => $join) {
            if (! empty($join['first'])) {
                if (empty($joined[$table])) {
                    $this->joinTable($query, $table, $join);
                    $joined[$table] = true;
                }
            }
        }
    }

    /**
     * @param  Builder  $query
     * @param  string  $table
     * @param  array  $join
     */
    private function joinTable(Builder $query, string $table, array $join)
    {
        $query->join(
            $table,
            $join['first'],
            $join['operator'] ?? (isset($join['second']) ? '=' : null),
            $join['second'] ?? null,
            $join['type'] ?? 'left',
            $join['where'] ?? false
        );
    }

    /**
     * @param  array  $conditions
     *
     * @return array
     */
    protected function filterConditions(array $conditions)
    {
        foreach ($conditions as $key => $value) {
            if (in_array($key, $this->likeSearch)) {
                $value = str_replace(['　', "\r", "\n"], ' ', $value);
                $value = trim($value);
                if ('' === $value) {
                    unset($conditions[$key]);
                } else {
                    $conditions[$key] = collect(explode(' ', $value))->filter()->unique()->map(function ($value) {
                        return $this->escapeLike($value);
                    })->toArray();
                }
            }
        }

        return $conditions;
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function escapeLike(string $string): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $string);
    }
}

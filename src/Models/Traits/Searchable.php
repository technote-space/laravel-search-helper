<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use \Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;

/**
 * Trait Searchable
 * @package Technote\SearchHelper\Models\Traits
 * @mixin Model
 * @mixin Builder
 */
trait Searchable
{
    protected static $likeSearch = [
        's',
        'search',
        'keyword',
    ];

    /**
     * @return array
     */
    public static function getSearchRules(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return array
     */
    public static function getSearchAttributes(): array
    {
        // @codeCoverageIgnoreStart
        return [];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param array $conditions
     *
     * @return Builder|SearchableContract
     */
    public static function search(array $conditions)
    {
        $conditions = static::filterConditions($conditions);
        $table = (new static)->getTable();
        $query = static::query();

        static::setIdConditions($query, $conditions);
        static::setLimitConditions($query, $conditions);
        static::setConditions($query, $conditions);
        static::joinTables($query);

        $selectTables = collect();
        foreach (static::getSearchOrderBy() as $k => $v) {
            $query->orderByRaw("$k $v");
            $matches = null;
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
     * @param Builder $query
     * @param array $conditions
     *
     * @return void
     */
    private static function setIdConditions(Builder $query, array $conditions): void
    {
        $table = (new static)->getTable();
        if (!empty($conditions['id'])) {
            if (is_array($conditions['id'])) {
                $conditions['ids'] = $conditions['id'];
            } else {
                $query->where("{$table}.id", $conditions['id']);
            }
        }

        if (!empty($conditions['ids'])) {
            $query->whereIn("{$table}.id", $conditions['ids']);
        }

        if (!empty($conditions['not_id'])) {
            if (is_array($conditions['not_id'])) {
                $conditions['not_ids'] = $conditions['not_id'];
            } else {
                $query->where("{$table}.id", '!=', $conditions['not_id']);
            }
        }

        if (!empty($conditions['not_ids'])) {
            $query->whereNotIn("{$table}.id", $conditions['not_ids']);
        }
    }

    /**
     * @param Builder $builder
     * @param array $conditions
     *
     * @return void
     */
    private static function setLimitConditions(Builder $builder, array $conditions): void
    {
        if (!empty($conditions['count']) && $conditions['count'] > 0) {
            $builder->limit($conditions['count']);
        }

        if (!empty($conditions['offset'])) {
            $builder->offset($conditions['offset']);
        }
    }

    /**
     * @param Builder $query
     * @param array $conditions
     */
    abstract protected static function setConditions(Builder $query, array $conditions);

    /**
     * @return array
     */
    protected static function getSearchJoins(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected static function getSearchOrderBy(): array
    {
        return [];
    }

    /**
     * @param Builder $query
     *
     * @return void
     */
    private static function joinTables(Builder $query): void
    {
        $joined = [];
        foreach (static::getSearchJoins() as $table => $join) {
            if (!empty($join['first']) && empty($joined[$table])) {
                static::joinTable($query, $table, $join);
                $joined[$table] = true;
            }
        }
    }

    /**
     * @param Builder $query
     * @param string $table
     * @param array $join
     *
     * @return void
     */
    private static function joinTable(Builder $query, string $table, array $join): void
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
     * @param array $conditions
     *
     * @return array
     */
    protected static function filterConditions(array $conditions): array
    {
        foreach ($conditions as $key => $value) {
            if (in_array($key, static::$likeSearch, true)) {
                $value = str_replace(['　', "\r", "\n"], ' ', $value);
                $value = trim($value);
                if ('' === $value) {
                    unset($conditions[$key]);
                } else {
                    $conditions[$key] = collect(explode(' ', $value))->filter()->map(function ($value) {
                        return static::escapeLike($value);
                    })->unique()->toArray();
                }
            }
        }

        return $conditions;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected static function escapeLike(string $string): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $string);
    }
}

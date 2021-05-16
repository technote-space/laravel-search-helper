<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Tests;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
use Technote\SearchHelper\Models\Traits\Searchable;

/**
 * Class Item
 * @package Technote\SearchHelper\Tests
 * @mixin Eloquent
 */
class Item extends Model implements SearchableContract
{
    use Searchable;

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @param Builder $query
     * @param array $conditions
     */
    protected static function setConditions(Builder $query, array $conditions)
    {
        if (!empty($conditions['s'])) {
            collect($conditions['s'])->each(function ($search) use ($query) {
                $query->where(function ($builder) use ($search) {
                    /** @var Builder $builder */
                    $builder->where('items.name', 'like', "%{$search}%");
                });
            });
        }
    }
}

<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Tests;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
use Technote\SearchHelper\Models\Traits\Searchable;

/**
 * Class User
 * @package Technote\SearchHelper\Tests
 * @mixin Eloquent
 */
class User extends Model implements SearchableContract
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
                    $builder->where('user_details.name', 'like', "%{$search}%")
                        ->orWhere('user_details.address', 'like', "%{$search}%");
                });
            });
        }
    }

    /**
     * @return array
     */
    protected static function getSearchJoins(): array
    {
        return [
            'user_details' => [
                'first' => 'user_details.user_id',
                'second' => 'users.id',
            ],
        ];
    }

    /**
     * @return array
     */
    protected static function getSearchOrderBy(): array
    {
        return [
            'users.id' => 'desc',
        ];
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }
}

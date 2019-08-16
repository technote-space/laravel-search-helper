<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface Searchable
 * @package Technote\SearchHelper\Models\Contracts
 */
interface Searchable
{
    /**
     * @return array
     */
    public static function getSearchRules(): array;

    /**
     * @return array
     */
    public static function getSearchAttributes(): array;

    /**
     * @param  array  $conditions
     *
     * @return Model|Builder|\Technote\SearchHelper\Models\Traits\Searchable
     */
    public static function search(array $conditions);
}

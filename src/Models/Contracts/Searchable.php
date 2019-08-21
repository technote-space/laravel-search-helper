<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;

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
     * @return Builder|Searchable
     */
    public static function search(array $conditions);
}

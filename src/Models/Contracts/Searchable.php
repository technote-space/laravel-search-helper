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
    public function getSearchRules(): array;

    /**
     * @return array
     */
    public function getSearchAttributes(): array;

    /**
     * @param  array  $conditions
     *
     * @return Model|Builder|\Technote\SearchHelper\Models\Traits\Searchable
     */
    public function search(array $conditions);
}

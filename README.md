# Laravel Search Helper

[![Build Status](https://travis-ci.com/technote-space/laravel-search-helper.svg?branch=master)](https://travis-ci.com/technote-space/laravel-search-helper)
[![Coverage Status](https://coveralls.io/repos/github/technote-space/laravel-search-helper/badge.svg?branch=master)](https://coveralls.io/github/technote-space/laravel-search-helper?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper/badge)](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![PHP: >=5.6](https://img.shields.io/badge/PHP-%3E%3D5.6-orange.svg)](http://php.net/)
[![WordPress: >=5.0](https://img.shields.io/badge/WordPress-%3E%3D5.0-brightgreen.svg)](https://wordpress.org/)

Search helper for Laravel.

[Packagist](https://packagist.org/packages/technote/laravel-search-helper)

## Install
```shell script
composer require technote/laravel-search-helper
```

## Usage
1. Implement Searchable contract and Searchable Trait.
1. Implement `setConditions` function.  
    ```php
    <?php
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Technote\SearchHelper\Models\Contracts\Searchable as SearchableContract;
    use Technote\SearchHelper\Models\Traits\Searchable;
    
    /**
     * Class Item
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
         * @param  Builder  $query
         * @param  array  $conditions
         */
        protected static function setConditions(Builder $query, array $conditions)
        {
            if (! empty($conditions['s'])) {
                collect($conditions['s'])->each(function ($search) use ($query) {
                    $query->where(function ($builder) use ($search) {
                        /** @var Builder $builder */
                        $builder->where('items.name', 'like', "%{$search}%");
                    });
                });
            }
        }
    }
    ```
1. Call search function.
    ```php
    <?php
    Item::search([
        's' => [
            'test',
        ],
        'ids' => [1, 2, 3],
    ])->get();
    ```

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)

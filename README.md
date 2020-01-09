# Laravel Search Helper

[![CI Status](https://github.com/technote-space/laravel-search-helper/workflows/CI/badge.svg)](https://github.com/technote-space/laravel-search-helper/actions)
[![Build Status](https://travis-ci.com/technote-space/laravel-search-helper.svg?branch=master)](https://travis-ci.com/technote-space/laravel-search-helper)
[![codecov](https://codecov.io/gh/technote-space/laravel-search-helper/branch/master/graph/badge.svg)](https://codecov.io/gh/technote-space/laravel-search-helper)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper/badge)](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/technote-space/laravel-search-helper/blob/master/LICENSE)
[![PHP: >=7.2](https://img.shields.io/badge/PHP-%3E%3D7.2-orange.svg)](http://php.net/)

Search helper for Laravel.

[Packagist](https://packagist.org/packages/technote/laravel-search-helper)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Install](#install)
- [Usage](#usage)
- [Author](#author)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Install
```
composer require technote/laravel-search-helper
```

## Usage
1. Implement `Searchable Contract` and `Searchable Trait`.
1. Implement `setConditions` method.

   ```php
   <?php
   namespace App\Models;
   
   use Eloquent;
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
1. Call `search` method.

   ```php
   <?php
   use App\Models\Item;
   
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

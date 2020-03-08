# Laravel Search Helper

[![CI Status](https://github.com/technote-space/laravel-search-helper/workflows/CI/badge.svg)](https://github.com/technote-space/laravel-search-helper/actions)
[![Build Status](https://travis-ci.com/technote-space/laravel-search-helper.svg?branch=master)](https://travis-ci.com/technote-space/laravel-search-helper)
[![codecov](https://codecov.io/gh/technote-space/laravel-search-helper/branch/master/graph/badge.svg)](https://codecov.io/gh/technote-space/laravel-search-helper)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper/badge)](https://www.codefactor.io/repository/github/technote-space/laravel-search-helper)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/technote-space/laravel-search-helper/blob/master/LICENSE)
[![PHP: >=7.3](https://img.shields.io/badge/PHP-%3E%3D7.3-orange.svg)](http://php.net/)

*Read this in other languages: [English](README.md), [日本語](README.ja.md).*

Laravel用検索ヘルパー

[Packagist](https://packagist.org/packages/technote/laravel-search-helper)

## Table of Contents
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
<details>
<summary>Details</summary>

- [インストール](#%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB)
- [使用方法](#%E4%BD%BF%E7%94%A8%E6%96%B9%E6%B3%95)
- [Author](#author)

</details>
<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## インストール
```
composer require technote/laravel-search-helper
```

## 使用方法
1. `Searchable Contract` と `Searchable Trait` を実装
1. `setConditions` メソッド を実装

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
        *
        * @return void
        */
       protected static function setConditions(Builder $query, array $conditions): void
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
1. `search`メソッドを呼び出し

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

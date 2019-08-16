# Gutenberg Package Versions

[![Build Status](https://travis-ci.com/technote-space/gutenberg-package-versions.svg?branch=master)](https://travis-ci.com/technote-space/gutenberg-package-versions)
[![Coverage Status](https://coveralls.io/repos/github/technote-space/gutenberg-package-versions/badge.svg?branch=master)](https://coveralls.io/github/technote-space/gutenberg-package-versions?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/gutenberg-package-versions/badge)](https://www.codefactor.io/repository/github/technote-space/gutenberg-package-versions)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![PHP: >=5.6](https://img.shields.io/badge/PHP-%3E%3D5.6-orange.svg)](http://php.net/)
[![WordPress: >=5.0](https://img.shields.io/badge/WordPress-%3E%3D5.0-brightgreen.svg)](https://wordpress.org/)

This repository fetches the versions of Gutenberg automatically every day.  
The version data is provided by `API` and `composer`.  
You can also use these data by [Wrapper](https://github.com/technote-space/gutenberg-packages).

## Last updated
|Gutenberg tag|WordPress tag|Last updated at|
|:---:|:---:|:---:|
|[v6.3.0-rc.1](https://api.wp-framework.dev/api/v1/gutenberg/tags/6.3.0-rc.1.json)|[v5.2.2](https://api.wp-framework.dev/api/v1/wp-core/tags/5.2.2.json)|[12 August 2019 15:46:23 UTC](https://travis-ci.com/technote-space/gutenberg-package-versions/builds/122850764)|

https://api.wp-framework.dev/api/v1/summary.json

## Data
### Versions of all tags
* `data/gutenberg-versions.json` (for Gutenberg Plugin)
* `data/wp-versions.json` (for WP Core)
#### Detail
- array of (`tag` => `packages`)
  - `packages`
    - array of (`wp-<package>` => `version`)
#### Example
```
{
  "v3.3.0": {
    "wp-a11y": "1.1.1",
    "wp-api-fetch": "1.0.1",
    "wp-autop": "1.1.1",
    
    ...
    
    "wp-url": "1.2.1",
    "wp-viewport": "1.0.1",
    "wp-wordcount": "1.1.1"
  },
  
  ...
  
  "v5.9.1": {
    "wp-a11y": "2.3.0",
    "wp-annotations": "1.3.0",
    "wp-api-fetch": "3.2.0",
    
    ...
    
    "wp-url": "2.6.0",
    "wp-viewport": "2.4.0",
    "wp-wordcount": "2.3.0"
  },
  "v5.9.2": {
    "wp-a11y": "2.3.0",
    "wp-annotations": "1.3.0",
    "wp-api-fetch": "3.2.0",
    
    ...
    
    "wp-url": "2.6.0",
    "wp-viewport": "2.4.0",
    "wp-wordcount": "2.3.0"
  }
  
  ...
  
}
```
### Versions of each bag
* `data/gutenberg/<TAG>.json` (for Gutenberg Plugin)
* `data/wordpress/<TAG>.json` (for WP Core)
#### Contents
- `packages`
  - `wp-<package>` => `version` 
#### Example
```json
{
  "wp-a11y": "2.0.0",
  "wp-api-fetch": "2.0.0",
  "wp-autop": "2.0.0",
  "wp-blob": "2.0.0",
  "wp-block-library": "2.0.0",
  "wp-block-serialization-default-parser": "1.0.0-rc.0",
  "wp-block-serialization-spec-parser": "1.0.1",
  "wp-blocks": "3.0.0",
  "wp-components": "3.0.0",
  "wp-compose": "2.0.0",
  "wp-core-data": "2.0.0",
  "wp-data": "2.0.0",
  "wp-date": "2.0.0",
  "wp-deprecated": "2.0.0",
  "wp-dom-ready": "2.0.0",
  "wp-dom": "2.0.0",
  "wp-editor": "3.0.0",
  "wp-element": "2.0.0",
  "wp-hooks": "2.0.0",
  "wp-html-entities": "2.0.0",
  "wp-i18n": "2.0.0",
  "wp-is-shallow-equal": "1.1.4",
  "wp-keycodes": "2.0.0",
  "wp-nux": "2.0.0",
  "wp-plugins": "2.0.0",
  "wp-redux-routine": "2.0.0",
  "wp-shortcode": "2.0.0",
  "wp-token-list": "1.0.0",
  "wp-url": "2.0.0",
  "wp-viewport": "2.0.0",
  "wp-wordcount": "2.0.0"
}
```

## Usage
### API
#### Endpoints
- for Gutenberg
  - Tags
    - https://api.wp-framework.dev/api/v1/gutenberg/tags.json
  - Versions
    - https://api.wp-framework.dev/api/v1/gutenberg/versions.json
  - Each tag
    - https://api.wp-framework.dev/api/v1/gutenberg/tags/${tag}.json
      - e.g.
        - https://api.wp-framework.dev/api/v1/gutenberg/tags/3.3.0.json
        - https://api.wp-framework.dev/api/v1/gutenberg/tags/5.1.1.json
        - https://api.wp-framework.dev/api/v1/gutenberg/tags/5.2.0.json
- for WP Core
  - Tags
    - https://api.wp-framework.dev/api/v1/wp-core/tags.json
  - Versions
    - https://api.wp-framework.dev/api/v1/wp-core/versions.json
  - Each tag
    - https://api.wp-framework.dev/api/v1/wp-core/tags/${tag}.json
      - e.g.
        - https://api.wp-framework.dev/api/v1/wp-core/tags/5.0.0.json
        - https://api.wp-framework.dev/api/v1/wp-core/tags/5.1.1.json
        - https://api.wp-framework.dev/api/v1/wp-core/tags/5.2.0.json
### composer
```bash
composer require technote/gutenberg-package-versions
```
#### Helper
```php
<?php

use Technote\GutenbergPackageVersionProvider;

// for Gutenberg
$provider = new GutenbergPackageVersionProvider();

$provider->get_tags(); // tags

$provider->get_versions(); // array of (tag => packages)
$provider->get_versions( '5.2.0' ); // array of (package => version)

$provider->get_package_version( '5.1.0', 'wp-block-editor' ); // false
$provider->get_package_version( '5.2.0', 'wp-block-editor' ); // 1.0.0-alpha.0

$provider->package_exists( '5.1', 'wp-block-editor' ); // false
$provider->package_exists( '5.2.0', 'wp-block-editor' ); // true
$provider->package_exists( 'v5.2', 'wp-block-editor' ); // true

// for WP Core
$provider = new GutenbergPackageVersionProvider( 'wp' );

$provider->get_tags(); // tags

$provider->get_versions(); // array of (tag => packages)
$provider->get_versions( '5.2.0' ); // array of (package => version)

$provider->get_package_version( '5.1.0', 'wp-block-editor' ); // false
$provider->get_package_version( '5.2.0', 'wp-block-editor' ); // 2.0.1

$provider->package_exists( '5.1', 'wp-block-editor' ); // false
$provider->package_exists( '5.2.0', 'wp-block-editor' ); // true
$provider->package_exists( 'v5.2', 'wp-block-editor' ); // true
```
#### Addition
- Tag format
  - 1     (= 1.0.0)
  - 1.2   (= 1.2.0)
  - 1.2.3
  - v1.2.3 (= 1.2.3)
  - v.1.2.3 (= 1.2.3)

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)

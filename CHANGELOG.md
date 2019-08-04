# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/model`.

## 3.8.2

Released: 2019-08-04

### Changes

* Use `static function` rather than `function` whenever possible, the PHP engine does not need to instantiate and later GC a `$this` variable for said closure.

## 3.8.1

Released: 2019-04-16

### Changes

* Allow to pass optional parameters to `Orchestra\Model\Concerns\Faker::faker()`.

## 3.8.0

Released: 2019-03-18

### Changes

* Update support to Laravel Framework 5.8.

## 3.7.2

Released: 2019-02-21

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

## 3.7.1

Released: 2018-12-04

### Added

* Add `Orchestra\Model\Concerns\Metable::forgetMetaData()` method helper.

## 3.7.0

Released: 2018-11-19

### Changes

* Update support to Laravel Framework 5.7.
* `Orchestra\Model\Concerns\Metable::mutateMetableAttribute()` should accept `$key` as first parameter to allow multiple JSON fields.

### Removed

* Remove deprecated `Orchestra\Model\Traits` namespace.

## 3.6.4

Released: 2018-12-04

### Added

* Add `Orchestra\Model\Concerns\Metable::forgetMetaData()` method helper.

## 3.6.3

Released: 2018-08-16

### Changes

* Avoid re-casting `meta` to instance of `Orchestra\Model\Value\Meta` when it already is.

## 3.6.2

Released: 2018-06-05

### Changes

* Improves `Orchestra\Model\Concerns\AdvancedSearchable` by parsing keyword using regular expression.

## 3.6.1

Released: 2018-05-08

### Added

* Added `Orchestra\Model\Concerns\AdvancedSearchable`.

### Changes

* Include `Orchestra\Model\Concerns\Searchable::scopeSearch()` by default.

## 3.6.0

Released: 2018-05-02

### Added

* Added `Orchestra\Model\Eloquent::column()`.
* Added `Orchestra\Model\Listeners\UserAccess`.

### Changes

* Update support to Laravel Framework 5.6.
* Rename the following classes:
    - `Orchestra\Model\Memory\UserMetaProvider` to `Orchestra\Model\Memory\UserProvider`.
    - `Orchestra\Model\Memory\UserMetaRepository` to `Orchestra\Model\Memory\UserRepository`.
* Rename the following traits:
    - `Orchestra\Model\Traits\CheckRoles` to `Orchestra\Model\Concerns\CheckRoles`.
    - `Orchestra\Model\Traits\Faker` to `Orchestra\Model\Concerns\Faker`.
    - `Orchestra\Model\Traits\Metable` to `Orchestra\Model\Concerns\Metable`.
    - `Orchestra\Model\Traits\OwnedBy` to `Orchestra\Model\Concerns\OwnedBy`.
    - `Orchestra\Model\Traits\Owns` to `Orchestra\Model\Concerns\Owns`.
    - `Orchestra\Model\Traits\Searchable` to `Orchestra\Model\Concerns\Searchable`.

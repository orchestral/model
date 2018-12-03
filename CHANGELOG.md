# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/model`.

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

## 3.5.6

Released: 2018-02-27

### Fixes

* Fixes type-hint declarations.

## 3.5.5

Released: 2018-02-21

## Fixes

* Fixes `Orchestra\Model\Value\Meta` method declaration and add tests to avoid regression issues.

## 3.5.4

Released: 2018-02-21

## Added

* Add `Orchestra\Model\Eloquent::saveIfExists()`.
* Add `Orchestra\Model\Eloquent::saveIfExistsOrFail()`.

### Changes

* Unset `roles` relationship when sync, attach or detach roles.

## 3.5.3

Released: 2017-11-29

### Changes

* Remove `orchestra/notifier` dependencies.

## 3.5.2

Released: 2017-11-28

### Changes

* Use available `fromJson()` and `castAttributeAsJson()` on `Orchestra\Model\Traits\Metable`.

## 3.5.1

Released: 2017-10-07

### Added

* Add `Orchestra\Model\Traits\Faker`.
* Add `Orchestra\Model\Traits\OwnedBy::scopeOwnedBy()` and `Orchestra\Model\Traits\Owns::scopeOwns()` query scope method.

### Changes

* `Orchestra\Model\Plugins\RefreshOnCreate` should refresh the model using `Illuminate\Database\Eloquent\Model::refresh()` method.

## 3.5.0

Released: 2017-09-03

### Changes

* Update support to Laravel Framework 5.5.

# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/model`.

## 4.3.0

Released: 2019-12-30

### Deprecated

* Deprecate `Orchestra\Model\Concerns\AdvancedSearch`, existing functionality has been merged with `Orchestra\Model\Concerns\Searchable`.
* Deprecate `getSearchableRules()`, use `getSearchableTerms()` insteads.

## 4.2.0

Released: 2019-11-03

### Changes

* Replace `Orchestra\Support\Concerns\QueryFilter` with `Laravie\QueryFilter\Orderable` and `Laravie\QueryFilter\Searchable`.
* Refactor `Orchestra\Model\Concerns\AdvancedSearch` to utilize `Laravie\QueryFilter\Taxanomy`.

## 4.1.0

Released: 2019-09-14

### Added

* Added `Orchestra\Model\HS`.
* Added `Orchestra\Model\Concerns\Swappable`.

### Changes

* Improves tests.

## 4.0.0

Released: 2019-09-11

### Changes

* Update support to Laravel Framework 6.0.

### Removed

* Remove deprecated `Orchestra\Model\UserMeta` class.
* Remove deprecated `Orchestra\Model\Eloquent::transaction()` method. 

# Changelog for 5.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/model`.

## 5.0.2

Released: 2020-04-02

### Deprecated

* Properly deprecate `Orchestra\Model\HS`, please use `Laravie\Dhosa\HotSwap`.

## 5.0.1

Released: 2020-04-02

### Changes

* Readd `Orchestra\Model\HS` for backward compatibility, do use `Laravie\Dhosa\HotSwap` whenever possible.
* Readd `Orchestra\Model\Concerns\Swappable` for backward compatibility, do use `Laravie\Dhosa\Concerns\Swappable` whenever possible.

## 5.0.0

Released: 2020-03-09

### Changes

* Update support to Laravel Framework v7.

### Removed

* Remove `Orchestra\Model\Concerns\Swappable`, use `Laravie\Dhosa\Concerns\Swappable`.
* Remove deprecated `Orchestra\Model\Concerns\AdvancedSearch`, use `Orchestra\Model\Concerns\Searchable`.
* Remove deprecated `getSearchableRules()`, use `getSearchableTerms()`.

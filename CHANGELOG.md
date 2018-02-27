# Changelog

This changelog references the relevant changes (bug and security fixes) done to `orchestra/model`.

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

## 3.4.0

Released: 2017-05-03

### Changes

* Update support to Laravel Framework 5.4.

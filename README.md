Model Component for Orchestra Platform
==============

This repository contains the model code of the Orchestra Platform. If you want to build an application using Orchestra Platform, visit [the main repository](https://github.com/orchestral/platform).

[![Build Status](https://travis-ci.org/orchestral/model.svg?branch=3.5)](https://travis-ci.org/orchestral/model)
[![Latest Stable Version](https://poser.pugx.org/orchestra/model/version)](https://packagist.org/packages/orchestra/model)
[![Total Downloads](https://poser.pugx.org/orchestra/model/downloads)](https://packagist.org/packages/orchestra/model)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/model/v/unstable)](//packagist.org/packages/orchestra/model)
[![License](https://poser.pugx.org/orchestra/model/license)](https://packagist.org/packages/orchestra/model)

## Resources

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Changelog](https://github.com/orchestral/model/releases)

## Version Compatibility

Laravel    | Model
:----------|:----------
 4.x.x     | 2.x.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x
 5.4.x     | 3.4.x
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x@dev

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/model": "~3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/model=~3.0"

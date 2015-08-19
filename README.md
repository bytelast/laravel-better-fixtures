PHP Fixtures
================

[![Build Status](https://travis-ci.org/yaodng/php-fixtures.svg)](https://travis-ci.org/yaodng/php-fixtures)

This package is inspired by [Rails](http://guides.rubyonrails.org/testing.html).

Fixtures
--------

Fixtures are a way of organizing data that you want to test against.

They are stored in YAML files, one file per model, which are placed in the directory you want.
The fixture file ends with the .yml file extension (Rails example: <your-rails-app>/test/fixtures/web_sites.yml).
The format of a fixture file looks like this:

```
eric:
  name: Eric Roston
  email: eric@example.com

jane:
  name: Jane Hunter
  email: jane@example.com
```

This fixture file includes two fixtures.
Each YAML fixture (ie. record) is given a name and is followed by an indented list of key/value pairs in the "key: value" format.
Records are separated by a blank line for your viewing pleasure.
Note that fixtures are unordered.


# laravel-psl

Laravel-first package that makes the PHP Standard Library (PSL) feel natural inside Laravel applications.

## Status

Foundation scaffolding is in place:

* Composer package metadata
* Laravel package discovery
* service provider
* publishable config
* PHPUnit and Testbench baseline

## Scope

The first release targets:

* Laravel 13.x
* PHP 8.4+
* PSL 6.x

Planned v1 focus:

* explicit bridge utilities between Collections, arrays, vecs, and dicts
* opt-in list-safe Collection macros
* helper-driven typed coercion built on `Psl\Type`
* Laravel-friendly exception handling

See [prd.md](./prd.md) for the product definition and [implementation-plan.md](./implementation-plan.md) for the execution checklist.

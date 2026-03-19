# Changelog

All notable changes to `laravel-psl` will be documented in this file.

## v1.0.0 - 2026-03-19

Initial public release.

### Added

- Explicit bridge helpers for converting between Laravel collections, PSL vec semantics, and PSL dict semantics.
- Opt-in list-safe collection macros: `pmap`, `pfilter`, `preduce`, `pchunk`, `psort`, `ptoVec`, and `ptoDict`.
- Thin typed coercion helper API through `LaravelPsl\Type\Coerce::value(...)`.
- Laravel-friendly coercion exception wrapping that preserves the original PSL exception as `previous`.
- Publishable package config with a single shipped V1 feature flag for collection macros.
- PHPUnit and Testbench coverage for bridges, macros, coercion, config publishing, and boot behavior.
- Laravel fixture-app smoke test that verifies Composer package auto-discovery in a real Laravel 13 application install.

### Notes

- V1 targets Laravel 13.x, PHP 8.4+, and PSL 6.x.
- Dict-aware transform macros, Form Request coercion adapters, string adapters, and runtime-aware concurrency integration remain out of scope for V1.

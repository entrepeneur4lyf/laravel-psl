# Implementation Plan: `laravel-psl`

This file is the execution checklist for the first release of `laravel-psl`.

## Tracking

- [ ] Not started
- [x] Done

## V1 Goal

- [ ] Ship a Laravel 13 package on PHP 8.4+ that provides explicit PSL bridge utilities, opt-in list-safe Collection macros, typed coercion helpers built on `Psl\Type`, Laravel-friendly exception handling, and test coverage.

## Phase 1: Foundation

- [x] Create `composer.json` for the package with PHP `^8.4` and Laravel 13 support.
- [x] Define package namespace and PSR-4 autoloading.
- [x] Add package discovery metadata for the service provider.
- [x] Create `src/LaravelPslServiceProvider.php`.
- [x] Create `config/psl.php`.
- [x] Register config publishing in the service provider.
- [x] Merge package config in the service provider.
- [x] Add config for shipped runtime behavior, starting with `collection_macros`.
- [x] Add base test infrastructure with Orchestral Testbench.
- [x] Add PHPUnit or Pest configuration for package tests.

## Phase 2: Core Bridge Utilities

- [x] Create `src/Support/PslBridge.php`.
- [x] Create `src/Support/CollectionBridge.php`.
- [x] Create `src/Support/DictBridge.php`.
- [x] Implement `toVec()` with explicit list semantics.
- [x] Implement `toDict()` with explicit key-preserving semantics.
- [x] Implement `toCollection()` for converting iterables into Laravel Collections.
- [x] Document irreversible conversions in code docblocks and tests.
- [x] Add tests for numeric-key, string-key, and mixed-key inputs.
- [x] Confirm `toDict()` preserves mixed runtime `array-key` inputs exactly as provided by PHP.
- [x] Add tests that confirm `toVec()` reindexes values intentionally.
- [x] Add tests that confirm `toDict()` preserves keys intentionally.

## Phase 3: Collection Macros

- [x] Create `src/Macros/RegistersCollectionMacros.php`.
- [x] Create `src/Macros/CollectionMacros.php`.
- [x] Register macros only when `collection_macros` is enabled.
- [x] Implement `pmap`.
- [x] Implement `pfilter`.
- [x] Implement `preduce` as a terminal operation requiring an explicit initial accumulator.
- [x] Implement `pchunk`.
- [x] Implement `psort` as a list-based sort that returns a reindexed Collection.
- [x] Implement `ptoVec`.
- [x] Implement `ptoDict`.
- [x] Ensure fluent transform macros return Laravel Collections.
- [x] Ensure extraction macros return plain arrays.
- [x] Ensure list-style macros use `array_values($collection->all())` before vec operations.
- [x] Ensure `preduce` returns the final accumulator rather than a Collection.
- [x] Do not add dict-aware transform macros in v1.
- [x] Add tests for registration and non-registration based on config.
- [x] Add tests for return types and key reindexing behavior.
- [x] Add tests for empty collections and invalid arguments where relevant.

## Phase 4: Typed Coercion

- [x] Create `src/Type/Coerce.php`.
- [x] Define `Coerce::value($type, $input)` as the initial public API for helper-driven coercion.
- [x] Keep the API thin over `Psl\Type` rather than introducing a new DSL.
- [x] Support request payload usage.
- [x] Support config payload usage.
- [x] Support third-party API payload usage.
- [x] Support job payload usage.
- [x] Support DTO pre-hydration checks.
- [x] Preserve static-analysis-friendly return types where possible.
- [x] Do not couple core coercion to Form Requests in v1.
- [x] Add tests for successful coercion flows.
- [x] Add tests for coercion failures and error messages.

## Phase 5: Exception Experience

- [x] Create `src/Exceptions/LaravelPslException.php`.
- [x] Create `src/Exceptions/CoercionException.php`.
- [x] Preserve the original PSL exception as `previous`.
- [x] Add Laravel-friendly failure messages for coercion errors.
- [x] Add optional contextual payload metadata strategy.
- [x] Avoid blanket-wrapping PSL exceptions where no value is added.
- [x] Add tests that verify exception wrapping behavior.

## Phase 6: Documentation and Examples

- [x] Create or update `README.md`.
- [x] Add installation instructions.
- [x] Add configuration examples.
- [x] Add bridge utility examples.
- [x] Add Collection macro examples.
- [x] Add typed coercion examples for controller, job, command, and service use cases.
- [x] Document list-safe macro semantics clearly.
- [x] Document `ptoVec()` vs `ptoDict()` clearly.
- [x] Document disabled-feature behavior clearly.
- [x] Document what is intentionally out of scope for v1.

## Phase 7: Quality Gate

- [x] Add a Laravel 13 test matrix.
- [ ] Verify package auto-discovery works in Testbench.
- [x] Verify config publishing works.
- [x] Verify macros are opt-in and do not register unexpectedly.
- [x] Verify the public V1 config contains only shipped features.
- [x] Run full automated test suite successfully.
- [x] Run static analysis successfully.
- [x] Run code style checks successfully.
- [x] Review public API naming for consistency before first tag.

Note: the current Testbench harness still registers the provider manually. Composer discovery metadata is covered, but runtime auto-discovery inside Testbench remains an open verification item.

## Release Checklist

- [x] Confirm the package requires PHP `^8.4`.
- [x] Confirm the package targets Laravel `^13.0`.
- [x] Confirm PSL `6.x` is required.
- [ ] Confirm all v1 acceptance criteria from `prd.md` are met.
- [x] Confirm docs match actual implemented API.
- [ ] Prepare initial version tag and release notes.

## Later, Not V1

- [ ] Add config-driven macro granularity beyond the initial feature flags.
- [ ] Add Form Request adapter layer for typed coercion.
- [ ] Add validation-rule adapters for typed payload constraints.
- [ ] Add additional key-aware collection helpers if real-world demand justifies them.
- [ ] Add runtime-aware concurrency capability detection.
- [ ] Add public concurrency config only when the concurrency feature is implemented.
- [ ] Add concurrency wrapper aligned with Laravel’s configured driver.
- [ ] Add package-level concurrency driver override as an explicit escape hatch.
- [ ] Add Octane-aware optimizations only if they provide measurable value.

## Explicitly Deferred

- [ ] String adapters are intentionally deferred until real-world demand justifies them.

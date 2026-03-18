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
- [x] Add feature flags for `collection_macros`, `typed_coercion`, and `concurrency`.
- [x] Add base test infrastructure with Orchestral Testbench.
- [x] Add PHPUnit or Pest configuration for package tests.

## Phase 2: Core Bridge Utilities

- [ ] Create `src/Support/PslBridge.php`.
- [ ] Create `src/Support/CollectionBridge.php`.
- [ ] Create `src/Support/DictBridge.php`.
- [ ] Implement `toVec()` with explicit list semantics.
- [ ] Implement `toDict()` with explicit key-preserving semantics.
- [ ] Implement `toCollection()` for converting iterables into Laravel Collections.
- [ ] Document irreversible conversions in code docblocks and tests.
- [ ] Add tests for numeric-key, string-key, and mixed-key inputs.
- [ ] Add tests that confirm `toVec()` reindexes values intentionally.
- [ ] Add tests that confirm `toDict()` preserves keys intentionally.

## Phase 3: Collection Macros

- [ ] Create `src/Macros/RegistersCollectionMacros.php`.
- [ ] Create `src/Macros/CollectionMacros.php`.
- [ ] Register macros only when `collection_macros` is enabled.
- [ ] Implement `pmap`.
- [ ] Implement `pfilter`.
- [ ] Implement `preduce`.
- [ ] Implement `pchunk`.
- [ ] Implement `psort`.
- [ ] Implement `ptoVec`.
- [ ] Implement `ptoDict`.
- [ ] Ensure fluent transform macros return Laravel Collections.
- [ ] Ensure extraction macros return plain arrays.
- [ ] Ensure list-style macros use `array_values($collection->all())` before vec operations.
- [ ] Do not add dict-aware transform macros in v1.
- [ ] Add tests for registration and non-registration based on config.
- [ ] Add tests for return types and key reindexing behavior.
- [ ] Add tests for empty collections and invalid arguments where relevant.

## Phase 4: Typed Coercion

- [ ] Create `src/Type/Coerce.php`.
- [ ] Define the initial public API for helper-driven coercion.
- [ ] Keep the API thin over `Psl\Type` rather than introducing a new DSL.
- [ ] Implement `from(...)` or equivalent explicit coercion entry point.
- [ ] Support request payload usage.
- [ ] Support config payload usage.
- [ ] Support third-party API payload usage.
- [ ] Support job payload usage.
- [ ] Support DTO pre-hydration checks.
- [ ] Preserve static-analysis-friendly return types where possible.
- [ ] Do not couple core coercion to Form Requests in v1.
- [ ] Add tests for successful coercion flows.
- [ ] Add tests for coercion failures and error messages.

## Phase 5: Exception Experience

- [ ] Create `src/Exceptions/LaravelPslException.php`.
- [ ] Create `src/Exceptions/CoercionException.php`.
- [ ] Preserve the original PSL exception as `previous`.
- [ ] Add Laravel-friendly failure messages for coercion errors.
- [ ] Add optional contextual payload metadata strategy.
- [ ] Avoid blanket-wrapping PSL exceptions where no value is added.
- [ ] Add tests that verify exception wrapping behavior.

## Phase 6: Documentation and Examples

- [ ] Create or update `README.md`.
- [ ] Add installation instructions.
- [ ] Add configuration examples.
- [ ] Add bridge utility examples.
- [ ] Add Collection macro examples.
- [ ] Add typed coercion examples for controller, job, command, and service use cases.
- [ ] Document list-safe macro semantics clearly.
- [ ] Document `ptoVec()` vs `ptoDict()` clearly.
- [ ] Document disabled-feature behavior clearly.
- [ ] Document what is intentionally out of scope for v1.

## Phase 7: Quality Gate

- [ ] Add a Laravel 13 test matrix.
- [ ] Verify package auto-discovery works in Testbench.
- [ ] Verify config publishing works.
- [ ] Verify macros are opt-in and do not register unexpectedly.
- [ ] Verify the package works without concurrency features enabled.
- [ ] Run full automated test suite successfully.
- [ ] Run static analysis successfully.
- [ ] Run code style checks successfully.
- [ ] Review public API naming for consistency before first tag.

## Release Checklist

- [ ] Confirm the package requires PHP `^8.4`.
- [ ] Confirm the package targets Laravel `^13.0`.
- [ ] Confirm PSL `6.x` is required.
- [ ] Confirm all v1 acceptance criteria from `prd.md` are met.
- [ ] Confirm docs match actual implemented API.
- [ ] Prepare initial version tag and release notes.

## Later, Not V1

- [ ] Add config-driven macro granularity beyond the initial feature flags.
- [ ] Add Form Request adapter layer for typed coercion.
- [ ] Add validation-rule adapters for typed payload constraints.
- [ ] Add additional key-aware collection helpers if real-world demand justifies them.
- [ ] Add runtime-aware concurrency capability detection.
- [ ] Add concurrency wrapper aligned with Laravel’s configured driver.
- [ ] Add package-level concurrency driver override as an explicit escape hatch.
- [ ] Add Octane-aware optimizations only if they provide measurable value.

## Explicitly Deferred

- [ ] String adapters are intentionally deferred until real-world demand justifies them.

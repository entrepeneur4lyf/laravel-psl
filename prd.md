# PRD: `laravel-psl`

## Status

Draft v0.2

## Working Title

`laravel-psl`

## Summary

Build a Laravel-first package that makes the PHP Standard Library (PSL) feel natural inside Laravel applications.

The package targets:

* **Laravel 13.x**
* **PHP 8.4+**
* **PSL 6.x**

The initial product should focus on explicit bridges between Laravel and PSL data structures, opt-in Collection macros, and typed coercion helpers built on `Psl\Type`. Optional concurrency integration should be treated as a later, runtime-aware capability aligned with Laravel's concurrency layer, not as a separate Octane package and not as a hard dependency for the core package. Laravel 13 is the framework baseline, while PSL 6.x sets the effective PHP floor at 8.4+. ([Laravel][1], [PSL][2])

## Problem

PSL gives PHP developers a safer, more consistent, strongly typed utility layer across collections, strings, validation/coercion, async, networking, I/O, and more. In Laravel apps, however, PSL still feels like a separate library rather than a framework-native development style.

Developers currently need to:

* manually import PSL namespaces,
* manually convert between arrays, Collections, and PSL vec/dict semantics,
* decide ad hoc where PSL belongs in request, job, command, and service code,
* build their own conventions for typed coercion around untrusted payloads.

## Opportunity

A thin but polished Laravel bridge could:

* lower the adoption friction for PSL in Laravel applications,
* create a Laravel-native DX around typed transformations and coercion,
* encourage safer utility usage without forcing developers to abandon Laravel idioms,
* provide a clear path for optional concurrency integration later without making runtime-specific behavior part of the core package contract.

## Product Vision

Make PSL feel like a **first-class enhancement to Laravel**, not a foreign utility layer.

The package should let a Laravel developer say:

* “I want PSL power, but I want it to feel like Laravel.”
* “I want safer typed transforms without rewriting my whole app.”
* “I want typed coercion before data reaches my domain layer.”
* “If I use Laravel concurrency features later, I want this package to integrate cleanly.”

## Goals

### Primary Goals

* Provide a clean Laravel 13 package for PSL adoption.
* Make common PSL usage ergonomic in Laravel services, actions, jobs, commands, and controllers.
* Expose opt-in Collection macros for well-defined PSL-style transforms.
* Provide explicit bridge helpers between Laravel `Collection`, arrays, and PSL vec/dict conventions.
* Add typed coercion helpers for request, config, job, and third-party payload handling.
* Keep the package safe and valuable in normal Laravel applications with no concurrency runtime requirements.

### Secondary Goals

* Provide strong static-analysis friendliness.
* Offer excellent error messages and package-level DX.
* Leave room for optional, runtime-aware concurrency helpers in a later milestone.

## Non-Goals

* Replacing Laravel Collections.
* Replacing Laravel’s container, validation, queues, or pipeline systems.
* Introducing a second type system on top of `Psl\Type`.
* Hiding PSL completely behind Laravel facades.
* Deep async rewrites of the Laravel request lifecycle.
* Requiring Octane, Swoole, Open Swoole, or any concurrency driver to use the package.

## Target Users

### Primary

Laravel developers on PHP 8.4+ who want:

* stronger typing,
* safer collection / array / string operations,
* predictable coercion for untrusted input,
* a better bridge between modern PHP and Laravel ergonomics.

### Secondary

Teams using:

* queue-heavy processing,
* internal packages or platform libraries,
* long-running workers,
* Laravel concurrency features,
* optional Octane deployments.

## Assumptions and Constraints

The package will target:

* **Laravel 13.x**
* **PSL 6.x**
* **PHP 8.4+**
* standard Laravel package discovery,
* a single service provider,
* publishable configuration,
* macro-based extension points for Collections and related Laravel surfaces.

Concurrency integration, if added, must:

* remain optional,
* align with Laravel’s concurrency abstractions before introducing runtime-specific behavior,
* degrade safely when unsupported or disabled,
* avoid creating a hard dependency on Octane or a separate Octane-only package. ([Laravel][3])

## Product Scope

## V1 Scope

### 1) Core Package Bootstrapping

Ship as a standard Laravel package with:

* package discovery,
* one service provider,
* publishable config,
* feature flags for macros and typed coercion,
* Testbench-based integration coverage.

### 2) Explicit Bridge Utilities

Add helpers or small classes for:

* `Collection -> vec`
* `Collection -> dict`
* `array -> Collection`
* keyed arrays <-> PSL-style dict semantics
* explicit conversions where return-type changes are irreversible

Example:

```php
use Vendor\LaravelPsl\Support\PslBridge;

$vec = PslBridge::toVec($collection);
$dict = PslBridge::toDict($assocArray);
$collection = PslBridge::toCollection($vec);
```

### 3) Collection Macros

Add opt-in macros for a small, well-defined initial surface such as:

* `pmap`
* `pfilter`
* `preduce`
* `pchunk`
* `psort`
* `ptoVec`
* `ptoDict`

V1 includes only **list-safe** Collection macros.

V1 macro semantics must be explicit:

* fluent transform macros return Laravel Collections,
* extraction macros return plain arrays,
* list-style macros use PSL vec semantics and therefore operate on collection values in a clearly documented way,
* dict-aware transforms stay in explicit bridge helpers until a later release,
* key-preserving behavior must not be ambiguous.

Example desired API:

```php
$users = collect($payload['users'])
    ->pfilter(fn (array $user) => $user['active'] ?? false)
    ->pmap(fn (array $user) => [
        'email' => strtolower($user['email']),
        'name' => trim($user['name']),
    ]);
```

### 4) Type / Coercion Integration

Introduce Laravel-friendly typed coercion utilities for:

* request payloads,
* config payloads,
* third-party API payloads,
* job payload validation,
* DTO hydration pre-checks.

The package should wrap `Psl\Type` ergonomically without hiding it or replacing it. In core, coercion remains helper-driven. Form Request integration is a later adapter layer rather than part of the initial coercion contract.

Example:

```php
use Psl\Type;
use Vendor\LaravelPsl\Type\Coerce;

$user = Coerce::shape([
    'name' => Type\non_empty_string(),
    'email' => Type\non_empty_string(),
    'roles' => Type\vec(Type\string()),
])->from($request->all());
```

### 5) Exception Experience

Provide package-defined exceptions where they materially improve DX, especially around coercion failures.

Rules:

* preserve the original PSL exception as the `previous` exception,
* provide readable Laravel-friendly messages,
* optionally attach contextual payload metadata for logging,
* avoid blanket-wrapping PSL exceptions when no additional value is added.

### 6) Testing Support

Ship:

* Pest / PHPUnit helpers where justified,
* assertions for coercion failures,
* macro registration tests,
* integration tests using Orchestral Testbench.

## V1.1 Scope

* config-driven macro granularity
* request coercion helper traits or Form Request adapters
* validation-rule adapters for common typed payload constraints
* additional key-aware collection bridges if real-world demand emerges

## V2 Scope

* optional runtime-aware concurrency adapters
* helpers aligned with Laravel’s concurrency layer
* capability detection and clear failure modes
* sync fallback behavior or explicit unsupported-runtime exceptions
* optional Octane-aware optimizations only if they add measurable value

## User Stories

### Developer Ergonomics

* As a Laravel developer, I want to use PSL transforms without constantly converting between Collection and array by hand.
* As a Laravel developer, I want typed coercion for API input before it hits my domain layer.
* As a package maintainer, I want the package to auto-register and just work.
* As a Laravel developer, I want explicit semantics when transforms switch from Collections to arrays.

### Team / Platform

* As a tech lead, I want safer utility usage standardized across our Laravel codebase.
* As a platform team, I want feature flags so we can adopt macros incrementally.
* As a team using Laravel concurrency features, I want later integration to be runtime-aware and optional.

## Functional Requirements

## FR1: Installation

Developer installs with Composer and the package auto-discovers with no manual provider registration in the default case.

## FR2: Config

The package provides a publishable config file, for example:

```php
return [
    'features' => [
        'collection_macros' => true,
        'typed_coercion' => true,
        'concurrency' => false,
    ],

    'concurrency' => [
        'enabled' => false,
        'driver' => null,
        'fail_when_unsupported' => true,
    ],
];
```

`driver => null` means “use Laravel’s configured default” if concurrency integration is enabled in a future release.

## FR3: Bridge Utilities

The package exposes explicit bridge utilities for vec, dict, and Collection conversions, with documented return types and no hidden key semantics.

## FR4: Collection Macros

When enabled, Collection macros are registered during package boot.

Macro requirements:

* macro names remain explicit and do not shadow native Collection methods,
* list-style operations document when keys are reindexed,
* macros that preserve fluent chaining return Collections,
* V1 excludes dict-aware transform macros,
* extraction methods such as `ptoVec()` and `ptoDict()` return arrays.

## FR5: Typed Coercion Helpers

The package exposes Laravel-friendly coercion helpers built on `Psl\Type`.

Requirements:

* coercion APIs remain explicit,
* coercion results are static-analysis-friendly,
* the core package keeps coercion helper-driven instead of coupling it to Form Requests,
* coercion failures surface readable package errors while preserving original PSL exception context.

## FR6: Exception Experience

On coercion failure, developers get:

* a readable message,
* the original PSL exception attached as `previous`,
* optional contextual metadata for logging or debugging.

## FR7: Concurrency Integration

If concurrency integration is introduced, it must:

* live inside the main package,
* be optional and disabled by default,
* align with Laravel’s concurrency abstractions,
* default to Laravel’s configured concurrency driver,
* treat any package-level driver setting as an override, not a second default,
* detect capabilities at runtime,
* avoid runtime fatals when unsupported.

Example desired API:

```php
use Vendor\LaravelPsl\Concurrency\Concurrent;

[$profile, $orders] = Concurrent::run([
    fn () => $this->profileService->fetch($userId),
    fn () => $this->orderService->recent($userId),
]);
```

The initial implementation should prefer capability-based detection over Octane-specific branching. Octane support, if useful, is an optimization path inside the same package rather than a separate addon.

## Proposed Package Architecture

```text
laravel-psl/
├── src/
│   ├── LaravelPslServiceProvider.php
│   ├── Support/
│   │   ├── PslBridge.php
│   │   ├── CollectionBridge.php
│   │   └── DictBridge.php
│   ├── Macros/
│   │   ├── RegistersCollectionMacros.php
│   │   └── CollectionMacros.php
│   ├── Type/
│   │   ├── Coerce.php
│   │   └── Exceptions/
│   ├── Exceptions/
│   │   ├── LaravelPslException.php
│   │   └── CoercionException.php
│   ├── Concurrency/
│   │   ├── Concurrent.php
│   │   └── RuntimeCapabilities.php
│   └── Testing/
│       ├── Concerns/
│       └── Assertions/
├── config/
│   └── psl.php
├── tests/
└── composer.json
```

## Developer Experience Principles

* Laravel-flavored naming.
* PSL semantics preserved where practical.
* Opt-in macros, not surprise global behavior.
* Explicit bridges for irreversible conversions.
* No hidden key rewriting.
* Great error messages.
* Static-analysis-friendly signatures.
* Safe degradation when optional concurrency support is unavailable.
* One package, with optional capabilities, rather than runtime-specific package fragmentation.

## Example Macro Design

Example initial Collection macros:

```php
Collection::macro('pmap', function (Closure $closure) {
    return collect(Psl\Vec\map(array_values($this->all()), $closure));
});

Collection::macro('pfilter', function (Closure $closure) {
    return collect(Psl\Vec\filter(array_values($this->all()), $closure));
});

Collection::macro('pchunk', function (int $size) {
    return collect(Psl\Vec\chunk(array_values($this->all()), $size));
});

Collection::macro('ptoVec', function () {
    return array_values($this->all());
});

Collection::macro('ptoDict', function () {
    return $this->all();
});
```

## Acceptance Criteria

## V1 Release Criteria

* Installs cleanly in Laravel 13 on PHP 8.4+.
* Auto-discovers in Laravel with no manual setup in the common case.
* Publishes config successfully.
* Registers opt-in Collection macros.
* Includes explicit bridge helpers.
* Includes typed coercion helpers with clean exception handling.
* Has automated package tests.
* Has documentation with examples for controller, job, command, and service usage.

## V1 Quality Bar

* Clear behavior when macros are disabled.
* Clear documentation for list semantics and return types.
* No hidden mutation or global surprise behavior.
* No hard dependency on Octane or runtime-specific concurrency packages.
* Consistent exception wrapping rules documented.

## Success Metrics

### Adoption

* First successful use inside one real Laravel 13 application.
* At least 3 repeated package usage patterns emerge from real app code.

### DX

* Install-to-first-use under 10 minutes.
* Collection macro usage understandable from docs alone.
* Typed coercion failures are easier to debug than ad hoc validation or casting.

### Technical

* 90%+ test coverage on bridges, macros, and coercion helpers.
* No unsupported runtime crashes in apps that do not enable concurrency features.
* Zero breaking changes inside v1 minor releases.

## Risks

### Risk 1: Identity Confusion

Developers may not know when to use native Laravel Collection methods vs PSL-backed methods.

Mitigation:

* keep names explicit (`pmap`, `pfilter`) instead of shadowing native methods,
* document when to use native Laravel methods vs bridge helpers,
* keep V1 focused on a small, obvious API surface.

### Risk 2: Return-Type and Key-Semantics Ambiguity

Mixing Collections, vec semantics, and dict semantics can confuse users.

Mitigation:

* use strong naming for extraction methods like `ptoVec()` and `ptoDict()`,
* document list-style macros as value-based operations,
* keep key-preserving behavior explicit.

### Risk 3: Coercion Abstraction Creep

A Laravel wrapper around `Psl\Type` could become a second abstraction layer that obscures PSL itself.

Mitigation:

* keep `Coerce` thin,
* preserve `Psl\Type` concepts directly in the public API,
* avoid inventing a parallel DSL.

### Risk 4: Runtime-Specific Concurrency Complexity

Concurrency behavior can become brittle if the package assumes a specific runtime too early.

Mitigation:

* keep concurrency out of the initial release,
* align later integration with Laravel’s concurrency layer first,
* use capability-based detection instead of Octane-only assumptions,
* keep Octane support optional and internal to the same package.

## Resolved Decisions

* V1 includes only list-safe Collection macros. Dict-aware transforms remain explicit bridge helpers until later.
* Typed coercion remains helper-driven in core. Form Request integration, if added, comes later as a thin adapter.
* Concurrency helpers default to Laravel’s configured concurrency driver. Package-level driver configuration is only an explicit override.
* String adapters are deferred until real-world demand justifies them and are not part of the planned v1.1 scope.

## Delivery Plan

### Milestone 1

Foundation

* Composer package
* service provider
* config
* package discovery
* Testbench setup

### Milestone 2

Core bridges and macros

* explicit bridge utilities
* first Collection macros
* return-type documentation
* examples

### Milestone 3

Typed coercion

* coercion helper
* coercion exceptions
* request / payload examples
* tests

### Milestone 4

Optional concurrency integration

* runtime capability detection
* concurrency wrapper
* failure-mode documentation
* examples
* performance notes if justified

## Final Product Positioning

`laravel-psl` should be marketed as:

**“A Laravel-first bridge to PSL for Laravel 13: safer transforms, explicit data bridges, typed coercion, and optional runtime-aware concurrency on PHP 8.4+.”**

[1]: https://laravel.com/docs/13.x/releases "Laravel 13 Release Notes"
[2]: https://github.com/php-standard-library/php-standard-library "PSL - PHP Standard Library"
[3]: https://laravel.com/docs/13.x/concurrency "Laravel Concurrency"

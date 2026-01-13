## 0.8.11 2026-01-13

## 0.8.10 2026-01-13

## 0.8.9 2026-01-13

## 0.8.8 2026-01-13

## 0.8.7 2026-01-13

## 0.8.6 2026-01-13
* Added RegistryKeys constants.

## 0.8.5 2026-01-07
* Added ProblemDetails class for standardized error responses (RFC 7807)
* Adds additional http code based events.

## 0.8.4 2025-12-11
* Added system abstractions for improved testability across the framework
* **IClock interface** with `RealClock` and `FrozenClock` implementations - enables instant, deterministic time-based testing (no more sleep!)
* **IRandom interface** with `RealRandom` (cryptographically secure) and `FakeRandom` (predictable) implementations - enables deterministic random value testing
* **IHttpClient/IHttpResponse interfaces** with `RealHttpClient` (curl-based) and `MemoryHttpClient` (in-memory) implementations - enables HTTP testing without external dependencies
* **ISession interface** with `RealSession` ($_SESSION wrapper) and `MemorySession` (in-memory) implementations - enables isolated session testing
* All abstractions fully tested with 122 comprehensive tests
* Time-dependent tests now run 200-250x faster using FrozenClock
* Used by MVC FileCacheStorage, Routing RateLimitStorage, and CMS CsrfToken

## 0.8.3 2025-12-10
## 0.8.2 2025-12-10
* Extended IFileSystem with directory operations: `mkdir()`, `rmdir()`, `scandir()`, and `unlink()` for comprehensive file system abstraction
* Enables full testability of file-dependent code across the framework
* Used by MVC FileCacheStorage for complete in-memory testing

## 0.8.1 2025-12-10
* Added file system abstractions: `IFileSystem`, `RealFileSystem`, `MemoryFileSystem`
* Core methods: `fileExists()`, `readFile()`, `writeFile()`, `isDir()`, `realpath()`, `getcwd()`
* Provides testable abstraction over PHP native file functions
* Enables components to be unit tested without touching real file system

## 0.8.0 2025-11-11
* Added pascalCase, toUpper and toLower functions to NString.
* Conversion to camelcase.

## 0.7.1 2025-02-06

## 0.1.0 2025-02-06
* Initial version.

## 0.8.5

## 0.8.4 2025-12-11

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

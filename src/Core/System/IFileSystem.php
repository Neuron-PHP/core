<?php

namespace Neuron\Core\System;

/**
 * Interface for file system operations abstraction.
 *
 * Provides a testable abstraction over file system operations. Implementations
 * can be real file system access for production or in-memory systems for testing.
 */
interface IFileSystem
{
	/**
	 * Check if a file exists
	 *
	 * @param string $path File path
	 * @return bool True if file exists, false otherwise
	 */
	public function fileExists( string $path ): bool;

	/**
	 * Read entire file contents
	 *
	 * @param string $path File path
	 * @return string|false File contents or false on failure
	 */
	public function readFile( string $path ): string|false;

	/**
	 * Check if path is a directory
	 *
	 * @param string $path Directory path
	 * @return bool True if directory exists, false otherwise
	 */
	public function isDir( string $path ): bool;

	/**
	 * Get absolute path (resolve symlinks, relative paths)
	 *
	 * @param string $path Path to resolve
	 * @return string|false Resolved absolute path or false on failure
	 */
	public function realpath( string $path ): string|false;

	/**
	 * Get current working directory
	 *
	 * @return string|false Current directory or false on failure
	 */
	public function getcwd(): string|false;

	/**
	 * Write data to file
	 *
	 * @param string $path File path
	 * @param string $data Data to write
	 * @return int|false Number of bytes written or false on failure
	 */
	public function writeFile( string $path, string $data ): int|false;
}

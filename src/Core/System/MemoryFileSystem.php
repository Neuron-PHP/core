<?php

namespace Neuron\Core\System;

/**
 * In-memory file system implementation for testing.
 *
 * Provides a virtual file system that exists entirely in memory. Files and
 * directories can be programmatically added for testing without touching the
 * real file system. Perfect for unit testing file-dependent operations.
 */
class MemoryFileSystem implements IFileSystem
{
	/**
	 * @var array<string, string> In-memory files (path => content)
	 */
	private array $files = [];

	/**
	 * @var array<string, bool> In-memory directories (path => true)
	 */
	private array $directories = [];

	/**
	 * @var string Current working directory
	 */
	private string $cwd = '/';

	/**
	 * Add a file to the virtual file system
	 *
	 * @param string $path File path
	 * @param string $content File content
	 * @return void
	 */
	public function addFile( string $path, string $content ): void
	{
		$this->files[$path] = $content;
	}

	/**
	 * Add a directory to the virtual file system
	 *
	 * @param string $path Directory path
	 * @return void
	 */
	public function addDirectory( string $path ): void
	{
		$this->directories[$path] = true;
	}

	/**
	 * Set the current working directory
	 *
	 * @param string $path Directory path
	 * @return void
	 */
	public function setCwd( string $path ): void
	{
		$this->cwd = $path;
	}

	/**
	 * Remove a file from the virtual file system
	 *
	 * @param string $path File path
	 * @return void
	 */
	public function removeFile( string $path ): void
	{
		unset( $this->files[$path] );
	}

	/**
	 * Remove a directory from the virtual file system
	 *
	 * @param string $path Directory path
	 * @return void
	 */
	public function removeDirectory( string $path ): void
	{
		unset( $this->directories[$path] );
	}

	/**
	 * Clear all files and directories
	 *
	 * @return void
	 */
	public function clear(): void
	{
		$this->files = [];
		$this->directories = [];
		$this->cwd = '/';
	}

	/**
	 * @inheritDoc
	 */
	public function fileExists( string $path ): bool
	{
		return isset( $this->files[$path] );
	}

	/**
	 * @inheritDoc
	 */
	public function readFile( string $path ): string|false
	{
		return $this->files[$path] ?? false;
	}

	/**
	 * @inheritDoc
	 */
	public function isDir( string $path ): bool
	{
		return isset( $this->directories[$path] );
	}

	/**
	 * @inheritDoc
	 */
	public function realpath( string $path ): string|false
	{
		// Simplistic realpath: just check if file or directory exists
		if( $this->fileExists( $path ) || $this->isDir( $path ) )
		{
			return $path;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getcwd(): string|false
	{
		return $this->cwd;
	}

	/**
	 * @inheritDoc
	 */
	public function writeFile( string $path, string $data ): int|false
	{
		$this->files[$path] = $data;
		return strlen( $data );
	}

	/**
	 * Get all files in the virtual file system
	 *
	 * @return array<string, string>
	 */
	public function getFiles(): array
	{
		return $this->files;
	}

	/**
	 * Get all directories in the virtual file system
	 *
	 * @return array<string, bool>
	 */
	public function getDirectories(): array
	{
		return $this->directories;
	}
}

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

	/**
	 * @inheritDoc
	 */
	public function unlink( string $path ): bool
	{
		if( !isset( $this->files[$path] ) )
		{
			return false;
		}
		unset( $this->files[$path] );
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function mkdir( string $path, int $permissions = 0755, bool $recursive = true ): bool
	{
		if( isset( $this->directories[$path] ) )
		{
			return true;
		}

		if( $recursive )
		{
			// Create parent directories if needed
			$parts = explode( '/', trim( $path, '/' ) );
			$current = '';
			foreach( $parts as $part )
			{
				$current .= '/' . $part;
				if( !isset( $this->directories[$current] ) )
				{
					$this->directories[$current] = true;
				}
			}
		}

		$this->directories[$path] = true;
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function rmdir( string $path ): bool
	{
		if( !isset( $this->directories[$path] ) )
		{
			return false;
		}

		// Check if directory is empty
		foreach( $this->files as $filePath => $content )
		{
			if( strpos( $filePath, $path . '/' ) === 0 )
			{
				return false; // Directory not empty
			}
		}

		foreach( $this->directories as $dirPath => $exists )
		{
			if( $dirPath !== $path && strpos( $dirPath, $path . '/' ) === 0 )
			{
				return false; // Directory not empty
			}
		}

		unset( $this->directories[$path] );
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function scandir( string $path ): array|false
	{
		if( !isset( $this->directories[$path] ) )
		{
			return false;
		}

		$items = ['.', '..'];
		$pathPrefix = rtrim( $path, '/' ) . '/';

		// Find direct children (files)
		foreach( $this->files as $filePath => $content )
		{
			if( strpos( $filePath, $pathPrefix ) === 0 )
			{
				$relativePath = substr( $filePath, strlen( $pathPrefix ) );
				// Only direct children (no slashes in relative path)
				if( strpos( $relativePath, '/' ) === false && $relativePath !== '' )
				{
					$items[] = $relativePath;
				}
			}
		}

		// Find direct children (directories)
		foreach( $this->directories as $dirPath => $exists )
		{
			if( $dirPath !== $path && strpos( $dirPath, $pathPrefix ) === 0 )
			{
				$relativePath = substr( $dirPath, strlen( $pathPrefix ) );
				// Only direct children (no slashes in relative path)
				if( strpos( $relativePath, '/' ) === false && $relativePath !== '' )
				{
					$items[] = $relativePath;
				}
			}
		}

		sort( $items );
		return $items;
	}

	/**
	 * @inheritDoc
	 */
	public function glob( string $pattern ): array|false
	{
		// Convert glob pattern to regex
		$regex = $this->globToRegex( $pattern );

		$matches = [];

		// Match files
		foreach( $this->files as $path => $content )
		{
			if( preg_match( $regex, $path ) )
			{
				$matches[] = $path;
			}
		}

		// Match directories
		foreach( $this->directories as $path => $exists )
		{
			if( preg_match( $regex, $path ) )
			{
				$matches[] = $path;
			}
		}

		sort( $matches );
		return $matches;
	}

	/**
	 * Convert glob pattern to regex pattern
	 *
	 * @param string $pattern Glob pattern (e.g., "/path/*.txt")
	 * @return string Regex pattern
	 */
	private function globToRegex( string $pattern ): string
	{
		$escaped = preg_quote( $pattern, '/' );

		// Replace escaped wildcard characters with regex equivalents
		$regex = str_replace( '\*', '[^/]*', $escaped );
		$regex = str_replace( '\?', '[^/]', $regex );

		return '/^' . $regex . '$/';
	}
}

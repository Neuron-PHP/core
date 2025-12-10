<?php

namespace Neuron\Core\System;

/**
 * Real file system implementation.
 *
 * This is the production implementation that wraps PHP's native file system functions.
 * Provides actual file system access for normal operations.
 */
class RealFileSystem implements IFileSystem
{
	/**
	 * @inheritDoc
	 */
	public function fileExists( string $path ): bool
	{
		return file_exists( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function readFile( string $path ): string|false
	{
		return file_get_contents( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function isDir( string $path ): bool
	{
		return is_dir( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function realpath( string $path ): string|false
	{
		return realpath( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function getcwd(): string|false
	{
		return getcwd();
	}

	/**
	 * @inheritDoc
	 */
	public function writeFile( string $path, string $data ): int|false
	{
		return file_put_contents( $path, $data );
	}

	/**
	 * @inheritDoc
	 */
	public function unlink( string $path ): bool
	{
		return @unlink( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function mkdir( string $path, int $permissions = 0755, bool $recursive = true ): bool
	{
		if( $this->isDir( $path ) )
		{
			return true;
		}
		return @mkdir( $path, $permissions, $recursive );
	}

	/**
	 * @inheritDoc
	 */
	public function rmdir( string $path ): bool
	{
		return @rmdir( $path );
	}

	/**
	 * @inheritDoc
	 */
	public function scandir( string $path ): array|false
	{
		return scandir( $path );
	}
}

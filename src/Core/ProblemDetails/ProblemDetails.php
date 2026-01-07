<?php

namespace Neuron\Core\ProblemDetails;

use JsonSerializable;

/**
 * RFC 9457 Problem Details for HTTP APIs implementation.
 *
 * This class represents a problem detail as defined in RFC 9457, providing
 * a standardized format for API error responses. It enables consistent,
 * machine-readable error information across all API endpoints.
 *
 * RFC 9457 defines a standard format for error responses in HTTP APIs,
 * replacing the older RFC 7807. The format provides both required and
 * optional fields to convey error information in a structured way.
 *
 * Required fields:
 * - type: A URI reference that identifies the problem type
 * - title: A short, human-readable summary of the problem type
 * - status: The HTTP status code for this occurrence of the problem
 *
 * Optional fields:
 * - detail: A human-readable explanation specific to this occurrence
 * - instance: A URI reference that identifies the specific occurrence
 *
 * Extension fields:
 * - Any additional fields can be included to provide more context
 *
 * @package Neuron\Core\ProblemDetails
 *
 * @example
 * ```php
 * // Create a validation error problem detail
 * $problem = new ProblemDetails(
 *     type: '/errors/validation',
 *     title: 'Validation Failed',
 *     status: 400,
 *     detail: 'The request contains invalid fields',
 *     extensions: [
 *         'errors' => [
 *             'email' => 'Invalid email format',
 *             'password' => 'Must be at least 8 characters'
 *         ]
 *     ]
 * );
 *
 * // Convert to JSON for API response
 * $json = json_encode($problem);
 * ```
 */
class ProblemDetails implements JsonSerializable
{
	private string $type;
	private string $title;
	private int $status;
	private ?string $detail;
	private ?string $instance;
	private array $extensions;

	/**
	 * Create a new Problem Details instance.
	 *
	 * @param string $type URI reference identifying the problem type
	 * @param string $title Short, human-readable summary of the problem
	 * @param int $status HTTP status code for this occurrence
	 * @param string|null $detail Human-readable explanation specific to this occurrence
	 * @param string|null $instance URI reference identifying the specific occurrence
	 * @param array $extensions Additional fields to include in the response
	 */
	public function __construct(
		string $type,
		string $title,
		int $status,
		?string $detail = null,
		?string $instance = null,
		array $extensions = []
	) {
		$this->type = $type;
		$this->title = $title;
		$this->status = $status;
		$this->detail = $detail;
		$this->instance = $instance;
		$this->extensions = $extensions;

		$this->validate();
	}

	/**
	 * Validate the problem details fields.
	 *
	 * @throws \InvalidArgumentException If required fields are invalid
	 */
	private function validate(): void
	{
		if (empty($this->type)) {
			throw new \InvalidArgumentException('Problem type cannot be empty');
		}

		if (empty($this->title)) {
			throw new \InvalidArgumentException('Problem title cannot be empty');
		}

		if ($this->status < 400 || $this->status > 599) {
			throw new \InvalidArgumentException('Problem status must be a 4xx or 5xx HTTP status code');
		}

		// Validate extension field names (must be valid for JSON and XML)
		foreach (array_keys($this->extensions) as $key) {
			if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)) {
				throw new \InvalidArgumentException("Invalid extension field name: $key");
			}

			// Reserved field names cannot be used as extensions
			if (in_array($key, ['type', 'title', 'status', 'detail', 'instance'])) {
				throw new \InvalidArgumentException("Reserved field name cannot be used as extension: $key");
			}
		}
	}

	/**
	 * Get the problem type URI.
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * Get the problem title.
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * Get the HTTP status code.
	 */
	public function getStatus(): int
	{
		return $this->status;
	}

	/**
	 * Get the problem detail.
	 */
	public function getDetail(): ?string
	{
		return $this->detail;
	}

	/**
	 * Get the problem instance URI.
	 */
	public function getInstance(): ?string
	{
		return $this->instance;
	}

	/**
	 * Get extension fields.
	 */
	public function getExtensions(): array
	{
		return $this->extensions;
	}

	/**
	 * Get a specific extension field value.
	 *
	 * @param string $key The extension field name
	 * @return mixed|null The field value or null if not set
	 */
	public function getExtension(string $key): mixed
	{
		return $this->extensions[$key] ?? null;
	}

	/**
	 * Convert to array representation.
	 *
	 * @return array The problem details as an associative array
	 */
	public function toArray(): array
	{
		$data = [
			'type' => $this->type,
			'title' => $this->title,
			'status' => $this->status,
		];

		if ($this->detail !== null) {
			$data['detail'] = $this->detail;
		}

		if ($this->instance !== null) {
			$data['instance'] = $this->instance;
		}

		// Add extension fields
		foreach ($this->extensions as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array Data to be JSON encoded
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

	/**
	 * Create a ProblemDetails instance from an array.
	 *
	 * @param array $data The problem details data
	 * @return self
	 */
	public static function fromArray(array $data): self
	{
		$type = $data['type'] ?? '';
		$title = $data['title'] ?? '';
		$status = $data['status'] ?? 500;
		$detail = $data['detail'] ?? null;
		$instance = $data['instance'] ?? null;

		// Extract extensions (any fields not in the standard set)
		$standardFields = ['type', 'title', 'status', 'detail', 'instance'];
		$extensions = array_diff_key($data, array_flip($standardFields));

		return new self($type, $title, $status, $detail, $instance, $extensions);
	}
}
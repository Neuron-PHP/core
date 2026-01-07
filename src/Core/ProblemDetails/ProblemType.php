<?php

namespace Neuron\Core\ProblemDetails;

/**
 * Standard problem type URIs for common API errors.
 *
 * This enum defines standard problem type URIs that can be reused across
 * API implementations to ensure consistency. These types follow a URI path
 * pattern that can be resolved to documentation if needed.
 *
 * Problem types are URIs that identify the type of problem. While they don't
 * need to be resolvable, RFC 9457 encourages using URIs that can provide
 * documentation when dereferenced.
 *
 * The URIs use a relative path format (/errors/...) which allows them to be
 * prefixed with a base URI for each application, making them both portable
 * and customizable.
 *
 * @package Neuron\Core\ProblemDetails
 *
 * @example
 * ```php
 * // Use standard problem type for validation errors
 * $problem = new ProblemDetails(
 *     type: ProblemType::VALIDATION_ERROR->value,
 *     title: 'Validation Failed',
 *     status: 400,
 *     detail: 'The email field is required'
 * );
 *
 * // Or use the enum directly with type hinting
 * public function createProblem(ProblemType $type): ProblemDetails {
 *     return match($type) {
 *         ProblemType::VALIDATION_ERROR => new ProblemDetails(...),
 *         ProblemType::NOT_FOUND => new ProblemDetails(...),
 *         // ...
 *     };
 * }
 * ```
 */
enum ProblemType: string
{
	/**
	 * Validation error - Request data failed validation rules.
	 * Typically includes an 'errors' extension with field-specific messages.
	 * HTTP Status: 400 Bad Request
	 */
	case VALIDATION_ERROR = '/errors/validation';

	/**
	 * Resource not found - The requested resource does not exist.
	 * Should include details about what resource was not found.
	 * HTTP Status: 404 Not Found
	 */
	case NOT_FOUND = '/errors/not-found';

	/**
	 * Authentication required - The request requires authentication.
	 * User needs to authenticate before accessing the resource.
	 * HTTP Status: 401 Unauthorized
	 */
	case AUTHENTICATION_REQUIRED = '/errors/authentication';

	/**
	 * Permission denied - Authenticated user lacks required permissions.
	 * User is authenticated but not authorized for this action.
	 * HTTP Status: 403 Forbidden
	 */
	case PERMISSION_DENIED = '/errors/authorization';

	/**
	 * Rate limit exceeded - Too many requests from this client.
	 * May include 'retry_after' extension with seconds to wait.
	 * HTTP Status: 429 Too Many Requests
	 */
	case RATE_LIMIT_EXCEEDED = '/errors/rate-limit';

	/**
	 * Service unavailable - The service is temporarily unavailable.
	 * May occur during maintenance or high load.
	 * HTTP Status: 503 Service Unavailable
	 */
	case SERVICE_UNAVAILABLE = '/errors/service-unavailable';

	/**
	 * Internal server error - An unexpected error occurred.
	 * Should not expose internal details in production.
	 * HTTP Status: 500 Internal Server Error
	 */
	case INTERNAL_ERROR = '/errors/internal';

	/**
	 * Bad request - The request is malformed or invalid.
	 * Generic client error when more specific type doesn't apply.
	 * HTTP Status: 400 Bad Request
	 */
	case BAD_REQUEST = '/errors/bad-request';

	/**
	 * Conflict - The request conflicts with current state.
	 * Often used for duplicate resources or version conflicts.
	 * HTTP Status: 409 Conflict
	 */
	case CONFLICT = '/errors/conflict';

	/**
	 * Method not allowed - HTTP method not supported for this endpoint.
	 * Should include 'allowed_methods' extension.
	 * HTTP Status: 405 Method Not Allowed
	 */
	case METHOD_NOT_ALLOWED = '/errors/method-not-allowed';

	/**
	 * Unsupported media type - Request content type not supported.
	 * Should include 'supported_types' extension.
	 * HTTP Status: 415 Unsupported Media Type
	 */
	case UNSUPPORTED_MEDIA_TYPE = '/errors/unsupported-media-type';

	/**
	 * Request timeout - The request took too long to process.
	 * HTTP Status: 408 Request Timeout
	 */
	case REQUEST_TIMEOUT = '/errors/timeout';

	/**
	 * Payload too large - Request body exceeds size limits.
	 * May include 'max_size' extension.
	 * HTTP Status: 413 Payload Too Large
	 */
	case PAYLOAD_TOO_LARGE = '/errors/payload-too-large';

	/**
	 * Get the recommended HTTP status code for this problem type.
	 *
	 * @return int The HTTP status code
	 */
	public function getRecommendedStatus(): int
	{
		return match($this) {
			self::VALIDATION_ERROR, self::BAD_REQUEST => 400,
			self::AUTHENTICATION_REQUIRED => 401,
			self::PERMISSION_DENIED => 403,
			self::NOT_FOUND => 404,
			self::METHOD_NOT_ALLOWED => 405,
			self::REQUEST_TIMEOUT => 408,
			self::CONFLICT => 409,
			self::PAYLOAD_TOO_LARGE => 413,
			self::UNSUPPORTED_MEDIA_TYPE => 415,
			self::RATE_LIMIT_EXCEEDED => 429,
			self::INTERNAL_ERROR => 500,
			self::SERVICE_UNAVAILABLE => 503,
		};
	}

	/**
	 * Get the default title for this problem type.
	 *
	 * @return string A human-readable title
	 */
	public function getDefaultTitle(): string
	{
		return match($this) {
			self::VALIDATION_ERROR => 'Validation Failed',
			self::NOT_FOUND => 'Resource Not Found',
			self::AUTHENTICATION_REQUIRED => 'Authentication Required',
			self::PERMISSION_DENIED => 'Permission Denied',
			self::RATE_LIMIT_EXCEEDED => 'Rate Limit Exceeded',
			self::SERVICE_UNAVAILABLE => 'Service Unavailable',
			self::INTERNAL_ERROR => 'Internal Server Error',
			self::BAD_REQUEST => 'Bad Request',
			self::CONFLICT => 'Conflict',
			self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
			self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
			self::REQUEST_TIMEOUT => 'Request Timeout',
			self::PAYLOAD_TOO_LARGE => 'Payload Too Large',
		};
	}

	/**
	 * Create a ProblemDetails instance from this type.
	 *
	 * @param string|null $detail Specific details about this occurrence
	 * @param string|null $instance URI identifying this specific occurrence
	 * @param array $extensions Additional fields to include
	 * @return ProblemDetails
	 */
	public function toProblemDetails(
		?string $detail = null,
		?string $instance = null,
		array $extensions = []
	): ProblemDetails {
		return new ProblemDetails(
			type: $this->value,
			title: $this->getDefaultTitle(),
			status: $this->getRecommendedStatus(),
			detail: $detail,
			instance: $instance,
			extensions: $extensions
		);
	}
}
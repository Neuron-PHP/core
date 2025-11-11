<?php

namespace Neuron\Core\Exceptions;

/**
 * Exception thrown when route parameter processing fails or validation errors occur.
 * 
 * This exception is used by the routing system to indicate problems with route
 * parameters, including missing required parameters, invalid parameter formats,
 * type conversion failures, or constraint violations. It helps distinguish
 * routing parameter issues from general application errors.
 * 
 * Common routing parameter issues:
 * - Missing required route parameters (e.g., /users/:id without id)
 * - Invalid parameter formats (e.g., non-numeric id when number expected)  
 * - Parameter constraint violations (e.g., id must be positive integer)
 * - Type conversion failures (e.g., string to integer conversion errors)
 * - Parameter validation rule failures (e.g., length, pattern, range checks)
 * 
 * Typical usage scenarios:
 * - Route parameter extraction and validation
 * - Dynamic route matching with parameter constraints
 * - RESTful API parameter processing
 * - URL parameter type checking and conversion
 * 
 * @package Neuron\Core\Exceptions
 * 
 * @example
 * ```php
 * // Thrown when route parameter is invalid
 * if (!is_numeric($userId)) {
 *     throw new RouteParam("User ID must be numeric, got: {$userId}");
 * }
 * 
 * // Thrown when required parameter missing
 * if (empty($routeParams['id'])) {
 *     throw new RouteParam("Required route parameter 'id' is missing");
 * }
 * 
 * // Caught in route processing
 * try {
 *     $controller->show($routeParams['id']);
 * } catch (RouteParam $e) {
 *     // Handle invalid route parameter
 *     return $this->notFound('Invalid resource identifier');
 * }
 * ```
 */
class RouteParam extends Base
{
	public function __construct( string $message )
	{
		parent::__construct( $message );
	}
}

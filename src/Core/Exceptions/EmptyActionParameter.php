<?php

namespace Neuron\Core\Exceptions;

/**
 * Exception thrown when a required action parameter is empty or missing.
 * 
 * This exception is typically thrown by command processors, routers, or other
 * components that require a non-empty action parameter to determine which
 * operation to execute. It indicates that the caller failed to provide a
 * required action identifier.
 * 
 * Common scenarios:
 * - Command pattern invokers receiving empty action names
 * - Route processors missing required action parameters
 * - API endpoints called without required action identifiers
 * - Form processors missing action field values
 * 
 * @package Neuron\Core\Exceptions
 * 
 * @example
 * ```php
 * // Thrown when action parameter is empty
 * if (empty($action)) {
 *     throw new EmptyActionParameter('Action parameter cannot be empty');
 * }
 * 
 * // Caught in command processing
 * try {
 *     $invoker->process($action, $params);
 * } catch (EmptyActionParameter $e) {
 *     // Handle missing action parameter
 *     return $this->errorResponse('Action required');
 * }
 * ```
 */
class EmptyActionParameter extends Base
{
}

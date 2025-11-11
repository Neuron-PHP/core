<?php

namespace Neuron\Core\Exceptions;

/**
 * Exception thrown when data validation fails with detailed error information.
 * 
 * This exception is used throughout the framework to indicate validation failures
 * for user input, configuration data, API parameters, or any other data that
 * must meet specific criteria. It extends the base exception to include detailed
 * error information that can be used for user feedback or debugging.
 * 
 * Key features:
 * - Stores array of specific validation errors
 * - Provides structured error access via public property
 * - Supports multiple validation failures in a single exception
 * - Integrates with the framework's validation system
 * - Enables detailed error reporting and user feedback
 * 
 * Common validation scenarios:
 * - Form input validation (required fields, format validation)
 * - API parameter validation (data types, ranges, patterns)
 * - Configuration file validation (required settings, valid values)
 * - DTO validation (object property constraints)
 * - Business rule validation (complex domain constraints)
 * 
 * @package Neuron\Core\Exceptions
 * 
 * @example
 * ```php
 * // Throw validation exception with multiple errors
 * $errors = [
 *     'email' => 'Invalid email format',
 *     'password' => 'Password must be at least 8 characters',
 *     'age' => 'Age must be between 13 and 120'
 * ];
 * throw new Validation('User Registration', $errors);
 * 
 * // Catch and handle validation errors
 * try {
 *     $validator->validate($userData);
 * } catch (Validation $e) {
 *     // Access specific validation errors
 *     foreach ($e->errors as $field => $error) {
 *         $form->addError($field, $error);
 *     }
 *     return $this->showFormWithErrors($form);
 * }
 * ```
 */
class Validation extends Base
{
	public array $errors
	{
		get
		{
			return $this->errors;
		}
	}

	public function __construct( string $name, array $errors )
	{
		parent::__construct( "Validation failed for $name" );
		$this->errors = $errors;
	}
}

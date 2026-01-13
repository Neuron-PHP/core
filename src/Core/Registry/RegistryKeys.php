<?php
namespace Neuron\Core\Registry;

/**
 * Centralized registry key constants for the Neuron framework.
 *
 * This class provides standardized string constants for all registry keys used
 * throughout the framework. Keys follow a dot notation convention with PascalCase
 * segments for clear namespace separation and consistency.
 *
 * @package Neuron\Core\Registry
 * @since 1.0.0
 *
 * @example
 * ```php
 * use Neuron\Core\Registry\RegistryKeys;
 * use Neuron\Patterns\Registry;
 *
 * // Store application instance
 * Registry::getInstance()->set(RegistryKeys::APP, $app);
 *
 * // Retrieve settings
 * $settings = Registry::getInstance()->get(RegistryKeys::SETTINGS);
 *
 * // Check if user is authenticated
 * $user = Registry::getInstance()->get(RegistryKeys::AUTH_USER);
 * ```
 */
class RegistryKeys
{
	// ================================================================================
	// Core Application Keys
	// ================================================================================

	/**
	 * The main application instance.
	 * @var string
	 */
	public const APP = 'App';

	/**
	 * Application settings manager instance.
	 * @var string
	 */
	public const SETTINGS = 'Settings';

	/**
	 * Application version string.
	 * @var string
	 */
	public const APP_VERSION = 'App.Version';

	/**
	 * Application name.
	 * @var string
	 */
	public const APP_NAME = 'App.Name';

	/**
	 * RSS feed URL for the application.
	 * @var string
	 */
	public const APP_RSS_URL = 'App.RssUrl';

	// ================================================================================
	// Path Configuration Keys
	// ================================================================================

	/**
	 * Base directory path of the application.
	 * @var string
	 */
	public const BASE_PATH = 'Base.Path';

	/**
	 * Base URL for routing and URL generation.
	 * @var string
	 */
	public const BASE_URL = 'Base.Url';

	/**
	 * Path to view templates.
	 * @var string
	 */
	public const VIEWS_PATH = 'Views.Path';

	/**
	 * Path to request definitions.
	 * @var string
	 */
	public const REQUESTS_PATH = 'Requests.Path';

	// ================================================================================
	// Authentication & Security Keys
	// ================================================================================

	/**
	 * Current authenticated user object.
	 * @var string
	 */
	public const AUTH_USER = 'Auth.User';

	/**
	 * Current authenticated user ID.
	 * @var string
	 */
	public const AUTH_USER_ID = 'Auth.UserId';

	/**
	 * Current authenticated user role.
	 * @var string
	 */
	public const AUTH_USER_ROLE = 'Auth.UserRole';

	/**
	 * CSRF token for form submissions.
	 * @var string
	 */
	public const AUTH_CSRF_TOKEN = 'Auth.CsrfToken';

	/**
	 * Authentication service instance.
	 * @var string
	 */
	public const AUTH_SERVICE = 'Auth.Service';

	/**
	 * CSRF token service instance.
	 * @var string
	 */
	public const CSRF_TOKEN = 'Csrf.Token';

	/**
	 * User ID for rate limiting purposes.
	 * @var string
	 */
	public const USER_ID = 'User.Id';

	// ================================================================================
	// Service & Component Keys
	// ================================================================================

	/**
	 * Dependency injection container instance.
	 * @var string
	 */
	public const CONTAINER = 'Container';

	/**
	 * Email verification service.
	 * @var string
	 */
	public const EMAIL_VERIFIER = 'Email.Verifier';

	/**
	 * User registration service.
	 * @var string
	 */
	public const REGISTRATION_SERVICE = 'Registration.Service';

	/**
	 * Password reset service.
	 * @var string
	 */
	public const PASSWORD_RESETTER = 'Password.Resetter';

	/**
	 * Event emitter instance.
	 * @var string
	 */
	public const EVENT_EMITTER = 'Event.Emitter';

	/**
	 * DTO factory service.
	 * @var string
	 */
	public const DTO_FACTORY_SERVICE = 'Dto.FactoryService';

	// ================================================================================
	// View & Caching Keys
	// ================================================================================

	/**
	 * View cache instance.
	 * @var string
	 */
	public const VIEW_CACHE = 'View.Cache';

	/**
	 * View data provider instance.
	 * @var string
	 */
	public const VIEW_DATA_PROVIDER = 'View.DataProvider';

	// ================================================================================
	// Routing & Controller Keys
	// ================================================================================

	/**
	 * Controller paths configuration for route scanning.
	 * @var string
	 */
	public const ROUTING_CONTROLLER_PATHS = 'Routing.ControllerPaths';

	// ================================================================================
	// Exception Handling Keys
	// ================================================================================

	/**
	 * List of exception classes that should pass through error handlers.
	 * @var string
	 */
	public const EXCEPTIONS_PASSTHROUGH = 'Exceptions.Passthrough';

	// ================================================================================
	// CLI Keys
	// ================================================================================

	/**
	 * CLI application instance.
	 * @var string
	 */
	public const CLI_APPLICATION = 'Cli.Application';

	/**
	 * CLI exit code.
	 * @var string
	 */
	public const CLI_EXIT_CODE = 'Cli.ExitCode';

	/**
	 * CLI output handler.
	 * @var string
	 */
	public const CLI_OUTPUT = 'Cli.Output';

	// ================================================================================
	// Maintenance Mode Keys
	// ================================================================================

	/**
	 * Maintenance mode manager instance.
	 * @var string
	 */
	public const MAINTENANCE_MANAGER = 'Maintenance.Manager';

	/**
	 * Maintenance mode configuration.
	 * @var string
	 */
	public const MAINTENANCE_CONFIG = 'Maintenance.Config';

	// ================================================================================
	// Deprecated Keys (for backward compatibility)
	// ================================================================================

	/**
	 * @deprecated Use BASE_PATH instead
	 * @var string
	 */
	public const BASE_PATH_LEGACY = 'BasePath';

	/**
	 * @deprecated Use VIEW_CACHE instead
	 * @var string
	 */
	public const VIEW_CACHE_LEGACY = 'ViewCache';

	/**
	 * @deprecated Use VIEW_DATA_PROVIDER instead
	 * @var string
	 */
	public const VIEW_DATA_PROVIDER_LEGACY = 'ViewDataProvider';

	/**
	 * @deprecated Use EXCEPTIONS_PASSTHROUGH instead
	 * @var string
	 */
	public const PASSTHROUGH_EXCEPTIONS_LEGACY = 'PassthroughExceptions';

	/**
	 * @deprecated Use AUTH_SERVICE instead
	 * @var string
	 */
	public const AUTHENTICATION_LEGACY = 'Authentication';

	/**
	 * @deprecated Use CSRF_TOKEN instead
	 * @var string
	 */
	public const CSRF_TOKEN_LEGACY = 'CsrfToken';

	/**
	 * @deprecated Use EMAIL_VERIFIER instead
	 * @var string
	 */
	public const EMAIL_VERIFIER_LEGACY = 'EmailVerifier';

	/**
	 * @deprecated Use REGISTRATION_SERVICE instead
	 * @var string
	 */
	public const REGISTRATION_SERVICE_LEGACY = 'RegistrationService';

	/**
	 * @deprecated Use PASSWORD_RESETTER instead
	 * @var string
	 */
	public const PASSWORD_RESETTER_LEGACY = 'PasswordResetter';

	/**
	 * @deprecated Use EVENT_EMITTER instead
	 * @var string
	 */
	public const EVENT_EMITTER_LEGACY = 'EventEmitter';

	/**
	 * @deprecated Use DTO_FACTORY_SERVICE instead
	 * @var string
	 */
	public const DTO_FACTORY_SERVICE_LEGACY = 'DtoFactoryService';

	/**
	 * @deprecated Use CLI_APPLICATION instead
	 * @var string
	 */
	public const CLI_APPLICATION_LEGACY = 'cli.application';

	/**
	 * @deprecated Use CLI_EXIT_CODE instead
	 * @var string
	 */
	public const CLI_EXIT_CODE_LEGACY = 'cli.exit_code';

	/**
	 * @deprecated Use CLI_OUTPUT instead
	 * @var string
	 */
	public const CLI_OUTPUT_LEGACY = 'cli.output';

	/**
	 * @deprecated Use MAINTENANCE_MANAGER instead
	 * @var string
	 */
	public const MAINTENANCE_MANAGER_LEGACY = 'maintenance.manager';

	/**
	 * @deprecated Use MAINTENANCE_CONFIG instead
	 * @var string
	 */
	public const MAINTENANCE_CONFIG_LEGACY = 'maintenance.config';

	/**
	 * @deprecated Use APP_VERSION instead
	 * @var string
	 */
	public const VERSION_LEGACY = 'version';

	/**
	 * @deprecated Use APP_NAME instead
	 * @var string
	 */
	public const NAME_LEGACY = 'name';

	/**
	 * @deprecated Use APP_RSS_URL instead
	 * @var string
	 */
	public const RSS_URL_LEGACY = 'rss_url';
}
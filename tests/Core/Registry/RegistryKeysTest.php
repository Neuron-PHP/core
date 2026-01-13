<?php
namespace Core\Registry;

use Neuron\Core\Registry\RegistryKeys;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test suite for RegistryKeys class.
 *
 * Validates that all registry keys are properly defined, unique,
 * and follow the established naming conventions.
 */
class RegistryKeysTest extends TestCase
{
	/**
	 * Test that all constants are accessible and return string values.
	 */
	public function testAllConstantsAreDefined(): void
	{
		$reflection = new ReflectionClass(RegistryKeys::class);
		$constants = $reflection->getConstants();

		$this->assertNotEmpty($constants, 'RegistryKeys should have constants defined');

		foreach ($constants as $name => $value) {
			$this->assertIsString($value, "Constant $name should be a string");
			$this->assertNotEmpty($value, "Constant $name should not be empty");
		}
	}

	/**
	 * Test that there are no duplicate values among the constants.
	 * This is critical to prevent registry key collisions.
	 */
	public function testNoDuplicateValues(): void
	{
		$reflection = new ReflectionClass(RegistryKeys::class);
		$constants = $reflection->getConstants();

		$values = [];
		$duplicates = [];

		foreach ($constants as $name => $value) {
			if (isset($values[$value])) {
				$duplicates[] = "Value '$value' is used by both $name and {$values[$value]}";
			}
			$values[$value] = $name;
		}

		$this->assertEmpty(
			$duplicates,
			"Duplicate values found:\n" . implode("\n", $duplicates)
		);
	}

	/**
	 * Test that legacy constants have different values than their replacements.
	 * This ensures backward compatibility works correctly.
	 */
	public function testLegacyConstantsHaveDifferentValues(): void
	{
		$legacyMappings = [
			// Legacy constant => New constant
			'BASE_PATH_LEGACY' => 'BASE_PATH',
			'VIEW_CACHE_LEGACY' => 'VIEW_CACHE',
			'VIEW_DATA_PROVIDER_LEGACY' => 'VIEW_DATA_PROVIDER',
			'PASSTHROUGH_EXCEPTIONS_LEGACY' => 'EXCEPTIONS_PASSTHROUGH',
			'AUTHENTICATION_LEGACY' => 'AUTH_SERVICE',
			'CSRF_TOKEN_LEGACY' => 'CSRF_TOKEN',
			'EMAIL_VERIFIER_LEGACY' => 'EMAIL_VERIFIER',
			'REGISTRATION_SERVICE_LEGACY' => 'REGISTRATION_SERVICE',
			'PASSWORD_RESETTER_LEGACY' => 'PASSWORD_RESETTER',
			'EVENT_EMITTER_LEGACY' => 'EVENT_EMITTER',
			'DTO_FACTORY_SERVICE_LEGACY' => 'DTO_FACTORY_SERVICE',
			'CLI_APPLICATION_LEGACY' => 'CLI_APPLICATION',
			'CLI_EXIT_CODE_LEGACY' => 'CLI_EXIT_CODE',
			'CLI_OUTPUT_LEGACY' => 'CLI_OUTPUT',
			'MAINTENANCE_MANAGER_LEGACY' => 'MAINTENANCE_MANAGER',
			'MAINTENANCE_CONFIG_LEGACY' => 'MAINTENANCE_CONFIG',
			'VERSION_LEGACY' => 'APP_VERSION',
			'NAME_LEGACY' => 'APP_NAME',
			'RSS_URL_LEGACY' => 'APP_RSS_URL',
		];

		$reflection = new ReflectionClass(RegistryKeys::class);

		foreach ($legacyMappings as $legacy => $new) {
			if ($reflection->hasConstant($legacy) && $reflection->hasConstant($new)) {
				$legacyValue = $reflection->getConstant($legacy);
				$newValue = $reflection->getConstant($new);

				$this->assertNotEquals(
					$legacyValue,
					$newValue,
					"Legacy constant $legacy should have a different value than $new for backward compatibility"
				);
			}
		}
	}

	/**
	 * Test that standardized keys follow the naming convention.
	 * Non-legacy keys should use dot notation with PascalCase segments.
	 */
	public function testNamingConvention(): void
	{
		$reflection = new ReflectionClass(RegistryKeys::class);
		$constants = $reflection->getConstants();

		$violations = [];

		foreach ($constants as $name => $value) {
			// Skip legacy constants and single-word constants
			if (str_contains($name, '_LEGACY') || !str_contains($value, '.')) {
				continue;
			}

			// Check that segments use PascalCase
			$segments = explode('.', $value);
			foreach ($segments as $segment) {
				if (!$this->isPascalCase($segment)) {
					$violations[] = "Constant $name with value '$value' has segment '$segment' that is not PascalCase";
				}
			}
		}

		$this->assertEmpty(
			$violations,
			"Naming convention violations found:\n" . implode("\n", $violations)
		);
	}

	/**
	 * Test that expected legacy mappings are maintained for backward compatibility.
	 */
	public function testExpectedLegacyMappings(): void
	{
		$expectedMappings = [
			'BasePath' => RegistryKeys::BASE_PATH_LEGACY,
			'ViewCache' => RegistryKeys::VIEW_CACHE_LEGACY,
			'ViewDataProvider' => RegistryKeys::VIEW_DATA_PROVIDER_LEGACY,
			'PassthroughExceptions' => RegistryKeys::PASSTHROUGH_EXCEPTIONS_LEGACY,
			'Authentication' => RegistryKeys::AUTHENTICATION_LEGACY,
			'CsrfToken' => RegistryKeys::CSRF_TOKEN_LEGACY,
			'EmailVerifier' => RegistryKeys::EMAIL_VERIFIER_LEGACY,
			'RegistrationService' => RegistryKeys::REGISTRATION_SERVICE_LEGACY,
			'PasswordResetter' => RegistryKeys::PASSWORD_RESETTER_LEGACY,
			'EventEmitter' => RegistryKeys::EVENT_EMITTER_LEGACY,
			'DtoFactoryService' => RegistryKeys::DTO_FACTORY_SERVICE_LEGACY,
			'cli.application' => RegistryKeys::CLI_APPLICATION_LEGACY,
			'cli.exit_code' => RegistryKeys::CLI_EXIT_CODE_LEGACY,
			'cli.output' => RegistryKeys::CLI_OUTPUT_LEGACY,
			'maintenance.manager' => RegistryKeys::MAINTENANCE_MANAGER_LEGACY,
			'maintenance.config' => RegistryKeys::MAINTENANCE_CONFIG_LEGACY,
			'version' => RegistryKeys::VERSION_LEGACY,
			'name' => RegistryKeys::NAME_LEGACY,
			'rss_url' => RegistryKeys::RSS_URL_LEGACY,
		];

		foreach ($expectedMappings as $expectedValue => $constantValue) {
			$this->assertEquals(
				$expectedValue,
				$constantValue,
				"Legacy constant should map to expected value '$expectedValue'"
			);
		}
	}

	/**
	 * Test that key groupings are logical and consistent.
	 */
	public function testKeyGroupings(): void
	{
		$authKeys = [
			RegistryKeys::AUTH_USER,
			RegistryKeys::AUTH_USER_ID,
			RegistryKeys::AUTH_USER_ROLE,
			RegistryKeys::AUTH_CSRF_TOKEN,
			RegistryKeys::AUTH_SERVICE,
		];

		foreach ($authKeys as $key) {
			$this->assertStringStartsWith('Auth.', $key, "Auth-related key should start with 'Auth.'");
		}

		$cliKeys = [
			RegistryKeys::CLI_APPLICATION,
			RegistryKeys::CLI_EXIT_CODE,
			RegistryKeys::CLI_OUTPUT,
		];

		foreach ($cliKeys as $key) {
			$this->assertStringStartsWith('Cli.', $key, "CLI-related key should start with 'Cli.'");
		}

		$maintenanceKeys = [
			RegistryKeys::MAINTENANCE_MANAGER,
			RegistryKeys::MAINTENANCE_CONFIG,
		];

		foreach ($maintenanceKeys as $key) {
			$this->assertStringStartsWith('Maintenance.', $key, "Maintenance-related key should start with 'Maintenance.'");
		}
	}

	/**
	 * Test that commonly used keys are present and correctly defined.
	 */
	public function testCommonlyUsedKeys(): void
	{
		// These are the most commonly used keys based on the codebase analysis
		$commonKeys = [
			'Settings' => RegistryKeys::SETTINGS,
			'App' => RegistryKeys::APP,
			'Base.Path' => RegistryKeys::BASE_PATH,
			'Auth.User' => RegistryKeys::AUTH_USER,
			'Container' => RegistryKeys::CONTAINER,
		];

		foreach ($commonKeys as $expectedValue => $constant) {
			$this->assertEquals(
				$expectedValue,
				$constant,
				"Common key constant should have expected value '$expectedValue'"
			);
		}
	}

	/**
	 * Helper method to check if a string is in PascalCase.
	 *
	 * @param string $str The string to check
	 * @return bool True if the string is in PascalCase
	 */
	private function isPascalCase(string $str): bool
	{
		// PascalCase: starts with uppercase, followed by letters (upper or lower)
		// Single uppercase letter is also valid (e.g., "App")
		return preg_match('/^[A-Z][a-zA-Z]*$/', $str) === 1;
	}

	/**
	 * Test that there are no naming conflicts between new and legacy constants.
	 * This ensures we can safely use both during the migration period.
	 */
	public function testNoConflictsBetweenNewAndLegacyConstants(): void
	{
		$reflection = new ReflectionClass(RegistryKeys::class);
		$constants = $reflection->getConstants();

		$standardKeys = [];
		$legacyKeys = [];

		foreach ($constants as $name => $value) {
			if (str_contains($name, '_LEGACY')) {
				$legacyKeys[$value] = $name;
			} else {
				$standardKeys[$value] = $name;
			}
		}

		// Check that no standard key value matches any legacy key value
		// (They should be able to coexist during migration)
		$conflicts = [];
		foreach ($standardKeys as $value => $name) {
			if (isset($legacyKeys[$value])) {
				$conflicts[] = "Value '$value' is used by both standard constant $name and legacy constant {$legacyKeys[$value]}";
			}
		}

		$this->assertEmpty(
			$conflicts,
			"Conflicts found between standard and legacy constants:\n" . implode("\n", $conflicts)
		);
	}
}
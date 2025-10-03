<?php

namespace Neuron\Core\Exceptions;

/**
 * Exception thrown when a requested command cannot be found or instantiated.
 * 
 * This exception extends the generic NotFound exception and is specifically used
 * in command pattern implementations when a command factory cannot locate or
 * create a requested command class. It indicates that the specified command
 * identifier does not correspond to any registered or available command.
 * 
 * Common causes:
 * - Command class does not exist
 * - Command not registered with the factory
 * - Incorrect command name or identifier
 * - Command class cannot be instantiated (missing dependencies)
 * - Autoloading issues preventing command class loading
 * 
 * This exception is typically thrown by:
 * - Command factories during command instantiation
 * - Command registries during command lookup
 * - Command invokers when resolving command identifiers
 * 
 * @package Neuron\Core\Exceptions
 * 
 * @see NotFound Base exception for not found errors
 * 
 * @example
 * ```php
 * // Thrown by command factory
 * $commandClass = "App\Commands\\{$commandName}Command";
 * if (!class_exists($commandClass)) {
 *     throw new CommandNotFound("Command '{$commandName}' not found");
 * }
 * 
 * // Caught in command processing
 * try {
 *     $command = $factory->get('nonexistent-command');
 * } catch (CommandNotFound $e) {
 *     // Handle unknown command
 *     $this->logger->error('Unknown command requested', ['command' => $commandName]);
 *     return $this->showAvailableCommands();
 * }
 * ```
 */
class CommandNotFound extends NotFound
{
}

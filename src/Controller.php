<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Spiral\Core\Exceptions\Container\ArgumentException;
use Spiral\Core\Exceptions\ControllerException;

/**
 * Basic application controller class. Implements method injections and simplified access to
 * container bindings.
 */
abstract class Controller implements ControllerInterface
{
    /**
     * Controller action prefixes and postfixes.
     */
    const ACTION_PREFIX  = '';
    const ACTION_POSTFIX = 'Action';

    /**
     * Default action to run.
     *
     * @var string
     */
    protected $defaultAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function callAction(
        ContainerInterface $container,
        string $action = null,
        array $parameters = []
    ) {
        $method = static::ACTION_PREFIX . ($action ?? $this->defaultAction) . static::ACTION_POSTFIX;

        if (!method_exists($this, $method)) {
            throw new ControllerException(
                "No such action '{$action}'", ControllerException::BAD_ACTION
            );
        }

        $reflection = new \ReflectionMethod($this, $method);

        if (!$this->isExecutable($reflection)) {
            //Need different exception code here
            throw new ControllerException(
                "Action '{$action}' can not be executed",
                ControllerException::BAD_ACTION
            );
        }

        //Needed to be called via reflection
        $reflection->setAccessible(true);

        //Executing our action
        return $this->executeAction(
            $reflection,
            $this->resolveArguments($container, $reflection, $parameters),
            $parameters
        );
    }

    /**
     * @param \ReflectionMethod $method
     * @param array             $arguments
     * @param array             $parameters
     *
     * @return mixed
     */
    protected function executeAction(\ReflectionMethod $method, array $arguments, array $parameters)
    {
        return $method->invokeArgs($this, $arguments);
    }

    /**
     * Check if method is callable.
     *
     * @param \ReflectionMethod $method
     *
     * @return bool
     */
    protected function isExecutable(\ReflectionMethod $method)
    {
        if ($method->isStatic() || !$method->isUserDefined()) {
            return false;
        }

        //Place to implement custom logic
        return true;
    }

    /**
     * Resolve controller method arguments.
     *
     * @param ContainerInterface $container
     * @param \ReflectionMethod  $method
     * @param array              $parameters
     *
     * @return array
     */
    private function resolveArguments(
        ContainerInterface $container,
        \ReflectionMethod $method,
        array $parameters
    ) {
        try {
            //Getting set of arguments should be sent to requested method
            return $container->get(ResolverInterface::class)->resolveArguments(
                $method,
                $parameters
            );
        } catch (ArgumentException $e) {
            throw new ControllerException(
                "Missing/invalid parameter '{$e->getParameter()->name}'",
                ControllerException::BAD_ARGUMENT
            );
        } catch (ContainerExceptionInterface $e) {
            throw new ControllerException($e->getMessage(), ControllerException::ERROR, $e);
        }
    }
}

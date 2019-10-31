<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Spiral\Core\Exception\Container\ArgumentException;
use Spiral\Core\Exception\ControllerException;

/**
 * Provides ability to call controllers in IoC scope.
 *
 * Make sure to bind ScopeInterface in your container.
 */
abstract class AbstractCore implements CoreInterface
{
    /** @var ContainerInterface @internal */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function callAction(string $controller, ?string $action, array $parameters = [], array $scope = [])
    {
        if (!class_exists($controller)) {
            throw new ControllerException(
                "No such controller '{$controller}' found",
                ControllerException::NOT_FOUND
            );
        }

        return $this->container->get(ScopeInterface::class)->runScope(
            $scope,
            function () use ($controller, $action, $parameters) {
                $instance = $this->container->get($controller);
                return ContainerScope::runScope(
                    $this->container,
                    function () use ($instance, $action, $parameters) {
                        if (!$instance instanceof ControllerInterface) {
                            return $this->callMethod($instance, $action, $parameters);
                        }

                        // delegate the resolution to the controller
                        return $instance->callAction($this->container, $action, $parameters);
                    }
                );
            }
        );
    }

    /**
     * @param object $instance
     * @param string $method
     * @param array  $parameters
     * @return mixed
     *
     * @throws ControllerException
     */
    protected function callMethod(object $instance, string $method, array $parameters)
    {
        try {
            $method = new \ReflectionMethod($instance, $method);
        } catch (\ReflectionException $e) {
            throw new ControllerException(
                "Invalid action '{$method}'",
                ControllerException::BAD_ACTION,
                $e
            );
        }

        if ($method->isStatic() || !$method->isPublic()) {
            throw new ControllerException(
                "No such method '{$method}'",
                ControllerException::BAD_ACTION
            );
        }

        try {
            //Getting set of arguments should be sent to requested method
            $args = $this->container->get(ResolverInterface::class)->resolveArguments(
                $method,
                $parameters
            );
        } catch (ArgumentException $e) {
            throw new ControllerException(
                "Missing/invalid parameter '{$e->getParameter()->name}'",
                ControllerException::BAD_ARGUMENT
            );
        } catch (ContainerExceptionInterface $e) {
            throw new ControllerException(
                $e->getMessage(),
                ControllerException::ERROR,
                $e
            );
        }

        return $method->invokeArgs($instance, $args);
    }
}

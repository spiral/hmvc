<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core;

use Psr\Container\ContainerInterface;
use Spiral\Core\Exception\ControllerException;

/**
 * Provides ability to call controllers in IoC scope.
 *
 * Make sure to bind ScopeInterface in your container.
 */
abstract class AbstractCore implements CoreInterface
{
    /**
     * @invisible
     * @var ContainerInterface
     */
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
    public function callAction(
        string $controller,
        string $action = null,
        array $parameters = [],
        array $scope = []
    ) {
        if (!class_exists($controller)) {
            throw new ControllerException(
                "No such controller '{$controller}' found",
                ControllerException::NOT_FOUND
            );
        }

        return $this->container->get(ScopeInterface::class)->runScope($scope, function () use (
            $controller,
            $action,
            $parameters
        ) {
            $instance = $this->container->get($controller);
            if (!$instance instanceof ControllerInterface) {
                throw new ControllerException(
                    "No such controller '{$controller}' found",
                    ControllerException::NOT_FOUND
                );
            }

            return ContainerScope::runScope($this->container, function () use (
                $instance,
                $action,
                $parameters
            ) {
                // Lock Controller exception into global container scope
                return $instance->callAction($this->container, $action, $parameters);
            });
        });
    }
}
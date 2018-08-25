<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core;

use Psr\Container\ContainerInterface;
use Spiral\Core\Exceptions\ControllerException;

/**
 * Class being treated as controller.
 */
interface ControllerInterface
{
    /**
     * Execute specific controller action (method).
     *
     * @param ContainerInterface $container  Container scope.
     * @param string             $action     Action name, without postfixes and prefixes.
     * @param array              $parameters Method parameters.
     *
     * @return mixed
     *
     * @throws ControllerException
     *
     * @throws \Throwable
     */
    public function callAction(
        ContainerInterface $container,
        string $action = null,
        array $parameters = []
    );
}
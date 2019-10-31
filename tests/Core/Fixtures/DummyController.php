<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Core\Tests\Fixtures;

use Psr\Container\ContainerInterface;
use Spiral\Core\ContainerScope;

class DummyController
{
    public function index(string $name = 'Dave')
    {
        return "Hello, {$name}.";
    }

    public function required(int $id)
    {
        return $id;
    }

    public function scope(int $id, ContainerInterface $controller)
    {
        return $controller;
    }

    public function globalScope(int $id)
    {
        return ContainerScope::getContainer();
    }

    public static function inner(): void
    {
    }
}

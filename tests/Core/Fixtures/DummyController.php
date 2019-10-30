<?php

declare(strict_types=1);

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core\Tests\Fixtures;

use Psr\Container\ContainerInterface;
use Spiral\Core\ContainerScope;
use Spiral\Core\Controller;

class DummyController extends Controller
{
    public function indexAction(string $name = 'Dave')
    {
        return "Hello, {$name}.";
    }

    public function requiredAction(int $id)
    {
        return $id;
    }

    public function scopeAction(int $id, ContainerInterface $controller)
    {
        return $controller;
    }

    public function globalScopeAction(int $id)
    {
        return ContainerScope::getContainer();
    }

    public static function innerAction(): void
    {
    }
}

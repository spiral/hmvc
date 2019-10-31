<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Core\Tests;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;

class DemoInterceptor implements CoreInterceptorInterface
{
    public function callAction(
        string $controller,
        string $action,
        array $parameters,
        array $scope,
        CoreInterface $core
    ) {
        $result = $core->callAction($controller, $action, $parameters, $scope);

        return '?' . $result . '!';
    }
}

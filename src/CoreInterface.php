<?php declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core;

use Spiral\Core\Exception\ControllerException;

/**
 * General application enterpoint class.
 */
interface CoreInterface
{
    /**
     * Request specific action result from Core. Due in 99% every action will need parent
     * controller, we can request it too.
     *
     * @param string $controller Controller class.
     * @param string $action     Controller action, empty by default (controller will use default
     *                           action).
     * @param array  $parameters Action parameters (if any).
     * @param array  $scope      Scope in a form if [alias=>binding] to be set by container before
     *                           executing given action.
     *
     * @return mixed
     *
     * @throws ControllerException
     * @throws \Exception
     */
    public function callAction(
        string $controller,
        string $action = null,
        array $parameters = [],
        array $scope = []
    );
}
<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core\Tests\Fixtures;

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

    static function innerAction()
    {
    }
}
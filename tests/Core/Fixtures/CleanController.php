<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Core\Tests\Fixtures;

class CleanController
{
    public function test(string $id)
    {
        return $id;
    }

    protected function another(): void
    {
    }
}

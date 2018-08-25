<?php
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\Core\Tests\Fixtures;

use Spiral\Core\Bootloaders\Bootloader;

class SampleBoot extends Bootloader
{
    const BINDINGS = [
        'abc' => self::class
    ];
}
<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Core\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Core\Tests\Fixtures\CleanController;
use Spiral\Core\Tests\Fixtures\DummyController;
use Spiral\Core\Tests\Fixtures\SampleCore;

class CoreTest extends TestCase
{
    public function testCallAction(): void
    {
        $core = new SampleCore(new Container());
        $this->assertSame('Hello, Antony.', $core->callAction(
            DummyController::class,
            'index',
            ['name' => 'Antony']
        ));
    }

    public function testCallActionDefaultParameter(): void
    {
        $core = new SampleCore(new Container());
        $this->assertSame('Hello, Dave.', $core->callAction(
            DummyController::class,
            'index'
        ));
    }

    public function testCallActionDefaultAction(): void
    {
        $core = new SampleCore(new Container());
        $this->assertSame('Hello, Dave.', $core->callAction(
            DummyController::class,
            'index'
        ));
    }

    public function testCallActionDefaultActionWithParameter(): void
    {
        $core = new SampleCore(new Container());
        $this->assertSame('Hello, Antony.', $core->callAction(
            DummyController::class,
            'index',
            ['name' => 'Antony']
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCallActionMissingParameter(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCallActionInvalidParameter(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required', ['id' => null]);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCallWrongController(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(BadController::class, 'index', ['name' => 'Antony']);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCallBadAction(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'missing', [
            'name' => 'Antony'
        ]);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testStaticAction(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'inner');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testInheritedAction(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'execute');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testInheritedActionCall(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'call');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCallNotController(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(SampleCore::class, 'index', [
            'name' => 'Antony'
        ]);
    }

    public function testCleanController(): void
    {
        $core = new SampleCore(new Container());

        $this->assertSame('900', $core->callAction(
            CleanController::class,
            'test',
            ['id' => 900]
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCleanControllerError(): void
    {
        $core = new SampleCore(new Container());

        $this->assertSame('900', $core->callAction(
            CleanController::class,
            'test',
            ['id' => null]
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCleanControllerError2(): void
    {
        $core = new SampleCore(new Container());

        $this->assertSame('900', $core->callAction(
            CleanController::class,
            'test',
            []
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCleanControllerError3(): void
    {
        $core = new SampleCore(new Container());

        $this->assertSame('900', $core->callAction(
            CleanController::class,
            'invalid',
            []
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     */
    public function testCleanControllerError4(): void
    {
        $core = new SampleCore(new Container());

        $this->assertSame('900', $core->callAction(
            CleanController::class,
            'another',
            []
        ));
    }
}

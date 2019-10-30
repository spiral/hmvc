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

class ControllersTest extends TestCase
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
            null
        ));
    }

    public function testCallActionDefaultActionWithParameter(): void
    {
        $core = new SampleCore(new Container());
        $this->assertSame('Hello, Antony.', $core->callAction(
            DummyController::class,
            null,
            ['name' => 'Antony']
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Missing/invalid parameter 'id'
     */
    public function testCallActionMissingParameter(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Missing/invalid parameter 'id'
     */
    public function testCallActionInvalidParameter(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required', ['id' => null]);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage No such controller 'Spiral\Core\Tests\BadController' found
     */
    public function testCallWrongController(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(BadController::class, null, ['name' => 'Antony']);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage No such action 'missing'
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
     * @expectedExceptionMessage Action 'inner' can not be executed
     */
    public function testStaticAction(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'inner');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Action 'execute' can not be executed
     */
    public function testInheritedAction(): void
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'execute');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Action 'call' can not be executed
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
        $core->callAction(SampleCore::class, null, [
            'name' => 'Antony'
        ]);
    }

    public function testScope(): void
    {
        $container = new Container();
        $core = new SampleCore($container);

        $this->assertSame($container, $core->callAction(
            DummyController::class,
            'scope',
            ['id' => 900]
        ));
    }

    public function testGlobalScope(): void
    {
        $container = new Container();
        $core = new SampleCore($container);

        $this->assertSame($container, $core->callAction(
            DummyController::class,
            'globalScope',
            ['id' => 900]
        ));
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

<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Core\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Core\Tests\Fixtures\DummyController;
use Spiral\Core\Tests\Fixtures\SampleCore;

class ControllersTest extends TestCase
{
    public function testCallAction()
    {
        $core = new SampleCore(new Container());
        $this->assertSame("Hello, Antony.", $core->callAction(
            DummyController::class,
            'index',
            ['name' => 'Antony']
        ));
    }

    public function testCallActionDefaultParameter()
    {
        $core = new SampleCore(new Container());
        $this->assertSame("Hello, Dave.", $core->callAction(
            DummyController::class,
            'index'
        ));
    }

    public function testCallActionDefaultAction()
    {
        $core = new SampleCore(new Container());
        $this->assertSame("Hello, Dave.", $core->callAction(
            DummyController::class
        ));
    }

    public function testCallActionDefaultActionWithParameter()
    {
        $core = new SampleCore(new Container());
        $this->assertSame("Hello, Antony.", $core->callAction(
            DummyController::class,
            null,
            ['name' => 'Antony']
        ));
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Missing/invalid parameter 'id'
     */
    public function testCallActionMissingParameter()
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Missing/invalid parameter 'id'
     */
    public function testCallActionInvalidParameter()
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'required', ['id' => null]);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage No such controller 'Spiral\Core\Tests\BadController' found
     */
    public function testCallWrongController()
    {
        $core = new SampleCore(new Container());
        $core->callAction(BadController::class, null, ['name' => 'Antony']);
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage No such action 'missing'
     */
    public function testCallBadAction()
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
    public function testStaticAction()
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'inner');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Action 'execute' can not be executed
     */
    public function testInheritedAction()
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'execute');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage Action 'call' can not be executed
     */
    public function testInheritedActionCall()
    {
        $core = new SampleCore(new Container());
        $core->callAction(DummyController::class, 'call');
    }

    /**
     * @expectedException \Spiral\Core\Exception\ControllerException
     * @expectedExceptionMessage No such controller 'Spiral\Core\Tests\Fixtures\SampleCore' found
     */
    public function testCallNotController()
    {
        $core = new SampleCore(new Container());
        $core->callAction(SampleCore::class, null, [
            'name' => 'Antony'
        ]);
    }

    public function testScope()
    {
        $container = new Container();
        $core = new SampleCore($container);

        $this->assertSame($container, $core->callAction(
            DummyController::class,
            'scope',
            ['id' => 900]
        ));
    }

    public function testGlobalScope()
    {
        $container = new Container();
        $core = new SampleCore($container);

        $this->assertSame($container, $core->callAction(
            DummyController::class,
            'globalScope',
            ['id' => 900]
        ));
    }
}
<?php

namespace Orchestra\Model\TestCase\Memory;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Model\Memory\UserMetaProvider;

class UserMetaProviderTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var Illuminate\Model\Memory\Application
     */
    private $app = null;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $this->app = new Container();
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Model\Memory\UserMetaRepository::initiate()
     * method.
     *
     * @test
     */
    public function testInitiateMethod()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserMetaRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([])
            ->shouldReceive('finish')->once()->andReturn(true);

        $stub = new UserMetaProvider($handler);

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');

        $items->setAccessible(true);

        $items->setValue($stub, [
            'foo/user-1'    => '',
            'foobar/user-1' => 'foo',
            'foo/user-2'    => ':to-be-deleted:',
        ]);

        $this->assertTrue($stub->finish());
    }

    /**
     * Test Orchestra\Model\Memory\UserMetaRepository::get() method.
     *
     * @test
     */
    public function testGetMethod()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserMetaRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([])
            ->shouldReceive('retrieve')->once()->with('foo/user-1')->andReturn('foobar')
            ->shouldReceive('retrieve')->once()->with('foobar/user-1')->andReturnNull();

        $stub = new UserMetaProvider($handler);

        $this->assertEquals('foobar', $stub->get('foo.1'));
        $this->assertEquals(null, $stub->get('foobar.1'));
    }

    /**
     * Test Orchestra\Model\Memory\UserMetaRepository::forget()
     * method.
     *
     * @test
     */
    public function testForgetMethod()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserMetaRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([]);

        $stub = new UserMetaProvider($handler);

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');

        $items->setAccessible(true);

        $items->setValue($stub, [
            'foo/user-1'   => 'foobar',
            'hello/user-1' => 'foobar',
        ]);

        $this->assertEquals('foobar', $stub->get('foo.1'));
        $stub->forget('foo.1');
        $this->assertNull($stub->get('foo.1'));
    }
}

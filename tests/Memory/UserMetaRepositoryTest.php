<?php

namespace Orchestra\Model\TestCase\Memory;

use Mockery as m;
use Illuminate\Support\Fluent;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Model\Memory\UserMetaRepository;

class UserMetaRepositoryTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
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
        $stub = new UserMetaRepository('meta', [], $this->app);

        $this->assertEquals([], $stub->initiate());
    }

    /**
     * Test Orchestra\Model\Memory\UserMetaRepository::initiate() method.
     *
     * @test
     */
    public function testRetrieveMethod()
    {
        $app = $this->app;

        $app->instance('Orchestra\Model\UserMeta', $eloquent = m::mock('UserMeta'));

        $eloquent->shouldReceive('newInstance')->once()->andReturn($eloquent)
            ->shouldReceive('where')->once()->with('user_id', '=', 1)->andReturnSelf()
            ->shouldReceive('get')->once()->andReturn([
                new Meta([
                    'name' => 'foo',
                    'id' => 2,
                    'value' => 'foobar',
                ]),
            ]);

        $stub = new UserMetaRepository('meta', [], $app);

        $this->assertEquals('foobar', $stub->retrieve('foo/user-1'));
        $this->assertNull($stub->retrieve('foobar/user-1'));
    }

    /**
     * Test Orchestra\Model\Memory\UserMetaRepository::finish() method.
     *
     * @test
     */
    public function testFinishMethod()
    {
        $app = $this->app;

        $items = [
            'foo/user-1' => 'foobar',
            'foobar/user-1' => 'foo',
            'foo/user-2' => ':to-be-deleted:',
            'foo/user-' => '',
        ];

        $app->instance('Orchestra\Model\UserMeta', $eloquent = m::mock('UserMeta'));

        $eloquent->shouldReceive('newInstance')->times(4)->andReturn($eloquent)
            ->shouldReceive('search')->once()->with('foo', 1)
                ->andReturn($fooQuery = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial())
            ->shouldReceive('search')->once()->with('foobar', 1)
                ->andReturn($foobarQuery = m::mock('\Illuminate\Database\Eloquent\Builder')->makePartial())
            ->shouldReceive('search')->once()->with('foo', 2)
                ->andReturn($foobarQuery)
            ->shouldReceive('setAttribute')->once()->with('name', 'foobar')->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('user_id', 1)->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('value', 's:3:"foo";')->andReturnNull()
            ->shouldReceive('setAttribute')->once()->with('value', 's:6:"foobar";')->andReturnNull()
            ->shouldReceive('save')->twice()->andReturnNull();

        $fooQuery->shouldReceive('first')->andReturn($eloquent);
        $foobarQuery->shouldReceive('first')->andReturnNull();

        $stub = new UserMetaRepository('meta', [], $app);

        $this->assertTrue($stub->finish($items));
    }
}

class Meta extends Fluent
{
    public function getAttribute($key)
    {
        return $this->{$key};
    }
}

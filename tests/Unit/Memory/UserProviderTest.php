<?php

namespace Orchestra\Model\TestCase\Unit\Memory;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Model\Memory\UserProvider;

class UserProviderTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_can_be_initiated()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([])
            ->shouldReceive('finish')->once()->andReturn(true);

        $stub = new UserProvider($handler);

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');

        $items->setAccessible(true);

        $items->setValue($stub, [
            'foo/user-1' => '',
            'foobar/user-1' => 'foo',
            'foo/user-2' => ':to-be-deleted:',
        ]);

        $this->assertTrue($stub->finish());
    }

    /**
     * @test
     */
    public function it_can_get_an_item()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([])
            ->shouldReceive('retrieve')->once()->with('foo/user-1')->andReturn('foobar')
            ->shouldReceive('retrieve')->once()->with('foobar/user-1')->andReturnNull();

        $stub = new UserProvider($handler);

        $this->assertSame('foobar', $stub->get('foo.1'));
        $this->assertNull($stub->get('foobar.1'));
    }

    /**
     * @test
     */
    public function it_can_forget_an_item()
    {
        $handler = m::mock('\Orchestra\Model\Memory\UserRepository');

        $handler->shouldReceive('initiate')->once()->andReturn([]);

        $stub = new UserProvider($handler);

        $refl = new \ReflectionObject($stub);
        $items = $refl->getProperty('items');

        $items->setAccessible(true);

        $items->setValue($stub, [
            'foo/user-1' => 'foobar',
            'hello/user-1' => 'foobar',
        ]);

        $this->assertSame('foobar', $stub->get('foo.1'));

        $stub->forget('foo.1');

        $this->assertNull($stub->get('foo.1'));
    }
}

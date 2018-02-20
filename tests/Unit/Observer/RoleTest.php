<?php

namespace Orchestra\Model\TestCase\Unit\Observer;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Model\Observer\Role as RoleObserver;

class RoleTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_observe_creating_event()
    {
        $acl = m::mock('Orchestra\Contracts\Authorization\Factory');
        $model = m::mock('\Orchestra\Model\Role');

        $model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
        $acl->shouldReceive('addRole')->once()->with('foo')->andReturn(null);

        $stub = new RoleObserver($acl);
        $stub->creating($model);
    }

    /** @test */
    public function it_observe_deleting_event()
    {
        $acl = m::mock('Orchestra\Contracts\Authorization\Factory');
        $model = m::mock('\Orchestra\Model\Role');

        $model->shouldReceive('getAttribute')->once()->with('name')->andReturn('foo');
        $acl->shouldReceive('removeRole')->once()->with('foo')->andReturn(null);

        $stub = new RoleObserver($acl);
        $stub->deleting($model);
    }

    /** @test */
    public function it_observe_updating_event()
    {
        $acl = m::mock('Orchestra\Contracts\Authorization\Factory');
        $model = m::mock('\Orchestra\Model\Role');

        $model->shouldReceive('getOriginal')->once()->with('name')->andReturn('foo')
            ->shouldReceive('getAttribute')->once()->with('name')->andReturn('foobar')
            ->shouldReceive('getDeletedAtColumn')->never()->andReturn('deleted_at')
            ->shouldReceive('isSoftDeleting')->once()->andReturn(false);
        $acl->shouldReceive('renameRole')->once()->with('foo', 'foobar')->andReturn(null);

        $stub = new RoleObserver($acl);
        $stub->updating($model);
    }

    /** @test */
    public function it_observe_updating_event_for_restoring()
    {
        $acl = m::mock('Orchestra\Contracts\Authorization\Factory');
        $model = m::mock('\Orchestra\Model\Role');

        $model->shouldReceive('getOriginal')->once()->with('name')->andReturn('foo')
            ->shouldReceive('getAttribute')->once()->with('name')->andReturn('foobar')
            ->shouldReceive('getDeletedAtColumn')->once()->andReturn('deleted_at')
            ->shouldReceive('isSoftDeleting')->once()->andReturn(true)
            ->shouldReceive('getOriginal')->once()->with('deleted_at')->andReturn('0000-00-00 00:00:00')
            ->shouldReceive('getAttribute')->once()->with('deleted_at')->andReturn(null);
        $acl->shouldReceive('addRole')->once()->with('foobar')->andReturn(null);

        $stub = new RoleObserver($acl);
        $stub->updating($model);
    }
}

<?php

namespace Orchestra\Model\TestCase\Unit;

use Mockery as m;
use Orchestra\Model\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Orchestra\Support\Traits\Testing\MockEloquentConnection;

class UserTest extends TestCase
{
    use MockEloquentConnection;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(new Container());
    }

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
    public function it_can_check_whether_user_has_roles()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->times(4)->andReturn(new Collection(['admin', 'editor']));

        $this->assertTrue($model->hasRoles('admin'));
        $this->assertFalse($model->hasRoles('user'));

        $this->assertTrue($model->hasRoles(['admin', 'editor']));
        $this->assertFalse($model->hasRoles(['admin', 'user']));

        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->times(4)->andReturn(new Collection(['foo']));

        $this->assertFalse($model->hasRoles('admin'));
        $this->assertFalse($model->hasRoles('user'));

        $this->assertFalse($model->hasRoles(['admin', 'editor']));
        $this->assertFalse($model->hasRoles(['admin', 'user']));
    }

    /**
     * @test
     */
    public function it_can_check_whether_user_has_any_roles()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->twice()->andReturn(new Collection(['admin', 'editor']));

        $this->assertTrue($model->hasAnyRoles(['admin', 'user']));
        $this->assertFalse($model->hasAnyRoles(['superadmin', 'user']));
    }

    /**
     * @test
     */
    public function it_can_check_whether_user_has_any_roles_given_invalid_data()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->twice()->andReturn(new Collection(['foo']));

        $this->assertFalse($model->hasAnyRoles(['admin', 'editor']));
        $this->assertFalse($model->hasAnyRoles(['admin', 'user']));
    }

    /**
     * @test
     */
    public function it_can_search_user_based_on_keyword_and_roles()
    {
        $model = new User();
        $this->addMockConnection($model);

        $keyword = 'foo*';
        $search = 'foo%';
        $roles = ['admin'];

        $query = m::mock('\Illuminate\Database\Eloquent\Builder');
        $query->shouldReceive('with')->once()->with('roles')->andReturn($query)
            ->shouldReceive('whereNotNull')->once()->with('users.id')->andReturn($query)
            ->shouldReceive('whereHas')->once()->with('roles', m::type('Closure'))
                ->andReturnUsing(function ($n, $c) use ($query) {
                    $c($query);
                })
            ->shouldReceive('whereIn')->once()->with('roles.id', $roles)->andReturn(null)
            ->shouldReceive('orWhere')->once()->with('email', 'LIKE', $search)->andReturn($query)
            ->shouldReceive('orWhere')->once()->with('fullname', 'LIKE', $search)->andReturn(null)
            ->shouldReceive('where')->once()->with(m::type('Closure'))->andReturnUsing(function ($q) use ($query, $keyword) {
                $q($query);
            })
            ->shouldReceive('orWhere')->twice()->with(m::type('Closure'))->andReturnUsing(function ($q) use ($query, $keyword) {
                $q($query);
            });

        $this->assertEquals($query, $model->scopeSearch($query, $keyword, $roles));
    }
}

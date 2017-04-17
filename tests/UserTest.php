<?php namespace Orchestra\Model\TestCase;

use Mockery as m;
use Orchestra\Model\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Facade;
use Orchestra\Support\Traits\Testing\MockEloquentConnection;

class UserTest extends TestCase
{
    use MockEloquentConnection;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(new Container());
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Model\User::roles() method.
     *
     * @test
     */
    public function testRolesMethod()
    {
        $model = new User();

        $this->addMockConnection($model);

        $stub = $model->roles();

        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $stub);
        $this->assertInstanceOf('\Orchestra\Model\Role', $stub->getQuery()->getModel());
    }

    /**
     * Test Orchestra\Model\User::attachRole() method.
     *
     * @test
     */
    public function testAttachRoleMethod()
    {
        $model = m::mock('\Orchestra\Model\User[roles]');
        $relationship = m::mock('\Illuminate\Database\Eloquent\Relations\BelongsToMany')->makePartial();

        $model->shouldReceive('roles')->once()->andReturn($relationship);
        $relationship->shouldReceive('sync')->once()->with([2], false)->andReturnNull();

        $model->attachRole(2);
    }

    /**
     * Test Orchestra\Model\User::detachRole() method.
     *
     * @test
     */
    public function testDetachRoleMethod()
    {
        $model = m::mock('\Orchestra\Model\User[roles]');
        $relationship = m::mock('\Illuminate\Database\Eloquent\Relations\BelongsToMany')->makePartial();

        $model->shouldReceive('roles')->once()->andReturn($relationship);
        $relationship->shouldReceive('detach')->once()->with([2])->andReturnNull();

        $model->detachRole(2);
    }

    /**
     * Test Orchestra\Model\User::hasRoles() method.
     *
     * @test
     */
    public function testHasRolesMethod()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->times(4)->andReturn(['admin', 'editor']);

        $this->assertTrue($model->hasRoles('admin'));
        $this->assertFalse($model->hasRoles('user'));

        $this->assertTrue($model->hasRoles(['admin', 'editor']));
        $this->assertFalse($model->hasRoles(['admin', 'user']));
    }

    /**
     * Test Orchestra\Support\Auth::hasRoles() method when invalid roles is
     * returned.
     *
     * @test
     */
    public function testHasRolesMethodWhenInvalidRolesIsReturned()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->times(4)->andReturn('foo');

        $this->assertFalse($model->hasRoles('admin'));
        $this->assertFalse($model->hasRoles('user'));

        $this->assertFalse($model->hasRoles(['admin', 'editor']));
        $this->assertFalse($model->hasRoles(['admin', 'user']));
    }

    /**
     * Test Orchestra\Model\User::hasAnyRoles() method.
     *
     * @test
     */
    public function testHasAnyRolesMethod()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->twice()->andReturn(['admin', 'editor']);

        $this->assertTrue($model->hasAnyRoles(['admin', 'user']));
        $this->assertFalse($model->hasAnyRoles(['superadmin', 'user']));
    }

    /**
     * Test Orchestra\Support\Auth::hasAnyRoles() method when invalid roles is
     * returned.
     *
     * @test
     */
    public function testhasAnyRolesMethodWhenInvalidRolesIsReturned()
    {
        $model = m::mock('\Orchestra\Model\User[getRoles]');

        $model->shouldReceive('getRoles')->twice()->andReturn('foo');

        $this->assertFalse($model->hasAnyRoles(['admin', 'editor']));
        $this->assertFalse($model->hasAnyRoles(['admin', 'user']));
    }

    /**
     * Test Orchestra\Model\User::getRoles() method.
     *
     * @test
     */
    public function testGetRolesMethod()
    {
        $model = m::mock('\Orchestra\Model\User[relationLoaded,load,getRelation]');
        $relationship = m::mock('\Illuminate\Database\Eloquent\Relations\BelongsToMany')->makePartial();

        $model->shouldReceive('relationLoaded')->once()->andReturn(false)
            ->shouldReceive('load')->once()->with('roles')->andReturnNull()
            ->shouldReceive('getRelation')->once()->with('roles')->andReturn($relationship);
        $relationship->shouldReceive('pluck')->once()->andReturn(['admin', 'editor']);

        $this->assertEquals(['admin', 'editor'], $model->getRoles());
    }

    /**
     * Test Orchestra\Model\User::scopeSearch() method.
     *
     * @test
     */
    public function testScopeSearchMethod()
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
            });;

        $this->assertEquals($query, $model->scopeSearch($query, $keyword, $roles));
    }

    /**
     * Test Orchestra\Model\User::getAuthIdentifier() method.
     *
     * @test
     */
    public function testGetAuthIdentifierMethod()
    {
        $stub = new User();
        $stub->id = 5;

        $this->assertEquals(5, $stub->getAuthIdentifier());
    }

    /**
     * Test Orchestra\Model\User::getAuthPassword() method.
     *
     * @test
     */
    public function testGetAuthPasswordMethod()
    {
        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(true);
        $hash->shouldReceive('make')->once()->with('foo')->andReturn('foobar');

        $stub = new User();
        $stub->password = 'foo';

        $this->assertEquals('foobar', $stub->getAuthPassword());
    }

    /**
     * Test Orchestra\Model\User::getAuthPassword() method without rehash.
     *
     * @test
     */
    public function testGetAuthPasswordMethodWithoutRehash()
    {
        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(false);

        $stub = new User();
        $stub->password = 'foo';

        $this->assertEquals('foo', $stub->getAuthPassword());
    }

    /**
     * Test Orchestra\Model\User::getRememberToken() method.
     *
     * @test
     */
    public function testGetRememberTokenMethod()
    {
        $stub = new User();
        $stub->remember_token = 'foobar';

        $this->assertEquals('foobar', $stub->getRememberToken());
    }

    /**
     * Test Orchestra\Model\User::setRememberToken() method.
     *
     * @test
     */
    public function testSetRememberTokenMethod()
    {
        $stub = m::mock('\Orchestra\Model\User[setAttribute]');
        $stub->shouldReceive('setAttribute')->once()->with('remember_token', 'foobar')->andReturnNull();

        $stub->setRememberToken('foobar');
    }

    /**
     * Test Orchestra\Model\User::getRememberTokenName() method.
     *
     * @test
     */
    public function testGetRememberTokenNameMethod()
    {
        $stub = new User();
        $this->assertEquals('remember_token', $stub->getRememberTokenName());
    }

    /**
     * Test Orchestra\Model\User::activate() method.
     *
     * @test
     */
    public function testActivateMethod()
    {
        $stub = new User();
        $stub->status = 0;

        $this->assertEquals($stub, $stub->activate());
    }

    /**
     * Test Orchestra\Model\User::deactivate() method.
     *
     * @test
     */
    public function testDeactivateMethod()
    {
        $stub = new User();
        $stub->status = 1;

        $this->assertEquals($stub, $stub->deactivate());
    }

    /**
     * Test Orchestra\Model\User::suspend() method.
     *
     * @test
     */
    public function testSuspendMethod()
    {
        $stub = new User();
        $stub->status = 1;

        $this->assertEquals($stub, $stub->suspend());
    }

    /**
     * Test Orchestra\Model\User::isActivated() method when account
     * is activated.
     *
     * @test
     */
    public function testIsActivatedMethodReturnTrue()
    {
        $stub = new User();
        $stub->status = 0;

        $stub->activate();

        $this->assertTrue($stub->isActivated());
    }

    /**
     * Test Orchestra\Model\User::isActivated() method when account
     * is not activated.
     *
     * @test
     */
    public function testIsActivatedMethodReturnFalse()
    {
        $stub = new User();
        $stub->status = 0;

        $this->assertFalse($stub->isActivated());
    }

    /**
     * Test Orchestra\Model\User::isSuspended() method when account
     * is suspended.
     *
     * @test
     */
    public function testIsSuspendedMethodReturnTrue()
    {
        $stub = new User();
        $stub->status = 0;

        $this->assertFalse($stub->isSuspended());

        $stub->suspend();

        $this->assertTrue($stub->isSuspended());
    }

    /**
     * Test Orchestra\Model\User::isSuspended() method when account
     * is not suspended.
     *
     * @test
     */
    public function testIsSuspendedMethodReturnFalse()
    {
        $stub = new User();
        $stub->status = 0;

        $this->assertFalse($stub->isActivated());
    }
}

<?php

namespace Orchestra\Model\TestCase\Feature;

use Mockery as m;
use Orchestra\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../factories');
    }

    /**
     * @test
     */
    public function it_belongs_to_many_roles()
    {
        $model = new User();

        $roles = $model->roles();

        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsToMany', $roles);
        $this->assertInstanceOf('\Orchestra\Model\Role', $roles->getQuery()->getModel());
    }

    /**
     * @test
     */
    public function it_can_attach_roles()
    {
        $user = User::faker()->create();
        $user->attachRole(2);

        $this->assertTrue($user->hasRoles('Member'));
    }

    /**
     * @test
     */
    public function it_can_detach_roles()
    {
        $user = User::faker()->create();
        $user->attachRole(2);

        $this->assertTrue($user->hasRoles('Member'));

        $user->detachRole(2);

        $this->assertFalse($user->hasRoles('Member'));
    }

    /**
     * @test
     */
    public function it_can_get_list_of_roles()
    {
        $user = User::faker()->create();
        $user->attachRoles([1, 2]);

        $this->assertEquals(['Administrator', 'Member'], $user->getRoles()->all());
    }

    /**
     * @test
     */
    public function it_can_manually_set_remember_token()
    {
        $user = User::faker()->create();
        $user->setRememberToken('foobar');

        $this->assertSame('foobar', $user->getRememberToken());
    }

    /**
     * @test
     */
    public function it_can_get_remember_token_name()
    {
        $this->assertEquals('remember_token', (new User())->getRememberTokenName());
    }

    /**
     * @test
     */
    public function it_can_get_remember_token_value()
    {
        $user = User::faker()->create([
            'remember_token' => 'foobar',
        ]);

        $this->assertEquals('foobar', $user->getRememberToken());
    }

    /**
     * @test
     */
    public function it_can_deactivate_the_user()
    {
        $user = User::faker()->create([
            'status' => 1,
        ]);

        $this->assertEquals($user, $user->deactivate());
        $this->assertFalse($user->isActivated());
    }

    /**
     * @test
     */
    public function it_can_suspend_the_user()
    {
        $user = User::faker()->create([
            'status' => 1,
        ]);

        $this->assertEquals($user, $user->suspend());
        $this->assertTrue($user->isSuspended());
    }

    /**
     * @test
     */
    public function it_can_activate_the_user()
    {
        $user = User::faker()->create([
            'status' => 0,
        ]);

        $this->assertFalse($user->isActivated());

        $user->activate();

        $this->assertTrue($user->isActivated());
    }

    /**
     * @test
     */
    public function it_can_activate_and_suspend_the_user()
    {
        $user = User::faker()->create([
            'status' => 0,
        ]);

        $this->assertFalse($user->isSuspended());
        $this->assertFalse($user->isActivated());

        $user->suspend();

        $this->assertTrue($user->isSuspended());
    }

    /**
     * Test Orchestra\Model\User::getAuthIdentifier() method.
     *
     * @test
     */
    public function testGetAuthIdentifierMethod()
    {
        $user = User::faker()->create([
            'id' => 1983,
        ]);

        $this->assertEquals(1983, $user->getAuthIdentifier());
    }

    /**
     * Test Orchestra\Model\User::getAuthPassword() method.
     *
     * @test
     */
    public function it_will_hash_upon_changing_the_password()
    {
        $user = User::faker()->create();

        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(true);
        $hash->shouldReceive('make')->once()->with('foo')->andReturn('foobar');

        $user->password = 'foo';

        $this->assertEquals('foobar', $user->getAuthPassword());
    }

    /**
     * Test Orchestra\Model\User::getAuthPassword() method without rehash.
     *
     * @test
     */
    public function it_can_get_password_without_rehash()
    {
        $user = User::faker()->create();

        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(false);

        $user->password = 'foo';

        $this->assertEquals('foo', $user->getAuthPassword());
    }
}

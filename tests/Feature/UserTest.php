<?php

namespace Orchestra\Model\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravie\Dhosa\HotSwap;
use Mockery as m;
use Orchestra\Model\Role;
use Orchestra\Model\Testing\Factories\RoleFactory;
use Orchestra\Model\Testing\Factories\UserFactory;
use Orchestra\Model\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        HotSwap::flush();
    }

    /** @test */
    public function it_implements_hot_swap()
    {
        $this->assertSame('User', User::hsAliasName());

        $user = User::hs();

        $this->assertNotInstanceOf(UserStub::class, $user);
        $this->assertInstanceOf(User::class, $user);

        HotSwap::override('User', UserStub::class);

        $user = User::hs();

        $this->assertInstanceOf(UserStub::class, $user);
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function it_belongs_to_many_roles()
    {
        $model = new User();

        $roles = $model->roles();

        $this->assertInstanceOf(BelongsToMany::class, $roles);
        $this->assertInstanceOf(Role::class, $roles->getQuery()->getModel());
    }

    /** @test */
    public function it_can_attach_roles()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(2);

        $this->assertTrue($user->hasRoles('Member'));
    }

    /** @test */
    public function it_can_attach_roles_from_instance_of_role()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(Role::find(2));

        $this->assertTrue($user->hasRoles('Member'));
    }

    /** @test */
    public function it_can_detach_roles()
    {
        $user = \tap(UserFactory::new()->create(), function ($user) {
            $user->roles()->sync([2]);
        });

        $this->assertTrue($user->hasRoles('Member'));

        $user->detachRole(2);

        $this->assertFalse($user->hasRoles('Member'));
    }

    /** @test */
    public function it_can_detach_roles_from_instance_of_role()
    {
        $user = \tap(UserFactory::new()->create(), function ($user) {
            $user->roles()->sync([2]);
        });

        $this->assertTrue($user->hasRoles('Member'));

        $user->detachRole(Role::find(2));

        $this->assertFalse($user->hasRoles('Member'));
    }

    /** @test */
    public function it_can_get_list_of_roles()
    {
        $user = UserFactory::new()->create();
        $user->attachRoles([1, 2]);

        $this->assertEquals(['Administrator', 'Member'], $user->getRoles()->all());
    }

    /** @test */
    public function it_can_manually_set_remember_token()
    {
        $user = UserFactory::new()->create();
        $user->setRememberToken('foobar');

        $this->assertSame('foobar', $user->getRememberToken());
    }

    /** @test */
    public function it_can_get_remember_token_name()
    {
        $this->assertEquals('remember_token', (new User())->getRememberTokenName());
    }

    /** @test */
    public function it_can_get_remember_token_value()
    {
        $user = UserFactory::new()->create([
            'remember_token' => 'foobar',
        ]);

        $this->assertEquals('foobar', $user->getRememberToken());
    }

    /** @test */
    public function it_can_deactivate_the_user()
    {
        $user = UserFactory::new()->create([
            'status' => 1,
        ]);

        $this->assertEquals($user, $user->deactivate());
        $this->assertFalse($user->isActivated());
    }

    /** @test */
    public function it_can_suspend_the_user()
    {
        $user = UserFactory::new()->create([
            'status' => 1,
        ]);

        $this->assertEquals($user, $user->suspend());
        $this->assertTrue($user->isSuspended());
    }

    /** @test */
    public function it_can_activate_the_user()
    {
        $user = UserFactory::new()->create([
            'status' => 0,
            'email_verified_at' => null,
        ]);

        $this->assertFalse($user->isActivated());
        $this->assertNull($user->email_verified_at);

        $user->activate();

        $this->assertTrue($user->isActivated());
        $this->assertNotNull($user->email_verified_at);
    }

    /** @test */
    public function it_can_activate_and_suspend_the_user()
    {
        $user = UserFactory::new()->create([
            'status' => 0,
        ]);

        $this->assertFalse($user->isSuspended());
        $this->assertFalse($user->isActivated());

        $user->suspend();

        $this->assertTrue($user->isSuspended());
    }

    /** @test */
    public function it_can_get_auth_identifier()
    {
        $user = UserFactory::new()->create([
            'id' => 1983,
        ]);

        $this->assertEquals(1983, $user->getAuthIdentifier());
    }

    /** @test */
    public function it_will_hash_upon_changing_the_password()
    {
        $user = UserFactory::new()->create();

        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(true);
        $hash->shouldReceive('make')->once()->with('foo')->andReturn('foobar');

        $user->password = 'foo';

        $this->assertEquals('foobar', $user->getAuthPassword());
    }

    /** @test */
    public function it_can_get_password_without_rehash()
    {
        $user = UserFactory::new()->create();

        Hash::swap($hash = m::mock('\Illuminate\Hashing\HasherInterface'));

        $hash->shouldReceive('needsRehash')->once()->with('foo')->andReturn(false);

        $user->password = 'foo';

        $this->assertEquals('foo', $user->getAuthPassword());
    }

    /** @test */
    public function it_can_search_user_based_on_keyword_and_roles_id()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(1);

        $keyword = substr($user->fullname, 0, 2);

        $search = User::search($keyword)->hasRolesId([1])->first();

        $this->assertSame($user->email, $search->email);
    }

    /** @test */
    public function it_can_search_user_based_on_keyword_and_roles_name()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(1);

        $keyword = substr($user->fullname, 0, 2);

        $search = User::search($keyword)->hasRoles(['Administrator'])->first();

        $this->assertSame($user->email, $search->email);
    }

    /** @test */
    public function it_can_check_whether_user_has_roles()
    {
        $user = UserFactory::new()->create();
        $editor = RoleFactory::new()->create([
            'name' => 'Editor',
        ]);
        $user->attachRoles([1, $editor->id]);

        $this->assertTrue($user->hasRoles('Administrator'));
        $this->assertFalse($user->hasRoles('User'));

        $this->assertTrue($user->hasRoles(['Administrator', 'Editor']));
        $this->assertFalse($user->hasRoles(['Administrator', 'User']));

        $user = UserFactory::new()->create();
        $role = RoleFactory::new()->create([
            'name' => 'Foo',
        ]);

        $user->attachRole($role);

        $this->assertFalse($user->hasRoles('Administrator'));
        $this->assertFalse($user->hasRoles('User'));

        $this->assertFalse($user->hasRoles(['Administrator', 'Editor']));
        $this->assertFalse($user->hasRoles(['Administrator', 'User']));
    }

    /** @test */
    public function it_can_check_whether_user_has_any_roles()
    {
        $user = UserFactory::new()->create();
        $user->attachRoles([1, 2]);

        $this->assertTrue($user->hasAnyRoles(['Administrator', 'User']));
        $this->assertFalse($user->hasAnyRoles(['Superadmin', 'User']));
    }

    /** @test */
    public function it_can_check_whether_user_has_any_roles_given_invalid_data()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(1);

        $this->assertFalse($user->hasAnyRoles(['admin', 'editor']));
        $this->assertFalse($user->hasAnyRoles(['admin', 'user']));
    }

    /** @test */
    public function it_can_search_user_based_on_keyword_and_roles_not_found()
    {
        $user = UserFactory::new()->create();
        $user->attachRole(1);

        $keyword = substr($user->fullname, 0, 2);

        $search = User::search($keyword)->hasRolesId([2])->first();

        $this->assertNull($search);
    }
}

class UserStub extends User
{
    //
}

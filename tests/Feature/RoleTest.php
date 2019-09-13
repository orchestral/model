<?php

namespace Orchestra\Model\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Model\HS;
use Orchestra\Model\Role;
use Orchestra\Model\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        HS::flush();
    }

    /** @test */
    public function it_implements_hot_swap()
    {
        $this->assertSame('Role', Role::hsAliasName());

        $user = Role::hs();

        $this->assertNotInstanceOf(RoleStub::class, $user);
        $this->assertInstanceOf(Role::class, $user);

        HS::override('Role', RoleStub::class);

        $user = Role::hs();

        $this->assertInstanceOf(RoleStub::class, $user);
        $this->assertInstanceOf(Role::class, $user);
    }

    /** @test */
    public function it_belongs_to_many_user()
    {
        $model = new Role();

        $stub = $model->users();

        $this->assertInstanceOf(BelongsToMany::class, $stub);
        $this->assertInstanceOf(User::class, $stub->getQuery()->getModel());
    }

    /** @test */
    public function it_can_fetch_admin_role()
    {
        $admin = Role::admin();

        $this->assertEquals(1, $admin->getKey());
        $this->assertSame('Administrator', $admin->name);
    }

    /** @test */
    public function it_can_swap_admin_role()
    {
        $role = Role::faker()->create([
            'name' => 'Boss',
        ]);
        Role::setDefaultRoles(['admin' => $role->id, 'member' => 2]);

        $admin = Role::admin();

        $this->assertEquals($role->id, $admin->getKey());
        $this->assertSame('Boss', $admin->name);
    }

    /** @test */
    public function it_can_fetch_member_role()
    {
        $member = Role::member();

        $this->assertEquals(2, $member->getKey());
        $this->assertSame('Member', $member->name);
    }

    /** @test */
    public function it_can_swap_member_role()
    {
        $role = Role::faker()->create([
            'name' => 'Partner',
        ]);
        Role::setDefaultRoles(['admin' => 1, 'member' => $role->id]);

        $member = Role::member();

        $this->assertEquals($role->id, $member->getKey());
        $this->assertSame('Partner', $member->name);
    }
}

class RoleStub extends Role
{
    //
}

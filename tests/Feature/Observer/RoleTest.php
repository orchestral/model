<?php

namespace Orchestra\Model\Tests\Feature\Observer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;
use Orchestra\Model\Observer\Role as RoleObserver;
use Orchestra\Model\Role;
use Orchestra\Model\Tests\Feature\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_save_guest_role()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Role [Guest] is not allowed to be used!');

        $role = Role::faker()->create();

        $this->swap(
            'Orchestra\Contracts\Authorization\Factory', $acl = m::mock('Orchestra\Contracts\Authorization\Factory')
        );

        Role::observe(new RoleObserver($acl));

        $role->name = 'Guest';

        $role->save();
    }
}

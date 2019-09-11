<?php

namespace Orchestra\Model\Tests\Feature\Observer;

use Mockery as m;
use Orchestra\Model\Role;
use Orchestra\Model\Tests\Feature\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Model\Observer\Role as RoleObserver;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

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

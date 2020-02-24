<?php

namespace Orchestra\Model\Tests\Feature\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Model\Concerns\Swappable;
use Orchestra\Model\Role;
use Orchestra\Model\Tests\Feature\TestCase;

class SwappableTest extends TestCase
{
    use RefreshDatabase, Swappable;

    /** @test */
    public function it_can_create_an_instance_of_eloquent()
    {
        $role = static::hs(['name' => 'Staff']);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame('Staff', $role->name);
        $this->assertFalse($role->exists);
    }

    /** @test */
    public function it_can_create_an_instance_of_eloquent_query_builder()
    {
        $query = static::hsQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertInstanceOf(Role::class, $query->getModel());
    }

    /** @test */
    public function it_can_create_an_instance_of_eloquent_faker_builder()
    {
        $builder = static::hsFaker();

        $this->assertInstanceOf(FactoryBuilder::class, $builder);

        $role = $builder->create(['name' => 'Moderator']);

        $this->assertTrue($role->exists);
        $this->assertSame('Moderator', $role->name);
        $this->assertInstanceOf(Role::class, $role);
    }

    /** @test */
    public function it_can_find_the_hs_model_name()
    {
        $this->assertSame(Role::class, static::hsFinder());
    }

    /**
     * Get Hot-swappable alias name.
     */
    public static function hsAliasName(): string
    {
        return 'Role';
    }
}

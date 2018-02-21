<?php

namespace Orchestra\Model\TestCase\Feature;

use Mockery as m;
use Orchestra\Model\User;
use Orchestra\Model\UserMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMetaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_user()
    {
        $model = new UserMeta();
        $stub = $model->users();

        $this->assertInstanceOf(BelongsTo::class, $stub);
        $this->assertInstanceOf(User::class, $stub->getQuery()->getModel());
    }

    /** @test */
    public function it_can_search_by_scope()
    {
        $user = User::faker()->create();
        $meta = new UserMeta();
        $meta->name = 'foo';
        $meta->value = 'hello world';
        $meta->users()->associate($user);
        $meta->save();

        $stub = UserMeta::search('foo', 1)->first();

        $meta->fresh();

        $this->assertEquals($meta->getKey(), $stub->getKey());
        $this->assertEquals($meta->value, $stub->value);
    }
}

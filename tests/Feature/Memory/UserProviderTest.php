<?php

namespace Orchestra\Model\TestCase\Feature\Memory;

use Mockery as m;
use Orchestra\Model\User;
use Orchestra\Model\UserMeta;
use Orchestra\Model\Memory\UserProvider;
use Orchestra\Model\Memory\UserRepository;
use Orchestra\Model\TestCase\Feature\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProviderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function it_can_be_initiated_and_close()
    {
        $users = User::faker()->times(2)->create();

        UserMeta::insert([
            ['name' => 'foo', 'user_id' => $users[0]->id, 'value' => ''],
            ['name' => 'foobar', 'user_id' => $users[0]->id, 'value' => 'foo'],
            ['name' => 'foo', 'user_id' => $users[1]->id, 'value' => 'foo'],
        ]);

        $stub = new UserProvider(new UserRepository('meta', [], $this->app));

        $stub->forget('foo.'.$users[1]->id);

        $this->assertTrue($stub->finish());

        $this->assertDatabaseHas('user_meta', ['name' => 'foo', 'user_id' => $users[0]->id, 'value' => '']);
        $this->assertDatabaseHas('user_meta', ['name' => 'foobar', 'user_id' => $users[0]->id, 'value' => 'foo']);
        $this->assertDatabaseMissing('user_meta', ['name' => 'foo', 'user_id' => $users[1]->id]);
    }

    /**
     * @test
     */
    public function it_can_get_an_item()
    {
        $user = User::faker()->create();

        UserMeta::insert([
            ['name' => 'foo', 'user_id' => $user->id, 'value' => 'foobar'],
        ]);

        $stub = new UserProvider(new UserRepository('meta', [], $this->app));

        $this->assertSame('foobar', $stub->get('foo.1'));
        $this->assertNull($stub->get('foobar.1'));
    }

    /**
     * @test
     */
    public function it_can_forget_an_item()
    {

        $user = User::faker()->create();

        UserMeta::insert([
            ['name' => 'foo', 'user_id' => $user->id, 'value' => 'foobar'],
            ['name' => 'hello', 'user_id' => $user->id, 'value' => 'foobar'],
        ]);

        $stub = new UserProvider(new UserRepository('meta', [], $this->app));

        $this->assertSame('foobar', $stub->get('foo.'.$user->id));

        $stub->forget('foo.'.$user->id);

        $this->assertNull($stub->get('foo.'.$user->id));
    }
}

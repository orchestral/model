<?php

namespace Orchestra\Model\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Model\Testing\Factories\UserFactory;
use Orchestra\Model\User;

class EloquentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_use_save_if_exists_when_model_not_saved()
    {
        $user = UserFactory::new()->make([
            'fullname' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
        ]);

        $this->assertFalse($user->saveIfExists());
        $this->assertFalse($user->exists);
    }

    /** @test */
    public function it_can_use_save_if_exists_when_model_already_saved()
    {
        $user = UserFactory::new()->create();

        $user->fullname = 'Mior Muhammad Zaki';

        $this->assertTrue($user->saveIfExists());
    }

    /** @test */
    public function it_cant_use_save_if_exists_or_failed_when_model_not_saved()
    {
        $user = UserFactory::new()->make([
            'fullname' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
        ]);

        $this->assertFalse($user->saveIfExistsOrFail());
        $this->assertFalse($user->exists);
    }

    /** @test */
    public function it_can_use_save_if_exists_or_fail_when_model_already_saved()
    {
        $user = UserFactory::new()->create();

        $user->fullname = 'Mior Muhammad Zaki';

        $this->assertTrue($user->saveIfExistsOrFail());
    }

    /** @test */
    public function it_can_be_transformed()
    {
        $user = UserFactory::new()->create([
            'fullname' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
        ])->transform(function ($user) {
            return [
                'name' => $user->fullname,
                'email' => $user->email,
            ];
        });

        $this->assertInstanceOf('Illuminate\Support\Fluent', $user);
        $this->assertSame(['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com'], $user->toArray());
    }
}

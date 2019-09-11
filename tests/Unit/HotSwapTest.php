<?php

namespace Orchestra\Model\Tests\Unit;

use Orchestra\Model\HS;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Model\Concerns\Swappable;

class HotSwapTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        HS::flush();
    }

    /** @test */
    public function it_can_register_swappable_class()
    {
        HS::register(SwappableModel::class);

        $this->assertSame(SwappableModel::class, Hs::eloquent('Model'));
        $this->assertInstanceOf(SwappableModel::class, Hs::make('Model'));
    }

    /** @test */
    public function it_cant_register_none_swappable_class()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given [Orchestra\Model\Tests\Unit\NotSwappableModel] doesn\'t use [Orchestra\Model\Concerns\Swappable] trait.');

        HS::register(NotSwappableModel::class);
    }

    /** @test */
    public function it_cant_register_none_eloquent_class()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given [Orchestra\Model\Tests\Unit\NotModel] is not a subclass of [Illuminate\Database\Eloquent\Model].');

        HS::register(NotModel::class);
    }
}

class SwappableModel extends Model
{
    use Swappable;

    public static function hsAliasName(): string
    {
        return 'Model';
    }
}

class NotSwappableModel extends Model
{
    //
}

class NotModel
{
    //
}

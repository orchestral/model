<?php

namespace Orchestra\Model\Tests\Unit;

use Orchestra\Model\Eloquent;
use PHPUnit\Framework\TestCase;

class EloquentTest extends TestCase
{
    /** @test */
    public function it_can_detect_soft_deletes()
    {
        $eloquent = new class() extends Eloquent {
            protected $forceDeleting = false;
        };

        $this->assertTrue($eloquent->isSoftDeleting());
    }

    /** @test */
    public function it_can_detect_none_soft_deletes()
    {
        $eloquent = new class() extends Eloquent {
            //
        };

        $this->assertFalse($eloquent->isSoftDeleting());
    }

    /** @test */
    public function it_can_detect_force_deletes()
    {
        $eloquent = new class() extends Eloquent {
            protected $forceDeleting = true;
        };

        $this->assertFalse($eloquent->isSoftDeleting());
    }
}

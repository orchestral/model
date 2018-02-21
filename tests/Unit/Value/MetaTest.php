<?php

namespace Orchestra\Model\TestCase\Unit\Value;

use Orchestra\Model\Value\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $meta = new Meta([
            'user' => ['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com'],
            'organization' => 'Orchestra Platform',
        ]);

        $this->assertInstanceOf('\Illuminate\Support\Fluent', $meta);
        $this->assertSame('crynobone@gmail.com', $meta->get('user.email'));
    }

    /** @test */
    public function it_can_put_content_to_meta()
    {
        $meta = new Meta([
            'user' => ['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com'],
            'organization' => 'Orchestra Platform',
        ]);

        $meta->put('user.gender', 'male');

        $this->assertSame('male', $meta->get('user.gender'));
        $this->assertSame([
            'user' => ['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com', 'gender' => 'male'],
            'organization' => 'Orchestra Platform',
        ], $meta->toArray());
    }

    /** @test */
    public function it_can_remove_content_to_meta()
    {
        $meta = new Meta([
            'user' => ['name' => 'Mior Muhammad Zaki', 'email' => 'crynobone@gmail.com'],
            'organization' => 'Orchestra Platform',
        ]);

        $meta->forget('user.email');

        $this->assertSame([
            'user' => ['name' => 'Mior Muhammad Zaki'],
            'organization' => 'Orchestra Platform',
        ], $meta->toArray());
    }
}

<?php

namespace Orchestra\Model\Tests\Unit\Concerns;

use PHPUnit\Framework\TestCase;
use Orchestra\Model\Concerns\AdvancedSearchable;

class AdvancedSearchableTest extends TestCase
{
    use AdvancedSearchable;

    /** @test */
    public function it_can_parse_keywords()
    {
        $keywords = $this->resolveSearchKeywords(
            'Orchestra Platform name:"Mior Muhammad Zaki" email:crynobone@katsana.com tags:github work:KATSANA'
        );

        $this->assertSame('Orchestra Platform', $keywords['basic']);
        $this->assertSame([
            'name:"Mior Muhammad Zaki"',
            'email:crynobone@katsana.com',
            'tags:github',
            'work:KATSANA',
        ], $keywords['advanced']);
    }

    /**
     * Get searchable rules.
     *
     * @return array
     */
    public function getSearchableRules(): array
    {
        return [
            'name:*' => function () {
            },
            'email:*' => function () {
            },
            'work:*' => function () {
            },
            'tags:[]' => function () {
            },
        ];
    }
}

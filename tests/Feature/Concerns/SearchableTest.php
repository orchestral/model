<?php

namespace Orchestra\Model\Tests\Feature\Concerns;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Model\Testing\Factories\UserFactory;
use Orchestra\Model\Tests\Feature\TestCase;
use Orchestra\Model\User;

class SearchableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_search_by_exact_rules()
    {
        UserFactory::new()->times(2)->create();
        UserFactory::new()->times(3)->create([
            'remember_token' => null,
        ]);

        $query = (new StubUser())->search('is:inactive');

        $this->assertSame(3, $query->count());
    }

    /** @test */
    public function it_can_search_by_exact_rules_when_keyword_doesnt_match()
    {
        UserFactory::new()->times(2)->create();

        $query = (new StubUser())->search('is:inactive');

        $this->assertSame(0, $query->count());
    }

    /** @test */
    public function it_can_search_by_wildcard_rules()
    {
        UserFactory::new()->times(2)->create();
        $me = UserFactory::new()->create([
            'fullname' => 'Mior Muhammad Zaki',
            'email' => 'crynobone@gmail.com',
        ]);

        $query = (new StubUser())->search('email:crynobone@gmail.com');

        $this->assertSame(1, $query->count());

        $user = $query->first();

        $this->assertSame($me->fullname, $user->fullname);
        $this->assertTrue($me->is($user));
    }

    /** @test */
    public function it_can_search_by_wildcard_rules_when_keyword_doesnt_match()
    {
        UserFactory::new()->times(2)->create();

        $query = (new StubUser())->search('email:crynobone@gmail.com');

        $this->assertSame(0, $query->count());
    }

    /** @test */
    public function it_can_search_by_wildcard_array_rules()
    {
        UserFactory::new()->times(3)->create();

        $query = (new StubUser())->search('ids:1 ids:3');

        $this->assertSame(2, $query->count());
        $this->assertSame([1, 3], $query->pluck('id')->all());
    }

    /** @test */
    public function it_can_search_by_wildcard_array_rules_when_keyword_doesnt_match()
    {
        UserFactory::new()->times(3)->create();

        $query = (new StubUser())->search('ids:10 ids:30');

        $this->assertSame(0, $query->count());
    }
}

class StubUser extends User
{
    public function getSearchableTerms(): array
    {
        return [
            'is:inactive' => function ($query) {
                $query->whereNull('remember_token');
            },
            'email:*' => function ($query, $email) {
                $query->where('email', '=', $email);
            },
            'ids:[]' => function ($query, array $ids) {
                $query->whereIn('id', $ids);
            },
        ];
    }
}

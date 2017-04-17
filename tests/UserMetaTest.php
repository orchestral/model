<?php namespace Orchestra\Model\TestCase;

use Mockery as m;
use Orchestra\Model\UserMeta;
use PHPUnit\Framework\TestCase;
use Orchestra\Support\Traits\Testing\MockEloquentConnection;

class UserMetaTest extends TestCase
{
    use MockEloquentConnection;

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Model\UserMeta::users() method.
     *
     * @test
     */
    public function testUsersMethod()
    {
        $model = new UserMeta();

        $this->addMockConnection($model);

        $stub = $model->users();

        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $stub);
        $this->assertInstanceOf('\Orchestra\Model\User', $stub->getQuery()->getModel());
    }

    /**
     * Test Orchestra\Model\UserMeta::search() method.
     *
     * @test
     */
    public function testScopeSearchMethod()
    {
        $query = m::mock('\Illuminate\Database\Eloquent\Builder');

        $query->shouldReceive('where')->once()->with('user_id', '=', 1)->andReturn($query)
            ->shouldReceive('where')->once()->with('name', '=', 'foo')->andReturn($query);

        with(new UserMeta())->scopeSearch($query, 'foo', 1);
    }
}

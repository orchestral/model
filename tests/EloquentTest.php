<?php namespace Orchestra\Model\TestCase;

use Orchestra\Model\Eloquent;

class EloquentTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSoftDeletingMethod()
    {
        $stub1 = new SoftDeletingModel();
        $stub2 = new NoneSoftDeletingModel();
        $stub3 = new ForceDeletingModel();

        $this->assertTrue($stub1->isSoftDeleting());
        $this->assertFalse($stub2->isSoftDeleting());
        $this->assertFalse($stub3->isSoftDeleting());
    }
}

class SoftDeletingModel extends Eloquent
{
    protected $forceDeleting = false;
}

class NoneSoftDeletingModel extends Eloquent
{
    //
}

class ForceDeletingModel extends Eloquent
{
    protected $forceDeleting = true;
}

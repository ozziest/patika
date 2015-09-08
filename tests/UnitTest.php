<?php 

use Ozziest\Patika\Manager;

class UnitTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidInit()
    {
        $_SERVER['REQUEST_URI'] = 'users/all';
        $patika = new Manager([]);
    }

    public function testInit()
    {
        $patika = new Manager(['app' => 'App\Controllers']);
        $request = $patika->getRequest();
        $this->assertInstanceOf('\Ozziest\Patika\Request', $request);
        $this->assertEquals('App\Controllers', $request->getMainNamespace());
        $this->assertEquals('users/all', $request->getUrl());
        $this->assertEquals("App\Controllers\Users", $request->getFullNamespace());
        $this->assertEquals("all", $request->getAction());
    }

    public function testLongUrl()
    {
        $_SERVER['REQUEST_URI'] = 'admin/manage/internal/users/all';
        $patika = new Manager(['app' => 'App\Controllers']);
        $request = $patika->getRequest();
        $this->assertEquals('App\Controllers', $request->getMainNamespace());
        $this->assertEquals('admin/manage/internal/users/all', $request->getUrl());
        $this->assertEquals("App\Controllers\Admin\Manage\Internal\Users", $request->getFullNamespace());
        $this->assertEquals("all", $request->getAction());
    }

    public function testSimpleArgument()
    {
        $_SERVER['REQUEST_URI'] = 'users/get/1/2/3/foo/bar';
        $patika = new Manager(['app' => 'App\Controllers']);
        $request = $patika->getRequest();
        $this->assertEquals('App\Controllers', $request->getMainNamespace());
        $this->assertEquals('users/get/1/2/3/foo/bar', $request->getUrl());
        $this->assertEquals("App\Controllers\Users", $request->getFullNamespace());
        $this->assertEquals("get", $request->getAction());
        $arguments = $request->getArguments();
        $this->assertCount(5, $arguments);
        $this->assertEquals("foo", $arguments[3]);
    }

}
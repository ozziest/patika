<?php 

use Ozziest\Patika\Manager;
use \Mockery as m;

class UnitTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

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

    /**
     * @expectedException \Ozziest\Patika\Exceptions\ControllerNotFoundException
     */
    public function testControllerNotFound()
    {
        $_SERVER['REQUEST_URI'] = 'users/all';
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->call();
        $this->assertTrue(true);
    }

    /**
     * @expectedException \Ozziest\Patika\Exceptions\MethodNotFoundException
     */
    public function testMethodNotFound()
    {
        $userController = $this->getMockBuilder('\App\Controllers\Users')->getMock();
        $_SERVER['REQUEST_URI'] = 'users/all';
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->call();
        $this->assertTrue(true);
    }

    public function testSuccess()
    {
        $userController = $this->getMockBuilder('\App\Controllers\Users')
            ->setMethods(array('all'))
            ->getMock();
        $userController->expects($this->once())
             ->method('all')
             ->with();
        $_SERVER['REQUEST_URI'] = 'users/all';
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->setMock($userController);
        $patika->call(array('constructer_data' => true));
        $this->assertTrue(true);
    }

    /**
     * @dataProvider sampleUrlProvider
     */
    public function testArguments($url, $controller, $method, $arguments)
    {
        $userController = $this->getMockBuilder($controller)
            ->setMethods(array($method))
            ->getMock();
        $userController->expects($this->once())
             ->method($method)
             ->withConsecutive($arguments);
        $_SERVER['REQUEST_URI'] = $url;
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->setMock($userController);
        $patika->call(array('constructer_data' => true));
    }

    public function sampleUrlProvider()
    {
        return array(
          array('users/get', '\App\Controllers\Users', 'get', []),
          array('users/manage/get', '\App\Controllers\Users\Manage', 'get', []),
          array('users/get/1/2/foo/bar', '\App\Controllers\Users', 'get', ['1', '2', 'foo', 'bar']),
          array('admin/manage/users/get/1/2/foo/bar', '\App\Controllers\Admin\Manage\Users', 'get', ['1', '2', 'foo', 'bar'])
        );
    }    

}
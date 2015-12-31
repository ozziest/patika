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

    /**
     * @dataProvider urlProvider
     */
    public function testLongUrl()
    {
        $_SERVER['REQUEST_URI'] = 'admin/manage/internal/users/all';
        $patika = new Manager(['app' => 'App\Controllers']);
        $request = $patika->getRequest();
        $this->assertInstanceOf('\Ozziest\Patika\Request', $request);
        $this->assertEquals('App\Controllers', $request->getMainNamespace());
        $this->assertEquals('admin/manage/internal/users/all', $request->getUrl());
        $this->assertEquals("App\Controllers\Admin\Manage\Internal\Users", $request->getFullNamespace());
        $this->assertEquals("all", $request->getAction());
    }

    /**
     * @dataProvider urlProvider
     * @expectedException \Ozziest\Patika\Exceptions\ControllerNotFoundException
     */
    public function testControllerNotFound($url, $controller, $method, $arguments)
    {
        $_SERVER['REQUEST_URI'] = $url;
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->call();
        $this->assertTrue(true);
    }

    /**
     * @dataProvider urlProvider
     * @expectedException \Ozziest\Patika\Exceptions\MethodNotFoundException
     */
    public function testMethodNotFound($url, $controller)
    {
        $_SERVER['REQUEST_URI'] = $url;
        $userController = $this->getMockBuilder($controller)->getMock();
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->call();
        $this->assertTrue(true);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testSuccess($url, $controller, $method, $arguments)
    {
        $_SERVER['REQUEST_URI'] = $url;
        $userController = $this->getMockBuilder($controller)
            ->setMethods(array($method))
            ->getMock();
        $userController->expects($this->once())
             ->method($method)
             ->with();
        $patika = new Manager(['app' => 'App\Controllers']);
        $patika->setMock($userController);
        $patika->call(array('constructer_data' => true));
        $this->assertTrue(true);
    }

    /**
     * @dataProvider urlProvider
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

    public function urlProvider()
    {
        return array(
            array('/', '\App\Controllers\Main', 'index', []),
            array('users/get', '\App\Controllers\Users', 'get', []),
            array('/users/get', '\App\Controllers\Users', 'get', []),
            array('/users/get', '\App\Controllers\Users', 'get', []),
            array('/users/manage/get', '\App\Controllers\Users\Manage', 'get', []),
            array('/users/get/1/2/foo/bar', '\App\Controllers\Users', 'get', ['1', '2', 'foo', 'bar']),
            array('/admin/manage/users/get/1/2/foo/bar', '\App\Controllers\Admin\Manage\Users', 'get', ['1', '2', 'foo', 'bar'])
        );
    }    

}
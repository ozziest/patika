<?php namespace Ozziest\Patika;

use Ozziest\Patika\Exceptions\ControllerNotFoundException;
use Ozziest\Patika\Exceptions\MethodNotFoundException;
use InvalidArgumentException, ReflectionClass;

class Manager {

    /**
     * Controller instance 
     *
     * @var boolean
     */
    private $instance = false;

    /**
     * Request object 
     *
     * @var Ozziest\Patika\Request
     */
    private $request;

    /**
     * Class constructer 
     *
     * @param  array        $options
     * @return null
     */
    public function __construct(Array $options, $defaultController = "Main", $defaultMethod = "index")
    {
        if (!isset($options['app'])) {
            throw new InvalidArgumentException('Application namespace must be setted!');
        }
        $this->request = new Request($options['app'], $defaultController, $defaultMethod);
    }

    /**
     * This method gets the request object
     *
     * @return Ozziest\Patika\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * This method calls the router if it was defined 
     *
     * @param  mixed        $constructerData
     * @return null
     */
    public function call($constructerData = array())
    {
        // Checking controller 
        $controller = $this->request->getFullNamespace();
        if (!class_exists($controller))
        {
            throw new ControllerNotFoundException("Controller not found: {$controller}");
        }
        // Creating a new instance for the controller 
        if ($this->instance === false)
        {
            $this->instance = $this->getInstance($controller, $constructerData);
        }
        // Checking method
        $action = $this->request->getAction();
        if (!method_exists($this->instance, $action))
        {
            throw new MethodNotFoundException("Method not found: {$action}()");
        }
        // The method of the instance is calling with all arguments
        call_user_func_array(
            array($this->instance, $action),
            $this->request->getArguments()
        );
    }

    /**
     * This method sets the mock controller
     *
     * @param  object       $mock 
     * @return null
     */
    public function setMock($mock)
    {
        $this->instance = $mock;
    }

    /**
     * This method gets a new instance for the controller 
     *
     * @param  string       $controller
     * @param  array        $constructerData
     * @return mixed
     */
    private function getInstance($controller, $constructerData)
    {
        $reflection = new ReflectionClass($controller);
        return $reflection->newInstanceArgs($constructerData);
    }

}
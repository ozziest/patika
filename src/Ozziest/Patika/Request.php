<?php namespace Ozziest\Patika;

class Request {

    /**
     * Application namespace
     *
     * @var string
     */
    private $app = '';

    /**
     * Current URL 
     *
     * @var string
     */
    private $url;

    /**
     * Namespace string
     *
     * @var string
     */
    private $namespace = '';

    /**
     * Action 
     *
     * @var string
     */
    private $action;

    /**
     * Arguments 
     *
     * @var array
     */
    private $arguments = [];

    /**
     * Default controller
     *
     * @var string
     */
    private $defaultController;

    /**
     * Default method
     *
     * @var string
     */
    private $defaultMethod;

    /**
     * Class constructer 
     *
     * @param  string        $app
     * @param  string        $defaultController
     * @param  string        $defaultMethod
     * @return null
     */
    public function __construct($app, $defaultController, $defaultMethod)
    {
        $this->app = $app;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        if (substr($this->app, strlen($this->app) - 1) === '\\')
        {
            $this->app = substr($this->app, 0, strlen($this->app) - 1);
        }
        $this->url = $_SERVER['REQUEST_URI'];
        $this->setNamespaceByUrl();
    }

    /**
     * This method gets the current url 
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * This method gets the full namespace 
     *
     * @return string
     */
    public function getFullNamespace()
    {
        if ($this->namespace === false)
        {
            $this->namespace = "Main";
        }
        return str_replace(['\\\\', '\\'], '\\', $this->app.'\\'.$this->namespace);
    }

    /**
     * This method gets the main namespace 
     *
     * @return string
     */
    public function getMainNamespace()
    {
        return $this->app;
    }

    /**
     * This method gets the action 
     *
     * @return string
     */
    public function getAction()
    {
        if (strlen(trim($this->action)) === 0)
        {
            $this->action = $this->defaultMethod;
        }
        return $this->action;
    }

    /**
     * This method gets the argument array 
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * This method sets the namespace by the current url 
     *
     * @return null
     */
    private function setNamespaceByUrl()
    {
        $parts = [];

        $found = false;
        // Arguments and parts are splited
        foreach (explode('/', $this->url) as $key => $item) 
        {
            if ($found === true || is_numeric($item))
            {
                $found = true;
                array_push($this->arguments, $item);
            } 
            else 
            {
                array_push($parts, $item);
            }
        }

        // Namespace is found
        foreach ($parts as $key => $item) 
        {
            if ($key < count($parts) - 1)
            {
                $this->namespace .= '\\'.ucfirst($item);
            }
        }
        if (substr($this->namespace, 0, 1) === '\\') 
        {
            $this->namespace = substr($this->namespace, 1);
        }
        // Setting the action
        $this->action = $parts[count($parts) - 1];        
    }
    
}
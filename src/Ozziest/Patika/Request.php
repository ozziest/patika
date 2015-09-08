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
     * Class constructer 
     *
     * @param  string        $app
     * @return null
     */
    public function __construct($app)
    {
        $this->app = $app;
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
        return $this->app.$this->namespace;
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

        // Setting the action
        $this->action = $parts[count($parts) - 1];        
    }
    
}
<?php namespace Ozziest\Patika;

use InvalidArgumentException;

class Manager {

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
    public function __construct(Array $options)
    {
        if (!isset($options['app'])) {
            throw new InvalidArgumentException('Application namespace must be setted!');
        }
        $this->request = new Request($options['app']);
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
    
}
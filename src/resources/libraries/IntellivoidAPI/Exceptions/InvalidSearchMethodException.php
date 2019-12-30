<?php


    namespace IntellivoidAPI\Exceptions;


    use Exception;

    /**
     * Class InvalidSearchMethodException
     * @package IntellivoidAPI\Exceptions
     */
    class InvalidSearchMethodException extends Exception
    {
        /**
         * InvalidSearchMethodException constructor.
         */
        public function __construct()
        {
            parent::__construct("The given search method is unsupported for this method");
        }
    }
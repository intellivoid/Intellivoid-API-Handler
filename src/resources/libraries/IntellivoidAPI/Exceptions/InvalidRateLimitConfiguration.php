<?php


    namespace IntellivoidAPI\Exceptions;

    use Exception;

    /**
     * Class InvalidRateLimitConfiguration
     * @package IntellivoidAPI\Exceptions
     */
    class InvalidRateLimitConfiguration extends Exception
    {
        /**
         * InvalidRateLimitConfiguration constructor.
         */
        public function __construct()
        {
            parent::__construct("The given rate limit configuration is invalid");
        }
    }
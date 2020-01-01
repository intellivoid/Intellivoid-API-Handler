<?php


    namespace IntellivoidAPI\Exceptions;
    
    
    use Exception;

    /**
     * Class ExceptionRecordNotFoundException
     * @package IntellivoidAPI\Exceptions
     */
    class ExceptionRecordNotFoundException extends Exception
    {
        /**
         * ExceptionRecordNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct("The exception record was not found in the database");
        }
    }
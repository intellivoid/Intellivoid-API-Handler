<?php


    namespace IntellivoidAPI\Exceptions;


    use Exception;

    /**
     * Class RequestRecordNotFoundException
     * @package IntellivoidAPI\Exceptions
     */
    class RequestRecordNotFoundException extends Exception
    {
        /**
         * RequestRecordNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct("The requested request record was not found in the database");
        }
    }
<?php


    namespace IntellivoidAPI\Exceptions;


    use Exception;

    /**
     * Class AccessRecordNotFoundException
     * @package IntellivoidAPI\Exceptions
     */
    class AccessRecordNotFoundException extends Exception
    {
        /**
         * AccessRecordNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct("The access record was not found in the database");
        }
    }
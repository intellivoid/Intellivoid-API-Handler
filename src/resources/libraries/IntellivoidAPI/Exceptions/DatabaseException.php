<?php


    namespace IntellivoidAPI\Exceptions;


    use Exception;

    /**
     * Class DatabaseException
     * @package IntellivoidAPI\Exceptions
     */
    class DatabaseException extends Exception
    {
        /**
         * @var string
         */
        private $query;
        /**
         * @var string
         */
        private $error_message;

        /**
         * DatabaseException constructor.
         * @param string $query
         * @param string $error_message
         */
        public function __construct(string $query, string $error_message)
        {
            parent::__construct("There was a database exception");

            $this->query = $query;
            $this->error_message = $error_message;
        }
    }
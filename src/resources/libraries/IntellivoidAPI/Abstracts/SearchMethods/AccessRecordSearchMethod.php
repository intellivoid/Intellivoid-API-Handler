<?php


    namespace IntellivoidAPI\Abstracts\SearchMethods;

    /**
     * Class AccessRecordSearchMethod
     * @package IntellivoidAPI\Abstracts\SearchMethods
     */
    abstract class AccessRecordSearchMethod
    {
        /**
         * Searches record by ID
         */
        const byId = 'id';

        /**
         * Searches record by Access Key
         *
         * (Can be changed by the user)
         */
        const byAccessKey = 'access_key';
    }
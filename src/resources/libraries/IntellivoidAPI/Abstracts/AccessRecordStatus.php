<?php


    namespace IntellivoidAPI\Abstracts;

    /**
     * Class AccessRecordStatus
     * @package IntellivoidAPI\Abstracts
     */
    abstract class AccessRecordStatus
    {
        /**
         * The access record is available
         */
        const Available = 0;

        /**
         * The access record is disabled
         */
        const Disabled = 1;

        /**
         * The access record is unavailable due to a billing error
         */
        const BillingError = 2;
    }
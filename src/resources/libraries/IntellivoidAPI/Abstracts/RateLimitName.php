<?php


    namespace IntellivoidAPI\Abstracts;

    /**
     * Class RateLimitName
     * @package IntellivoidAPI\Abstracts
     */
    abstract class RateLimitName
    {
        /**
         * No rate limit configuration is applied
         */
        const None = "NONE";

        /**
         * The rate limit is dependent on an interval
         */
        const IntervalLimit = "INTERVAL_LIMIT";
    }
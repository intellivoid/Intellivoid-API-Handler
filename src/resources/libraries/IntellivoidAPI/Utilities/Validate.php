<?php


    namespace IntellivoidAPI\Utilities;


    /**
     * Class Validate
     * @package IntellivoidAPI\Utilities
     */
    class Validate
    {
        /**
         * @param string $ip_address
         * @return bool
         */
        public static function ip_address(string $ip_address): bool
        {
            if(filter_var($ip_address, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
            {
                return true;
            }
            if(filter_var($ip_address, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
            {
                return true;
            }

            return false;
        }
    }
<?php


    namespace IntellivoidAPI\Utilities;

    /**
     * Class Hashing
     * @package IntellivoidAPI\Utilities
     */
    class Hashing
    {
        /**
         * Peppers a hash using whirlpool
         *
         * @param string $Data The hash to pepper
         * @param int $Min Minimal amounts of executions
         * @param int $Max Maximum amount of executions
         * @return string
         */
        public static function pepper(string $Data, int $Min = 100, int $Max = 1000): string
        {
            $n = rand($Min, $Max);
            $res = '';
            $Data = hash('whirlpool', $Data);
            for ($i=0,$l=strlen($Data) ; $l ; $l--)
            {
                $i = ($i+$n-1) % $l;
                $res = $res . $Data[$i];
                $Data = ($i ? substr($Data, 0, $i) : '') . ($i < $l-1 ? substr($Data, $i+1) : '');
            }
            return($res);
        }

        /**
         * Generates an access key, the ID is optional
         *
         * @param int $application_id
         * @param int $timestamp
         * @param int $id
         * @return string
         */
        public static function generateAccessKey(int $application_id, int $timestamp, int $id=0): string
        {
            $first_part = hash('crc32b', $application_id);
            $second_part = hash('crc32b', $id);
            $pepper =  self::pepper($first_part . $second_part . $timestamp);
            return hash('sha512', $first_part . $second_part . $timestamp . $pepper);
        }

        /**
         * Generates a reference unique reference ID, this includes
         *
         * the first bytes indicating the timestamp (8)
         * the second (64) bytes are the pepper (random) data
         * the last (64) are the fingerprint hash for the request
         *
         * In total 136 bytes are calculated which is very CPU intensive
         *
         * @param int $timestamp
         * @param int $application_id
         * @param int $access_record_id
         * @param string $ip_address
         * @param string $user_agent
         * @param string $path
         * @return string
         */
        public static function calculateReferenceId(int $timestamp, int $application_id, int $access_record_id, string $ip_address, string $user_agent, string $path): string
        {
            $timestamp = hash('sha1', $timestamp);
            $application_id = hash('sha1', $application_id);
            $access_record_id = hash('sha1', $access_record_id);
            $ip_address = hash('sha1', $ip_address);
            $user_agent = hash('sha1', $user_agent);
            $path = hash('sha1', $path);

            $part_1 = self::pepper($timestamp . $application_id . $access_record_id);
            $part_2 = self::pepper($ip_address . $user_agent . $path);
            $part_3 = self::pepper($part_1 . $part_2);

            $first_byes = hash('crc32b', $timestamp);
            $pepper_bytes = hash('sha256', $part_1 . $part_2 . $part_3);
            $fingerprint_bytes = hash('sha256', $timestamp . $application_id . $access_record_id . $ip_address . $user_agent . $path);

            return $first_byes . $pepper_bytes . $fingerprint_bytes;
        }
    }
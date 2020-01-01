<?php


    namespace Handler\GenericResponses;


    /**
     * Class UnsupportedVersion
     * @package Handler\GenericResponses
     */
    class UnsupportedVersion
    {
        /**
         * Executes the generic error response for a unsupported version
         */
        public static function executeResponse()
        {
            $ResponsePayload = array(
                'success' => false,
                'response_code' => 400,
                'error' => array(
                    'error_code' => 1,
                    'type' => "SERVER",
                    "message" => "The given version for this API is not supported"
                )
            );
            $ResponseBody = json_encode($ResponsePayload);

            http_response_code(400);
            header('Content-Type: application/json');
            header('Content-Size: ' . strlen($ResponseBody));
            print($ResponseBody);
        }
    }
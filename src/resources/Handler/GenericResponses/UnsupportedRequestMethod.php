<?php


    namespace Handler\GenericResponses;

    /**
     * Class UnsupportedRequestMethod
     * @package Handler\GenericResponses
     */
    class UnsupportedRequestMethod
    {
        /**
         * Returns a generic response stating the request method is unsupported
         */
        public static function executeResponse()
        {
            $ResponsePayload = array(
                'success' => true,
                'response_code' => 400,
                'error' => array(
                    'error_code' => 0,
                    'type' => "CLIENT",
                    "message" => "The given request method is unsupported"
                )
            );
            $ResponseBody = json_encode($ResponsePayload);

            http_response_code(400);
            header('Content-Type: application/json');
            header('Content-Size: ' . strlen($ResponseBody));
            print($ResponseBody);
        }
    }
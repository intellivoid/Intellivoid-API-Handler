<?php


    namespace Handler\GenericResponses;

    /**
     * Class ResourceNotFound
     * @package Handler\GenericResponses
     */
    class ResourceNotFound
    {
        /**
         * Returns a generic error response for a missing resource
         */
        public static function executeResponse()
        {
            $ResponsePayload = array(
                'success' => false,
                'response_code' => 404,
                'error' => array(
                    'error_code' => 0,
                    'type' => "SERVER",
                    "message" => "The requested resource/action is invalid or not found"
                ),
                'reference_code' => null
            );
            $ResponseBody = json_encode($ResponsePayload);

            http_response_code(404);
            header('Content-Type: application/json');
            header('Content-Size: ' . strlen($ResponseBody));
            print($ResponseBody);
        }
    }
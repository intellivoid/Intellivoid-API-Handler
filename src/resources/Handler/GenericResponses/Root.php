<?php


    namespace Handler\GenericResponses;


    use Handler\Handler;

    /**
     * Class Root
     * @package Handler\GenericResponses
     */
    class Root
    {
        /**
         * Executes the generic response for root '/'
         */
        public static function executeResponse()
        {
            $ResponsePayload = array(
                'success' => true,
                'response_code' => 200,
                'payload' => array(
                    'service_name' => Handler::$MainConfiguration->Name,
                    'documentation' => Handler::$MainConfiguration->DocumentationUrl
                ),
                'reference_code' => null
            );
            $ResponseBody = json_encode($ResponsePayload);

            http_response_code(200);
            header('Content-Type: application/json');
            header('Content-Size: ' . strlen($ResponseBody));
            print($ResponseBody);
        }
    }
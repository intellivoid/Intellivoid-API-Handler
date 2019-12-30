<?php


    namespace IntellivoidAPI\Objects;

    /**
     * Class RequestRecordEntry
     * @package IntellivoidAPI\Objects
     */
    class RequestRecordEntry
    {
        /**
         * The ID of the access record that was used to make this request
         *  0 = None
         *
         * @var int
         */
        public $AccessRecordID;

        /**
         * The ID of the application that is responsible for handling this request
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * The request method used to create this request
         *
         * @var string
         */
        public $RequestMethod;

        /**
         * The version of the API That this request was using
         *
         * @var string
         */
        public $Version;

        /**
         * The request path made by the client
         *
         * @var string
         */
        public $Path;

        /**
         * The GET/POST Payload included in the request
         *
         * @var array
         */
        public $RequestPayload;

        /**
         * The IP Address used to make this request
         *
         * @var string
         */
        public $IPAddress;

        /**
         * The user agent used to make this request
         *
         * @var string
         */
        public $UserAgent;

        /**
         * The HTTP response code given in the response
         *
         * @var int
         */
        public $ResponseCode;

        /**
         * The content type given in the response
         *
         * @var string
         */
        public $ResponseContentType;

        /**
         * The length (size) of the response that was given
         *
         * @var int
         */
        public $ResponseLength;

        /**
         * The time it took to process the request
         *
         * @var float
         */
        public $ResponseTime;

        /**
         * Returns an array which represents this object
         * 
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'access_record_id' => (int)$this->AccessRecordID,
                'application_id' => (int)$this->ApplicationID,
                'request_method' => $this->RequestMethod,
                'version' => $this->Version,
                'path' => $this->Path,
                'request_payload' => $this->RequestPayload,
                'ip_address' => $this->IPAddress,
                'user_agent' => $this->UserAgent,
                'response_code' => (int)$this->ResponseCode,
                'response_content_type' => $this->ResponseContentType,
                'response_length' => (int)$this->ResponseLength,
                'response_time' => (float)$this->ResponseTime
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return RequestRecordEntry
         */
        public static function fromArray(array $data): RequestRecordEntry
        {
            $RequestRecordEntryObject = new RequestRecordEntry();

            if(isset($data['access_Record_id']))
            {
                $RequestRecordEntryObject->AccessRecordID = (int)$data['access_Record_id'];
            }

            if(isset($data['application_id']))
            {
                $RequestRecordEntryObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['request_method']))
            {
                $RequestRecordEntryObject->RequestMethod = $data['request_method'];
            }

            if(isset($data['version']))
            {
                $RequestRecordEntryObject->Version = $data['version'];
            }

            if(isset($data['path']))
            {
                $RequestRecordEntryObject->Path = $data['path'];
            }

            if(isset($data['request_payload']))
            {
                $RequestRecordEntryObject->RequestPayload = $data['request_payload'];
            }

            if(isset($data['ip_address']))
            {
                $RequestRecordEntryObject->IPAddress = $data['ip_address'];
            }

            if(isset($data['user_agent']))
            {
                $RequestRecordEntryObject->UserAgent = $data['user_agent'];
            }

            if(isset($data['response_code']))
            {
                $RequestRecordEntryObject->ResponseCode = (int)$data['response_code'];
            }

            if(isset($data['response_content_type']))
            {
                $RequestRecordEntryObject->ResponseContentType = $data['response_content_type'];
            }

            if(isset($data['response_length']))
            {
                $RequestRecordEntryObject->ResponseLength = (int)$data['response_length'];
            }

            if(isset($data['response_time']))
            {
                $RequestRecordEntryObject->ResponseTime = (float)$data['response_time'];
            }

            return $RequestRecordEntryObject;
        }
    }
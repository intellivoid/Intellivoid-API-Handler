<?php /** @noinspection PhpUnused */


    namespace IntellivoidAPI\Objects;

    /**
     * Class RequestRecord
     * @package IntellivoidAPI\Objects
     */
    class RequestRecord
    {
        /**
         * Internal Unique Database ID for this request record
         *
         * @var int
         */
        public $ID;

        /**
         * Public reference ID
         *
         * @var string
         */
        public $ReferenceID;

        /**
         * The access record used to make this request, 0 = No authentication
         *
         * @var int
         */
        public $AccessRecordID;

        /**
         * The Application ID that handled this request
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * The HTTP request method used
         *
         * @var string
         */
        public $RequestMethod;

        /**
         * The payload used in the request
         *
         * @var array
         */
        public $RequestPayload;

        /**
         * The API Version that was used
         *
         * @var string
         */
        public $Version;

        /**
         * The path that was requested (URI)
         *
         * @var string
         */
        public $Path;

        /**
         * The IP Address used to accomplish this request
         *
         * @var string
         */
        public $IPAddress;

        /**
         * The user agent used for this request
         *
         * @var string
         */
        public $UserAgent;

        /**
         * The HTTP Response code given to the client
         *
         * @var int
         */
        public $ResponseCode;

        /**
         * The response type given to the client
         *
         * @var string
         */
        public $ResponseContentType;

        /**
         * The length of the response
         *
         * @var int
         */
        public $ResponseLength;

        /**
         * The time taken to process the request
         *
         * @var int
         */
        public $ResponseTime;

        /**
         * The unix timestamp for when this record was created
         *
         * @var int
         */
        public $Timestamp;

        /**
         * The day that this request took place in
         *
         * @var int
         */
        public $Day;

        /**
         * The month that this request took place in
         *
         * @var int
         */
        public $Month;

        /**
         * The year that this request took place in
         *
         * @var int
         */
        public $Year;

        /**
         * Returns the array which represents this object
         *
         * @return array
         * @noinspection PhpUnused
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'reference_id' => $this->ReferenceID,
                'access_record_id' => (int)$this->AccessRecordID,
                'application_id' => (int)$this->ApplicationID,
                'request_method' => $this->RequestMethod,
                'request_payload' => $this->RequestPayload,
                'version' => $this->Version,
                'path' => $this->Path,
                'ip_address' => $this->IPAddress,
                'user_agent' => $this->UserAgent,
                'response_code' => (int)$this->ResponseCode,
                'response_content_type' => $this->ResponseContentType,
                'response_length' => (int)$this->ResponseLength,
                'response_time' => (float)$this->ResponseTime,
                'day' => (int)$this->Day,
                'month' => (int)$this->Month,
                'year' => (int)$this->Year,
                'timestamp' => (int)$this->Timestamp
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return RequestRecord
         * @noinspection PhpUnused
         */
        public static function fromArray(array $data): RequestRecord
        {
            $RequestRecordObject = new RequestRecord();


            if(isset($data['id']))
            {
                $RequestRecordObject->ID = (int)$data['id'];
            }

            if(isset($data['reference_id']))
            {
                $RequestRecordObject->ReferenceID = $data['reference_id'];
            }

            if(isset($data['access_record_id']))
            {
                $RequestRecordObject->AccessRecordID = (int)$data['access_record_id'];
            }

            if(isset($data['application_id']))
            {
                $RequestRecordObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['request_method']))
            {
                $RequestRecordObject->RequestMethod = $data['request_method'];
            }

            if(isset($data['request_payload']))
            {
                $RequestRecordObject->RequestPayload = $data['request_payload'];
            }

            if(isset($data['version']))
            {
                $RequestRecordObject->Version = $data['version'];
            }

            if(isset($data['path']))
            {
                $RequestRecordObject->Path = $data['path'];
            }

            if(isset($data['ip_address']))
            {
                $RequestRecordObject->IPAddress = $data['ip_address'];
            }

            if(isset($data['user_agent']))
            {
                $RequestRecordObject->UserAgent = $data['user_agent'];
            }

            if(isset($data['response_code']))
            {
                $RequestRecordObject->ResponseCode = (int)$data['response_code'];
            }

            if(isset($data['response_content_type']))
            {
                $RequestRecordObject->ResponseContentType = $data['response_content_type'];
            }

            if(isset($data['response_length']))
            {
                $RequestRecordObject->ResponseLength = (int)$data['response_length'];
            }

            if(isset($data['response_time']))
            {
                $RequestRecordObject->ResponseTime = (float)$data['response_time'];
            }

            if(isset($data['day']))
            {
                $RequestRecordObject->Day = (int)$data['day'];
            }

            if(isset($data['month']))
            {
                $RequestRecordObject->Month = (int)$data['month'];
            }

            if(isset($data['year']))
            {
                $RequestRecordObject->Year = (int)$data['year'];
            }

            if(isset($data['timestamp']))
            {
                $RequestRecordObject->Timestamp = (int)$data['timestamp'];
            }

            return $RequestRecordObject;
        }
    }
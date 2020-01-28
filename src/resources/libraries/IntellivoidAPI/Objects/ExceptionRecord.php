<?php /** @noinspection PhpUnused */


    namespace IntellivoidAPI\Objects;

    /**
     * Reported exception record containing all the details to the exception
     *
     * Class ExceptionRecord
     * @package IntellivoidAPI\Objects
     */
    class ExceptionRecord
    {
        /**
         * The ID of the exception record
         *
         * @var int
         */
        public $ID;

        /**
         * The request record that raised this exception
         *
         * @var int
         */
        public $RequestRecordID;

        /**
         * The application ID that is responsible for handling the request
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * The access record ID if any was used
         *
         * @var int
         */
        public $AccessRecordID;

        /**
         * The message returned by the exception
         *
         * @var string
         */
        public $Message;

        /**
         * The affecting File
         *
         * @var string
         */
        public $File;

        /**
         * The line of the affecting file
         *
         * @var int
         */
        public $Line;

        /**
         * The exception code
         *
         * @var int
         */
        public $Code;

        /**
         * The trace of the exception
         *
         * @var array
         */
        public $Trace;

        /**
         * The Unix Timestamp of when this Exception Record was created
         *
         * @var int
         */
        public $Timestamp;

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => (int)$this->ID,
                'request_record_id' => (int)$this->RequestRecordID,
                'application_id' => (int)$this->ApplicationID,
                'access_record_id' => (int)$this->AccessRecordID,
                'message' => $this->Message,
                'file' => $this->File,
                'line' => (int)$this->Line,
                'code' => (int)$this->Code,
                'trace' => $this->Trace,
                'timestamp' => (int)$this->Timestamp
            );
        }

        /**
         * Constructs ExceptionRecord from array
         *
         * @param array $data
         * @return ExceptionRecord
         */
        public static function fromArray(array $data): ExceptionRecord
        {
            $ExceptionRecordObject = new ExceptionRecord();

            if(isset($data['id']))
            {
                $ExceptionRecordObject->ID = (int)$data['id'];
            }

            if(isset($data['request_record_id']))
            {
                $ExceptionRecordObject->RequestRecordID = (int)$data['request_record_id'];
            }

            if(isset($data['application_id']))
            {
                $ExceptionRecordObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['access_record_id']))
            {
                $ExceptionRecordObject->AccessRecordID = (int)$data['access_record_id'];
            }

            if(isset($data['message']))
            {
                $ExceptionRecordObject->Message = $data['message'];
            }

            if(isset($data['file']))
            {
                $ExceptionRecordObject->File = $data['file'];
            }

            if(isset($data['line']))
            {
                $ExceptionRecordObject->Line = (int)$data['line'];
            }

            if(isset($data['code']))
            {
                $ExceptionRecordObject->Code = (int)$data['code'];
            }

            if(isset($data['trace']))
            {
                $ExceptionRecordObject->Trace = $data['trace'];
            }

            if(isset($data['timestamp']))
            {
                $ExceptionRecordObject->Timestamp = (int)$data['timestamp'];
            }

            return $ExceptionRecordObject;
        }
    }
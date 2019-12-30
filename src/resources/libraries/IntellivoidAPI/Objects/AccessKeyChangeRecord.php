<?php


    namespace IntellivoidAPI\Objects;


    /**
     * Class AccessKeyChangeRecord
     * @package IntellivoidAPI\Objects
     */
    class AccessKeyChangeRecord
    {
        /**
         * The internal unique ID for this record
         *
         * @var int
         */
        public $ID;

        /**
         * The access record ID associated with this change
         *
         * @var int
         */
        public $AccessRecordID;

        /**
         * The old access key
         *
         * @var string
         */
        public $OldAccessKey;

        /**
         * The new access key
         *
         * @var string
         */
        public $NewAccessKey;

        /**
         * The Unix Timestamp of when this change was logged
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
                'access_record_id' => (int)$this->AccessRecordID,
                'old_access_key' => $this->OldAccessKey,
                'new_access_key' => $this->NewAccessKey,
                'timestamp' => (int)$this->Timestamp
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return AccessKeyChangeRecord
         */
        public static function fromArray(array $data): AccessKeyChangeRecord
        {
            $AccessKeyChangeRecordObject = new AccessKeyChangeRecord();

            if(isset($data['id']))
            {
                $AccessKeyChangeRecordObject->ID = (int)$data['id'];
            }

            if(isset($data['access_record_id']))
            {
                $AccessKeyChangeRecordObject->AccessRecordID = (int)$data['access_record_id'];
            }

            if(isset($data['old_access_key']))
            {
                $AccessKeyChangeRecordObject->OldAccessKey = $data['old_access_key'];
            }

            if(isset($data['new_access_key']))
            {
                $AccessKeyChangeRecordObject->NewAccessKey = $data['new_access_key'];
            }

            if(isset($data['timestamp']))
            {
                $AccessKeyChangeRecordObject->Timestamp = (int)$data['timestamp'];
            }

            return $AccessKeyChangeRecordObject;
        }
    }
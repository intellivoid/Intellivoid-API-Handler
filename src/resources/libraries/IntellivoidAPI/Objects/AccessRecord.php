<?php


    namespace IntellivoidAPI\Objects;

    use IntellivoidAPI\Abstracts\RateLimitName;
    use IntellivoidAPI\Objects\RateLimitTypes\IntervalLimit;

    /**
     * Class AccessRecord
     * @package IntellivoidAPI\Objects
     */
    class AccessRecord
    {
        /**
         * Internal unique database ID for this AccessKey
         *
         * @var int
         */
        public $ID;

        /**
         * Unique access key for authenticating access
         *
         * @var string
         */
        public $AccessKey;

        /**
         * The Unix Timestamp of when the access key was last changed
         *
         * @var int
         */
        public $LastChangedAccessKey;

        /**
         * The Application ID that this Access Record was created for
         *
         * @var int
         */
        public $ApplicationID;

        /**
         * Optional subscription ID that this Access Record is associated with
         *
         * @var int
         */
        public $SubscriptionID;

        /**
         * The status of the Access Record
         *
         * @var int
         */
        public $Status;

        /**
         * Variables that is associated with this Access Record
         *
         * @var array
         */
        public $Variables;

        /**
         * Rate Limit that has been applied to this access key
         *
         * @var string
         */
        public $RateLimitType;

        /**
         * The rate limit configuration applied to this access record
         *
         * @var IntervalLimit
         */
        public $RateLimitConfiguration;

        /**
         * Unix Timestamp of when this access record was last used
         *
         * @var int
         */
        public $LastActivity;

        /**
         * The Unix Timestamp of when this access record was created
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * Sets a variable to the object
         *
         * @param string $key
         * @param $value
         * @return bool
         */
        public function setVariable(string $key, $value): bool
        {
            $this->Variables[$key] = $value;
            return true;
        }

        /**
         * Removes an existing variable from the object
         *
         * @param string $key
         * @return bool
         */
        public function removeVariable(string $key): bool
        {
            if(isset($this->Variables[$key]) == false)
            {
                return false;
            }

            $this->Variables = array_diff($this->Variables, [$key]);
            return true;
        }

        /**
         * Returns a variable from the object
         *
         * @param string $key
         * @return mixed|null
         */
        public function getVariable(string $key)
        {
            if(isset($this->Variables[$key]) == false)
            {
                return null;
            }

            return $this->Variables[$key];
        }

        /**
         * Returns an array which represents this object's structure and values
         *
         * @return array
         */
        public function toArray(): array
        {
            $RateLimitConfiguration = array();

            if($this->RateLimitType !== RateLimitName::None)
            {
                $RateLimitConfiguration = $this->RateLimitConfiguration->toArray();
            }

            return array(
                'id' => (int)$this->ID,
                'access_key' => $this->AccessKey,
                'last_changed_access_key' => (int)$this->LastActivity,
                'application_id' => (int)$this->ApplicationID,
                'subscription_id' => (int)$this->SubscriptionID,
                'status' => (int)$this->Status,
                'variables' => $this->Variables,
                'rate_limit_type' => $this->RateLimitType,
                'rate_limit_configuration' => $RateLimitConfiguration,
                'last_activity' => (int)$this->LastActivity,
                'created' => (int)$this->CreatedTimestamp
            );
        }

        /**
         * Constructs
         *
         * @param array $data
         * @return AccessRecord
         */
        public static function fromArray(array $data): AccessRecord
        {
            $AccessRecordObject = new AccessRecord();

            if(isset($data['id']))
            {
                $AccessRecordObject->ID = (int)$data['id'];
            }

            if(isset($data['access_key']))
            {
                $AccessRecordObject->AccessKey = $data['access_key'];
            }

            if(isset($data['last_changed_access_key']))
            {
                $AccessRecordObject->LastChangedAccessKey = (int)$data['last_changed_access_key'];
            }

            if(isset($data['application_id']))
            {
                $AccessRecordObject->ApplicationID = (int)$data['application_id'];
            }

            if(isset($data['subscription_id']))
            {
                $AccessRecordObject->SubscriptionID = (int)$data['subscription_id'];
            }

            if(isset($data['status']))
            {
                $AccessRecordObject->Status = (int)$data['status'];
            }

            if(isset($data['variables']))
            {
                $AccessRecordObject->Variables = $data['variables'];
            }
            else
            {
                $AccessRecordObject->Variables = array();
            }

            $AccessRecordObject->RateLimitType = RateLimitName::None;
            $AccessRecordObject->RateLimitConfiguration = array();

            if(isset($data['rate_limit_type']))
            {
                if($data['rate_limit_type'] !== RateLimitName::None)
                {
                    if(isset($data['rate_limit_configuration']))
                    {
                        switch($data['rate_limit_type'])
                        {
                            case RateLimitName::IntervalLimit:
                                $AccessRecordObject->RateLimitConfiguration = IntervalLimit::fromArray($data['rate_limit_configuration']);
                                $AccessRecordObject->RateLimitType = RateLimitName::IntervalLimit;
                                break;
                        }
                    }
                }
            }

            if(isset($data['last_activity']))
            {
                $AccessRecordObject->LastActivity = (int)$data['last_activity'];
            }

            if(isset($data['created']))
            {
                $AccessRecordObject->CreatedTimestamp = (int)$data['created'];
            }

            return $AccessRecordObject;
        }
    }
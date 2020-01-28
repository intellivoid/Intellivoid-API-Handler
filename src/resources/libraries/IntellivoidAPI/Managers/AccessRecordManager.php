<?php /** @noinspection PhpUnused */


    namespace IntellivoidAPI\Managers;

    use IntellivoidAPI\Abstracts\AccessRecordStatus;
    use IntellivoidAPI\Abstracts\RateLimitName;
    use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
    use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
    use IntellivoidAPI\Exceptions\DatabaseException;
    use IntellivoidAPI\Exceptions\InvalidRateLimitConfiguration;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\IntellivoidAPI;
    use IntellivoidAPI\Objects\AccessRecord;
    use IntellivoidAPI\Objects\RateLimitTypes\IntervalLimit;
    use IntellivoidAPI\Utilities\Hashing;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class AccessKeyManager
     * @package IntellivoidAPI\Managers
     */
    class AccessRecordManager
    {
        /**
         * @var IntellivoidAPI
         */
        private $intellivoidAPI;

        /**
         * AccessKeyManager constructor.
         * @param IntellivoidAPI $intellivoidAPI
         */
        public function __construct(IntellivoidAPI $intellivoidAPI)
        {
            $this->intellivoidAPI = $intellivoidAPI;
        }

        /**
         * Creates a new Access Record
         *
         * @param int $application_id
         * @param int $subscription_id
         * @param string $rate_limit_type
         * @param array $rate_limit_configuration
         * @return AccessRecord
         * @throws AccessRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidRateLimitConfiguration
         * @throws InvalidSearchMethodException
         * @noinspection DuplicatedCode
         * @noinspection PhpUnused
         */
        public function createAccessRecord(int $application_id, $subscription_id=0, string $rate_limit_type=RateLimitName::None, array $rate_limit_configuration=array()): AccessRecord
        {
            $creation_timestamp = (int)time();
            $last_activity = 0;
            $application_id = (int)$application_id;

            $access_key = Hashing::generateAccessKey($application_id, $creation_timestamp, 0);
            $last_changed_access_key = 0;

            switch($rate_limit_type)
            {
                case RateLimitName::None:
                    $rate_limit_type = $this->intellivoidAPI->getDatabase()->real_escape_string(RateLimitName::None);
                    $rate_limit_configuration = ZiProto::encode(array());
                    $rate_limit_configuration = $this->intellivoidAPI->getDatabase()->real_escape_string($rate_limit_configuration);
                    break;

                case RateLimitName::IntervalLimit:
                    $rate_limit_type = $this->intellivoidAPI->getDatabase()->real_escape_string(RateLimitName::IntervalLimit);

                    /** @var IntervalLimit $rate_limit_configuration */
                    $rate_limit_configuration = ZiProto::encode($rate_limit_configuration);
                    $rate_limit_configuration = $this->intellivoidAPI->getDatabase()->real_escape_string($rate_limit_configuration);
                    break;

                default:
                    throw new InvalidRateLimitConfiguration();
            }

            $status = (int)AccessRecordStatus::Available;
            $subscription_id = (int)$subscription_id;
            $variables = ZiProto::encode(array());
            $variables = $this->intellivoidAPI->getDatabase()->real_escape_string($variables);

            $Query = QueryBuilder::insert_into('access_records', array(
                'access_key' => $access_key,
                'application_id' => $application_id,
                'created' => $creation_timestamp,
                'last_activity' => $last_activity,
                'last_changed_access_key' => $last_changed_access_key,
                'rate_limit_configuration' => $rate_limit_configuration,
                'rate_limit_type' =>  $rate_limit_type,
                'status' => $status,
                'subscription_id' => $subscription_id,
                'variables' => $variables
            ));
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return $this->getAccessRecord(AccessRecordSearchMethod::byAccessKey, $access_key);
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
        }

        /**
         * Returns a access record from the database
         *
         * @param string $search_by
         * @param $value
         * @return AccessRecord
         * @throws AccessRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @noinspection PhpUnused
         */
        public function getAccessRecord(string $search_by, $value): AccessRecord
        {
            /** @noinspection DuplicatedCode */
            switch($search_by)
            {
                case AccessRecordSearchMethod::byId:
                case AccessRecordSearchMethod::bySubscriptionID:
                    $search_by = $this->intellivoidAPI->getDatabase()->real_escape_string($search_by);
                    $value = (int)$value;
                    break;

                case AccessRecordSearchMethod::byAccessKey:
                    $search_by = $this->intellivoidAPI->getDatabase()->real_escape_string($search_by);
                    $value = $this->intellivoidAPI->getDatabase()->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('access_records', [
                'id',
                'access_key',
                'last_changed_access_key',
                'application_id',
                'subscription_id',
                'status',
                'variables',
                'rate_limit_type',
                'rate_limit_configuration',
                'last_activity',
                'created'
            ], $search_by, $value);
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new AccessRecordNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['variables'] = ZiProto::decode($Row['variables']);
                $Row['rate_limit_configuration'] = ZiProto::decode($Row['rate_limit_configuration']);

                return AccessRecord::fromArray($Row);
            }
        }

        /**
         * Updates an existing access record in the database
         *
         * @param AccessRecord $accessRecord
         * @return bool
         * @throws AccessRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidRateLimitConfiguration
         * @throws InvalidSearchMethodException
         * @noinspection DuplicatedCode
         * @noinspection PhpUnused
         */
        public function updateAccessRecord(AccessRecord $accessRecord): bool
        {
            // Will throw an exception if the Access Record does not exist
            $this->getAccessRecord(AccessRecordSearchMethod::byId, $accessRecord->ID);

            $access_key = $this->intellivoidAPI->getDatabase()->real_escape_string($accessRecord->AccessKey);
            $last_changed_access_key = (int)$accessRecord->LastChangedAccessKey;
            $application_id = (int)$accessRecord->ApplicationID;
            $subscription_id = (int)$accessRecord->SubscriptionID;
            $status = (int)$accessRecord->Status;
            $variables = $this->intellivoidAPI->getDatabase()->real_escape_string(ZiProto::encode($accessRecord->Variables));

            $rate_limit_type = $accessRecord->RateLimitType;
            $rate_limit_configuration = $accessRecord->RateLimitConfiguration;

            switch($rate_limit_type)
            {
                case RateLimitName::None:
                    $rate_limit_type = $this->intellivoidAPI->getDatabase()->real_escape_string(RateLimitName::None);
                    $rate_limit_configuration = ZiProto::encode(array());
                    $rate_limit_configuration = $this->intellivoidAPI->getDatabase()->real_escape_string($rate_limit_configuration);
                    break;

                case RateLimitName::IntervalLimit:
                    $rate_limit_type = $this->intellivoidAPI->getDatabase()->real_escape_string(RateLimitName::IntervalLimit);

                    /** @var IntervalLimit $rate_limit_configuration */
                    $rate_limit_configuration = ZiProto::encode($rate_limit_configuration->toArray());
                    $rate_limit_configuration = $this->intellivoidAPI->getDatabase()->real_escape_string($rate_limit_configuration);
                    break;

                default:
                    throw new InvalidRateLimitConfiguration();
            }

            $last_activity = (int)$accessRecord->LastActivity;

            $Query = QueryBuilder::update('access_records',array(
                'access_key' => $access_key,
                'last_changed_access_key' => $last_changed_access_key,
                'application_id' => $application_id,
                'subscription_id' => $subscription_id,
                'status' => $status,
                'variables' => $variables,
                'rate_limit_type' => $rate_limit_type,
                'rate_limit_configuration' => $rate_limit_configuration,
                'last_activity' => $last_activity
            ), 'id', (int)$accessRecord->ID);
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
        }

        /**
         * Deletes an existing Access Record form the database
         *
         * @param AccessRecord $accessRecord
         * @return bool
         * @throws AccessRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @noinspection PhpUnused
         */
        public function deleteAccessRecord(AccessRecord $accessRecord): bool
        {
            // Will throw an exception if the Access Record does not exist
            $this->getAccessRecord(AccessRecordSearchMethod::byId, $accessRecord->ID);

            $ID = (int)$accessRecord->ID;
            $Query = "DELETE FROM `access_records` WHERE id=" . $ID . ";";
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
        }

        /**
         * Generates a new access key and updates it immediately
         *
         * @param AccessRecord $accessRecord
         * @return AccessRecord
         * @throws AccessRecordNotFoundException
         * @throws DatabaseException
         * @throws InvalidRateLimitConfiguration
         * @throws InvalidSearchMethodException
         */
        public function generateNewAccessKey(AccessRecord $accessRecord): AccessRecord
        {
            $access_record_id = (int)$accessRecord->ID;
            $old_access_key = $this->intellivoidAPI->getDatabase()->real_escape_string($accessRecord->AccessKey);

            $accessRecord->LastChangedAccessKey = (int)time();
            $accessRecord->AccessKey = Hashing::generateAccessKey(
                $accessRecord->ApplicationID,
                $accessRecord->LastChangedAccessKey,
                $accessRecord->ID
            );
            $new_access_key = $this->intellivoidAPI->getDatabase()->real_escape_string($accessRecord->AccessKey);

            $this->updateAccessRecord($accessRecord);

            $Query = QueryBuilder::insert_into("access_key_changes", array(
                'access_record_id' => $access_record_id,
                'old_access_key' => $old_access_key,
                'new_access_key' => $new_access_key,
                'timestamp' => (int)$accessRecord->CreatedTimestamp
            ));
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);
            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }

            return $accessRecord;
        }
    }
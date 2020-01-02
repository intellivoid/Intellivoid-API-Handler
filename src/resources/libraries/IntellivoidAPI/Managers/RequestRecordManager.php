<?php


    namespace IntellivoidAPI\Managers;


    use IntellivoidAPI\Abstracts\SearchMethods\RequestRecordSearchMethod;
    use IntellivoidAPI\Exceptions\DatabaseException;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\Exceptions\RequestRecordNotFoundException;
    use IntellivoidAPI\IntellivoidAPI;
    use IntellivoidAPI\Objects\RequestRecord;
    use IntellivoidAPI\Objects\RequestRecordEntry;
    use IntellivoidAPI\Utilities\Hashing;
    use IntellivoidAPI\Utilities\Validate;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class RequestRecordManager
     * @package IntellivoidAPI\Managers
     */
    class RequestRecordManager
    {
        /**
         * @var IntellivoidAPI
         */
        private $intellivoidAPI;

        /**
         * RequestRecordManager constructor.
         * @param IntellivoidAPI $intellivoidAPI
         */
        public function __construct(IntellivoidAPI $intellivoidAPI)
        {
            $this->intellivoidAPI = $intellivoidAPI;
        }

        /**
         * Logs a request record into the database
         *
         * @param RequestRecordEntry $requestRecordEntry
         * @return string
         * @throws DatabaseException
         * @noinspection PhpUnusedLocalVariableInspection
         * @noinspection PhpUnused
         */
        public function logRecord(RequestRecordEntry $requestRecordEntry): string
        {
            $access_record_id = 0;
            $application_id = 0;
            $request_method = "Unknown";
            $version = "Unknown";
            $path = "Unknown";
            $request_payload = array();
            $ip_address = "Unknown";
            $user_agent = "Unknown";
            $response_code = 0;
            $response_content_type = "Unknown";
            $response_length = 0;
            $response_time = (float)0;

            if($requestRecordEntry->AccessRecordID !== null)
            {
                $access_record_id = (int)$requestRecordEntry->AccessRecordID;
            }

            if($requestRecordEntry->ApplicationID !== null)
            {
                $application_id = (int)$requestRecordEntry->ApplicationID;
            }

            if($requestRecordEntry->RequestMethod !== null)
            {
                if(strlen($requestRecordEntry->RequestMethod) > 1)
                {
                    if(strlen($requestRecordEntry->RequestMethod) < 20)
                    {
                        $request_method = $this->intellivoidAPI->getDatabase()->real_escape_string($requestRecordEntry->RequestMethod);
                    }
                }
            }

            if($requestRecordEntry->Version !== null)
            {
                if(strlen($requestRecordEntry->Version) > 1)
                {
                    if(strlen($requestRecordEntry->Version) < 20)
                    {
                        $version = $this->intellivoidAPI->getDatabase()->real_escape_string($requestRecordEntry->Version);
                    }
                }
            }

            if($requestRecordEntry->Path !== null)
            {
                if(strlen($requestRecordEntry->Path) > 1)
                {
                    if(strlen($requestRecordEntry->Path) < 255)
                    {
                        $path = $this->intellivoidAPI->getDatabase()->real_escape_string($requestRecordEntry->Path);
                    }
                }
            }


            if($requestRecordEntry->RequestPayload == null)
            {
                $requestRecordEntry->RequestPayload = array();
            }

            $request_payload = ZiProto::encode($requestRecordEntry->RequestPayload);
            $request_payload = $this->intellivoidAPI->getDatabase()->real_escape_string($request_payload);

            if(Validate::ip_address($requestRecordEntry->IPAddress))
            {
                $ip_address = $requestRecordEntry->IPAddress;
                $ip_address = $this->intellivoidAPI->getDatabase()->real_escape_string($ip_address);
            }

            if(strlen($requestRecordEntry->UserAgent) < 526)
            {
                $user_agent = base64_encode($requestRecordEntry->UserAgent);
                $user_agent = $this->intellivoidAPI->getDatabase()->real_escape_string($user_agent);
            }

            if($requestRecordEntry->ResponseCode == null)
            {
                $response_code = 0;
            }
            else
            {
                $response_code = (int)$requestRecordEntry->ResponseCode;
            }

            if($requestRecordEntry->ResponseContentType !== null)
            {
                if(strlen($requestRecordEntry->ResponseContentType) > 1)
                {
                    if(strlen($requestRecordEntry->ResponseContentType) < 255)
                    {
                        $response_content_type = $this->intellivoidAPI->getDatabase()->real_escape_string($requestRecordEntry->ResponseContentType);
                    }
                }
            }

            if($requestRecordEntry->ResponseLength == null)
            {
                $response_length = 0;
            }
            else
            {
                $response_length = (int)$requestRecordEntry->ResponseLength;
            }

            if($requestRecordEntry->ResponseTime == null)
            {
                $response_time = (float)0;
            }
            else
            {
                $response_time = (float)$requestRecordEntry->ResponseTime;
            }

            $timestamp = (int)time();
            $reference_id = Hashing::calculateReferenceId(
                $timestamp,
                $application_id,
                $access_record_id,
                $ip_address,
                $user_agent,
                $path
            );
            $reference_id = $this->intellivoidAPI->getDatabase()->real_escape_string($reference_id);
            $day = (int)date('d');
            $month = (int)date('m');
            $year = (int)date('Y');

            $Query = QueryBuilder::insert_into('request_records', array(
                'reference_id' => $reference_id,
                'access_record_id' => $access_record_id,
                'application_id' => $application_id,
                'request_method' => $request_method,
                'version' => $version,
                'path' => $path,
                'request_payload' => $request_payload,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'response_code' => $response_code,
                'response_content_type' => $response_content_type,
                'response_length' => $response_length,
                'response_time' => $response_time,
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'timestamp' => $timestamp
            ));
            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return $reference_id;
            }
            else
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
        }

        /**
         * Returns a request record object from the database
         *
         * @param string $search_method
         * @param $value
         * @return RequestRecord
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws RequestRecordNotFoundException
         * @noinspection PhpUnused
         */
        public function getRequestRecord(string $search_method, $value): RequestRecord
        {
            /** @noinspection DuplicatedCode */
            switch($search_method)
            {
                case RequestRecordSearchMethod::byId:
                    $search_method = $this->intellivoidAPI->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case RequestRecordSearchMethod::byReferenceId:
                    $search_method = $this->intellivoidAPI->getDatabase()->real_escape_string($search_method);
                    $value = $this->intellivoidAPI->getDatabase()->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select('request_records', [
                'id',
                'reference_id',
                'access_record_id',
                'application_id',
                'request_method',
                'version',
                'path',
                'request_payload',
                'ip_address',
                'user_agent',
                'response_code',
                'response_content_type',
                'response_length',
                'response_time',
                'day',
                'month',
                'year',
                'timestamp'
            ], $search_method, $value);

            $QueryResults = $this->intellivoidAPI->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($Query, $this->intellivoidAPI->getDatabase()->error);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new RequestRecordNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
                $Row['request_payload'] = ZiProto::decode($Row['request_payload']);

                return RequestRecord::fromArray($Row);
            }
        }
    }
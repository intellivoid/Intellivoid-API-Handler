<?php


    namespace IntellivoidAPI;


    use acm\acm;
    use Exception;
    use IntellivoidAPI\Managers\AccessRecordManager;
    use IntellivoidAPI\Managers\RequestRecordManager;
    use mysqli;

    $LocalDirectory = __DIR__ . DIRECTORY_SEPARATOR;

    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'AccessRecordSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'SearchMethods' . DIRECTORY_SEPARATOR . 'RequestRecordSearchMethod.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'AccessRecordStatus.php');
    include_once($LocalDirectory . 'Abstracts' . DIRECTORY_SEPARATOR . 'RateLimitName.php');

    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccessRecordNotFoundException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidRateLimitConfiguration.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidSearchMethodException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'RateLimitExceededException.php');
    include_once($LocalDirectory . 'Exceptions' . DIRECTORY_SEPARATOR . 'RequestRecordNotFoundException.php');

    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'AccessRecordManager.php');
    include_once($LocalDirectory . 'Managers' . DIRECTORY_SEPARATOR . 'RequestRecordManager.php');

    include_once($LocalDirectory . 'Objects' . DIRECTORY_SEPARATOR . 'RateLimitTypes' . DIRECTORY_SEPARATOR . 'IntervalLimit.php');
    include_once($LocalDirectory . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKeyChangeRecord.php');
    include_once($LocalDirectory . 'Objects' . DIRECTORY_SEPARATOR . 'AccessRecord.php');
    include_once($LocalDirectory . 'Objects' . DIRECTORY_SEPARATOR . 'RequestRecord.php');
    include_once($LocalDirectory . 'Objects' . DIRECTORY_SEPARATOR . 'RequestRecordEntry.php');

    include_once($LocalDirectory . 'Utilities' . DIRECTORY_SEPARATOR . 'Hashing.php');
    include_once($LocalDirectory . 'Utilities' . DIRECTORY_SEPARATOR . 'Validate.php');


    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    if(class_exists('msqg\msqg') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'msqg' . DIRECTORY_SEPARATOR . 'msqg.php');
    }

    if(class_exists('ZiProto\ZiProto') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'ZiProto' . DIRECTORY_SEPARATOR . 'ZiProto.php');
    }

    /**
     * Class IntellivoidAPI
     * @package IntellivoidAPI
     */
    class IntellivoidAPI
    {
        /**
         * @var acm
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var mysqli
         */
        private $database;

        /**
         * @var AccessRecordManager
         */
        private $AccessKeyManager;

        /**
         * @var RequestRecordManager
         */
        private $RequestRecordManager;

        /**
         * IntellivoidAPI constructor.
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'Intellivoid Accounts');

            try
            {
                $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            }
            catch (Exception $e)
            {
                print("There was an error while trying to parse the ACM configuration");
                print($e->getMessage());
                exit(0);
            }

            $this->database = new mysqli(
                $this->DatabaseConfiguration['Host'],
                $this->DatabaseConfiguration['Username'],
                $this->DatabaseConfiguration['Password'],
                $this->DatabaseConfiguration['Name'],
                $this->DatabaseConfiguration['Port']
            );

            $this->AccessKeyManager = new AccessRecordManager($this);
            $this->RequestRecordManager = new RequestRecordManager($this);
        }

        /**
         * @return acm
         */
        public function getAcm()
        {
            return $this->acm;
        }

        /**
         * @return mixed
         */
        public function getDatabaseConfiguration()
        {
            return $this->DatabaseConfiguration;
        }

        /**
         * @return mysqli
         */
        public function getDatabase()
        {
            return $this->database;
        }

        /**
         * @return AccessRecordManager
         */
        public function getAccessKeyManager()
        {
            return $this->AccessKeyManager;
        }

        /**
         * @return RequestRecordManager
         */
        public function getRequestRecordManager(): RequestRecordManager
        {
            return $this->RequestRecordManager;
        }

    }
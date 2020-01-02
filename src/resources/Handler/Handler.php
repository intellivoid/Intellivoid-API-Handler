<?php


    namespace Handler;

    // Define the directory locations
    use Exception;
    use Handler\Abstracts\Module;
    use Handler\GenericResponses\InternalServerError;
    use Handler\GenericResponses\ModuleListingResponse;
    use Handler\GenericResponses\ResourceNotAvailable;
    use Handler\GenericResponses\ResourceNotFound;
    use Handler\GenericResponses\Root;
    use Handler\GenericResponses\UnauthorizedResponse;
    use Handler\GenericResponses\UnsupportedVersion;
    use Handler\Objects\Library;
    use Handler\Objects\MainConfiguration;
    use Handler\Objects\ModuleConfiguration;
    use Handler\Objects\VersionConfiguration;
    use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
    use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
    use IntellivoidAPI\Exceptions\DatabaseException;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\IntellivoidAPI;
    use IntellivoidAPI\Objects\AccessRecord;
    use IntellivoidAPI\Objects\RequestRecordEntry;

    define("HANDLER_DIRECTORY", __DIR__, false);
    define("LIBRARIES_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'libraries', false);
    define("MODULES_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'modules', false);

    // Define the file locations
    define("CONFIGURATION_FILE", __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configuration.json', false);

    // Auto-Include the core files
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'Response.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'Module.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'InternalServerError.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'InvalidUserAgentResponse.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'ModuleListingResponse.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'ResourceNotAvailable.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'ResourceNotFound.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'Root.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'UnauthorizedResponse.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'GenericResponses' . DIRECTORY_SEPARATOR . 'UnsupportedVersion.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'Library.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'MainConfiguration.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'ModuleConfiguration.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'VersionConfiguration.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Router.php');

    // Load Intellivoid API
    require_once(LIBRARIES_DIRECTORY . DIRECTORY_SEPARATOR . 'IntellivoidAPI' . DIRECTORY_SEPARATOR . 'IntellivoidAPI.php');

    /**
     * Class Handler
     * @package Handler
     */
    class Handler
    {
        /**
         * The configuration data which is stored in memory for better performance
         *
         * @var array
         */
        public static $ConfigurationData;

        /**
         * Main configuration object
         *
         * @var MainConfiguration
         */
        public static $MainConfiguration;

        /**
         * Path routes for versions
         *
         * @var array
         */
        public static $PathRoutes;

        /**
         * The HTTP router
         *
         * @var Router
         */
        public static $Router;

        /**
         * Intellivoid API Object, can be null if not initialized
         *
         * @var IntellivoidAPI
         */
        private static $IntellivoidAPI;

        /**
         * The beginning of the timer to determine the execution time
         *
         * @var float
         */
        private static $TimerBegin;

        /**
         * Loads the local configuration to memory
         *
         * @throws Exception
         */
        private static function loadConfigurationFile()
        {
            if(self::$ConfigurationData == null)
            {
                $file_contents = file_get_contents(CONFIGURATION_FILE);
                $json_data = json_decode($file_contents, true);

                if(empty($json_data))
                {
                    throw new Exception("Invalid JSON data in the configuration file");
                }

                self::$ConfigurationData = $json_data;
            }
        }

        /**
         * Creates a route for the root
         *
         * @throws Exception
         */
        private static function createRootRoute()
        {
            self::$Router->map('GET|POST', '/', function(){
                Root::executeResponse();
                exit();
            });
        }

        /**
         * Gets GET/POST Parameters combined, this can be altered.
         * POST Parameters can override GET Parameters
         *
         * @param bool $get
         * @param bool $post
         * @return array
         */
        private static function getParameters(bool $get=true, bool $post=true): array
        {
            $parameters = array();

            if($get)
            {
                foreach($_GET as $value => $item)
                {
                    $parameters[$value] = $item;
                }
            }

            if($post)
            {
                foreach($_POST as $value => $item)
                {
                    $parameters[$value] = $item;
                }
            }

            return $parameters;
        }

        /**
         * Authenticates the user and returns the Access Record once authenticated
         *
         * @return AccessRecord
         */
        public static function authenticateUser(): AccessRecord
        {
            $AccessKey = null;

            if(isset(self::getParameters()['access_key']))
            {
                $AccessKey = self::getParameters()['access_key'];
            }

            if($AccessKey == null)
            {
                if (isset($_SERVER['PHP_AUTH_USER']) == false)
                {
                    UnauthorizedResponse::executeResponse();
                    exit();
                }
                else
                {
                    $AccessKey = $_SERVER['PHP_AUTH_PW'];
                }
            }

            try
            {
                $AccessRecord = self::getIntellivoidAPI()->getAccessKeyManager()->getAccessRecord(
                    AccessRecordSearchMethod::byAccessKey, $AccessKey
                );
            }
            catch (AccessRecordNotFoundException $e)
            {
                UnauthorizedResponse::executeResponse();
                exit();
            }
            catch(Exception $e)
            {
                InternalServerError::executeResponse($e);
                exit();
            }

            return $AccessRecord;
        }

        /**
         * Creates a route for the version
         *
         * @throws Exception
         */
        private static function createVersionRoute()
        {
            self::$Router->map('GET|POST', '/[a:version]', function(string $version){
                if(isset(Handler::$MainConfiguration->VersionConfigurations[$version]) == false)
                {
                    UnsupportedVersion::executeResponse();
                    exit();
                }
                else
                {
                    /** @var VersionConfiguration $VersionConfiguration */
                    $VersionConfiguration = Handler::$MainConfiguration->VersionConfigurations[$version];

                    $Modules = array();

                    /** @var ModuleConfiguration $module */
                    foreach($VersionConfiguration->Modules as $module)
                    {
                        $ModuleObject = self::getModuleObject($version, $module);

                        $Modules['/' . $module->Path] = array(
                            'name' => $ModuleObject->name,
                            'version' => $ModuleObject->version,
                            'description' => $ModuleObject->description
                        );
                    }

                    ModuleListingResponse::executeResponse($Modules);
                    exit();
                }
            });
        }

        /** @noinspection DuplicatedCode */
        /**
         * Constructs the module object from a module configuration
         *
         * @param string $version
         * @param ModuleConfiguration $moduleConfiguration
         * @return Module
         * @throws Exception
         */
        public static function getModuleObject(string $version, ModuleConfiguration $moduleConfiguration): Module
        {
            $VersionDirectory = MODULES_DIRECTORY . DIRECTORY_SEPARATOR . $version;
            $ScriptPath = $VersionDirectory . DIRECTORY_SEPARATOR . $moduleConfiguration->Script . '.php';

            if(file_exists($VersionDirectory) == false)
            {
                throw new Exception("The version directory '" . $version . "' was not found");
            }

            if(file_exists($ScriptPath) == false)
            {
                throw new Exception("The script '" . $moduleConfiguration->Path . "' was not found");
            }

            require_once($ScriptPath);

            $script_namespace = 'modules\\' .  $version . '\\' . $moduleConfiguration->Script;

            /** @var Module $module_object */
            $module_object = new $script_namespace();

            return $module_object;
        }

        /**
         * Processes the request of the module and returns the response
         *
         * @param Module $module
         */
        public static function processModuleResponse(Module $module)
        {
            // Process the request
            try
            {
                $module->processRequest();
            }
            catch(Exception $exception)
            {
                InternalServerError::executeResponse($exception);
                exit();
            }

            header('Content-Type: ' . $module->getContentType());
            header('Content-Size: ' . $module->getContentLength());

            // Create the response
            if($module->isFile())
            {
                header("Content-disposition: attachment; filename=\"" . basename($module->getFileName()) . "\"");
            }

            print($module->getBodyContent());
        }

        /**
         * Starts the timer to determine the execution time of the request
         *
         * @return bool
         */
        private static function startTimer(): bool
        {
            self::$TimerBegin = microtime(true);
            return true;
        }

        /**
         * Stops the timer and returns the total execution time of the request
         *
         * @return float
         */
        private static function stopTimer(): float
        {
            return (float)(microtime(true) - self::$TimerBegin);
        }

        private static function logRequest(AccessRecord $accessRecord, string $version, Module $module, float $response_time)
        {
            $IntellivoidAPI = self::getIntellivoidAPI();

            $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

            $RequestRecordEntry = new RequestRecordEntry();
            $RequestRecordEntry->ApplicationID = (int)$accessRecord->ApplicationID;
            $RequestRecordEntry->AccessRecordID = (int)$accessRecord->ID;
            $RequestRecordEntry->Path = $uri_parts[0];
            $RequestRecordEntry->Version = $version;
            $RequestRecordEntry->ResponseContentType = $module->getContentType();
            $RequestRecordEntry->ResponseLength = (int)$module->getContentLength();
            $RequestRecordEntry->ResponseTime = (float)$response_time;
            $RequestRecordEntry->ResponseCode = $module->getResponseCode();
            $RequestRecordEntry->UserAgent =

            //$IntellivoidAPI->getRequestRecordManager()->logRecord(
            //)
        }

        private static function verifyRequest()
        {
            if(isset($_SERVER['HTTP_USER_AGENT']) == false)
            {

            }
        }

        /**
         * Creates a route for the module
         *
         * @throws Exception
         */
        private static function createModuleRoute()
        {
            self::$Router->map('GET|POST', '/[a:version]/[**:path]', function(string $version, string $path){
                $version = strtolower($version);
                $path = strtolower($path);

                if(isset(self::$PathRoutes[$version][$path]))
                {
                    /** @var VersionConfiguration $VersionConfiguration */
                    $VersionConfiguration = self::$MainConfiguration->VersionConfigurations[$version];

                    /** @var ModuleConfiguration $ModuleConfiguration */
                    $ModuleConfiguration = $VersionConfiguration->Modules[self::$PathRoutes[$version][$path]];

                    if($VersionConfiguration->Available == false)
                    {
                        ResourceNotAvailable::executeResponse($VersionConfiguration->UnavailableMessage);
                        exit();
                    }

                    if($ModuleConfiguration->Available == false)
                    {
                        ResourceNotAvailable::executeResponse($ModuleConfiguration->UnavailableMessage);
                        exit();
                    }

                    $AccessRecord = new AccessRecord();
                    $AccessRecord->ID = 0;

                    if($ModuleConfiguration->AuthenticationRequired)
                    {
                        $AccessRecord = self::authenticateUser();
                    }

                    /** @var Module $ModuleObject */
                    $ModuleObject = self::getModuleObject($version, $ModuleConfiguration);
                    $ModuleObject->access_record = $AccessRecord;
                    self::processModuleResponse($ModuleObject);
                    exit();
                }
                else
                {
                    ResourceNotFound::executeResponse();
                    exit();
                }

            });
        }

        /**
         * Loads the configuration and handles the routes
         *
         * @throws Exception
         */
        public static function handle()
        {
            /** @noinspection PhpUnhandledExceptionInspection */
            self::loadConfigurationFile();
            self::$MainConfiguration = MainConfiguration::fromArray(self::$ConfigurationData);
            self::$Router = new Router();
            self::$Router->setBasePath(self::$MainConfiguration->BasePath);
            self::$PathRoutes = [];

            // Create routes
            self::createRootRoute();
            self::createVersionRoute();
            self::createModuleRoute();

            // Load module and version paths
            foreach(self::$MainConfiguration->VersionConfigurations as $versionConfiguration)
            {
                self::$MainConfiguration->VersionConfigurations[$versionConfiguration->Version] = $versionConfiguration;
                self::$PathRoutes[$versionConfiguration->Version] = [];

                /** @var Library $library */
                foreach($versionConfiguration->Libraries as $library)
                {
                    $library->import();
                }

                /** @var ModuleConfiguration $module */
                foreach($versionConfiguration->Modules as $module)
                {
                    self::$PathRoutes[strtolower($versionConfiguration->Version)][strtolower($module->Path)] = $module->Script;
                }
            }
        }

        /**
         * Returns the IntellivoidAPI, if not constructed it will construct it
         *
         * @return IntellivoidAPI
         */
        public static function getIntellivoidAPI(): IntellivoidAPI
        {
            if(self::$IntellivoidAPI == null)
            {
                self::$IntellivoidAPI = new IntellivoidAPI();
            }

            return self::$IntellivoidAPI;
        }
    }
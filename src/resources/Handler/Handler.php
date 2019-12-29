<?php


    namespace Handler;

    // Define the directory locations
    use Exception;
    use Handler\Abstracts\Module;
    use Handler\Objects\Library;
    use Handler\Objects\MainConfiguration;
    use Handler\Objects\ModuleConfiguration;
    use Handler\Objects\VersionConfiguration;

    define("HANDLER_DIRECTORY", __DIR__, false);
    define("LIBRARIES_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'libraries', false);
    define("MODULES_DIRECTORY", __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'modules', false);

    // Define the file locations
    define("CONFIGURATION_FILE", __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configuration.json', false);

    // Auto-Include the core files
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'Response.php');
    require_once(HANDLER_DIRECTORY . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'Module.php');
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
        private static $ConfigurationData;

        /**
         * Main configuration object
         *
         * @var MainConfiguration
         */
        private static $MainConfiguration;

        /**
         * Path routes for versions
         *
         * @var array
         */
        private static $PathRoutes;

        /**
         * The HTTP router
         *
         * @var Router
         */
        public static $Router;

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

        private static function createRootRoute()
        {
            self::$Router->map('GET|POST', '/', function(){
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
                exit();
            });
        }

        private static function createVersionRoute()
        {
            self::$Router->map('GET|POST', '/[a:version]', function(string $version){
                if(isset(Handler::$MainConfiguration->VersionConfigurations[$version]) == false)
                {
                    $ResponsePayload = array(
                        'success' => false,
                        'response_code' => 404,
                        'error' => array(
                            'error_code' => 0,
                            'type' => "SERVER",
                            "message" => "The given version for this API is unsupported"
                        ),
                        'reference_code' => null
                    );
                    $ResponseBody = json_encode($ResponsePayload);

                    http_response_code(200);
                    header('Content-Type: application/json');
                    header('Content-Size: ' . strlen($ResponseBody));
                    print($ResponseBody);
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
                        $VersionDirectory = MODULES_DIRECTORY . DIRECTORY_SEPARATOR . $version;
                        $ScriptPath = $VersionDirectory . DIRECTORY_SEPARATOR . $module->Script . '.php';

                        if(file_exists($VersionDirectory) == false)
                        {
                            throw new Exception("The version directory '" . $version . "' was not found");
                        }

                        if(file_exists($ScriptPath) == false)
                        {
                            throw new Exception("The script '" . $module->Path . "' was not found");
                        }

                        require_once($ScriptPath);

                        $script_namespace = 'modules\\' .  $version . '\\' . $module->Script;
                        $module_class = new $script_namespace();

                        $Modules['/' . $module->Path] = array(
                            'name' => $module_class->name,
                            'version' => $module_class->version,
                            'description' => $module_class->description
                        );
                    }

                    /** @noinspection DuplicatedCode */
                    $ResponsePayload = array(
                        'success' => true,
                        'response_code' => 200,
                        'modules' => $Modules,
                        'reference_code' => null
                    );
                    $ResponseBody = json_encode($ResponsePayload);

                    http_response_code(200);
                    header('Content-Type: application/json');
                    header('Content-Size: ' . strlen($ResponseBody));
                    print($ResponseBody);
                    exit();
                }
            });
        }

        private static function createModuleRoute()
        {
            self::$Router->map('GET|POST', '/[a:version]/[**:path]', function(string $version, string $path){
                $version = strtolower($version);
                $path = strtolower($path);

                if(isset(self::$PathRoutes[$version][$path]))
                {
                    print(self::$PathRoutes[$version][$path]);
                }
                else
                {
                    print("Not found");
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
    }
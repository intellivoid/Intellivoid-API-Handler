<?php


    namespace Handler\Objects;

    /**
     * Class MainConfiguration
     * @package Handler\Objects
     */
    class MainConfiguration
    {
        /**
         * The name of the API service
         *
         * @var string
         */
        public $Name;

        /**
         * The URL of the documentation for this API service
         *
         * @var string
         */
        public $DocumentationUrl;

        /**
         * The base path that this API is using
         *
         * Default = '/'
         *
         * @var string
         */
        public $BasePath;

        /**
         * Indicates if the API is available or not
         *
         * @var bool
         */
        public $Available;

        /**
         * The message to display
         *
         * @var string
         */
        public $UnavailableMessage;

        /**
         * If enabled, debugging information about the exception would be displayed
         *
         * @var bool
         */
        public $DebugExceptions;

        /**
         * Array of configurations for versions
         *
         * @var array
         */
        public $VersionConfigurations;

        /**
         * Returns an array which represents this object
         *
         * @return VersionConfiguration[]
         */
        public function toArray(): array
        {
            $VersionConfigurations = array();

            /** @var VersionConfiguration $versionConfiguration */
            foreach($this->VersionConfigurations as $versionNumber => $versionConfiguration)
            {
                $VersionConfigurations[] = $versionConfiguration->toArray();
            }

            return array(
                'NAME' => $this->Name,
                'DOCUMENTATION_URL' => $this->DocumentationUrl,
                'BASE_PATH' => $this->BasePath,
                'AVAILABLE' => (bool)$this->Available,
                'UNAVAILABLE_MESSAGE' => $this->UnavailableMessage,
                'DEBUG_EXCEPTIONS' => (bool)$this->DebugExceptions,
                'VERSION_CONFIGURATIONS' => $VersionConfigurations
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return MainConfiguration
         */
        public static function fromArray(array $data): MainConfiguration
        {
            $MainConfigurationObject = new MainConfiguration();

            if(isset($data['NAME']))
            {
                $MainConfigurationObject->Name = $data['NAME'];
            }

            if(isset($data['DOCUMENTATION_URL']))
            {
                $MainConfigurationObject->DocumentationUrl = $data['DOCUMENTATION_URL'];
            }

            if(isset($data['BASE_PATH']))
            {
                $MainConfigurationObject->BasePath = $data['BASE_PATH'];
            }

            if(isset($data['AVAILABLE']))
            {
                $MainConfigurationObject->Available = (bool)$data['AVAILABLE'];
            }

            if(isset($data['UNAVAILABLE_MESSAGE']))
            {
                $MainConfigurationObject->UnavailableMessage = $data['UNAVAILABLE_MESSAGE'];
            }

            if(isset($data['DEBUG_EXCEPTIONS']))
            {
                $MainConfigurationObject->DebugExceptions = (bool)$data['DEBUG_EXCEPTIONS'];
            }

            if(isset($data['VERSION_CONFIGURATIONS']))
            {
                $MainConfigurationObject->VersionConfigurations = array();

                foreach($data['VERSION_CONFIGURATIONS'] as $VERSION_CONFIGURATION)
                {
                    $MainConfigurationObject->VersionConfigurations[$VERSION_CONFIGURATION['VERSION']] = VersionConfiguration::fromArray($VERSION_CONFIGURATION);
                }
            }

            return $MainConfigurationObject;
        }
    }
<?php


    namespace Handler\Objects;

    /**
     * Class ModuleConfiguration
     * @package Handler\Objects
     */
    class ModuleConfiguration
    {
        /**
         * The script of the module
         *
         * @var string
         */
        public $Script;

        /**
         * The URL path for the module
         *
         * @var string
         */
        public $Path;

        /**
         * Indicates if this module is available or not
         *
         * @var bool
         */
        public $Available;

        /**
         * The message to display when this module is not available
         *
         * @var string
         */
        public $UnavailableMessage;

        /**
         * Indicates if this module requires authentication
         *
         * @var bool
         */
        public $AuthenticationRequired;

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'script' => $this->Script,
                'path' => $this->Path,
                'available' => (bool)$this->Available,
                'unavailable_message' => $this->UnavailableMessage,
                'authentication_required' => (bool)$this->AuthenticationRequired
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return ModuleConfiguration
         */
        public static function fromArray(array $data): ModuleConfiguration
        {
            $ModuleConfigurationObject = new ModuleConfiguration();

            if(isset($data['script']))
            {
                $ModuleConfigurationObject->Script = $data['script'];
            }

            if(isset($data['path']))
            {
                $ModuleConfigurationObject->Path = $data['path'];
            }

            if(isset($data['available']))
            {
                $ModuleConfigurationObject->Available = (bool)$data['available'];
            }

            if(isset($data['unavailable_message']))
            {
                $ModuleConfigurationObject->UnavailableMessage = $data['unavailable_message'];
            }

            if(isset($data['authentication_required']))
            {
                $ModuleConfigurationObject->AuthenticationRequired = (bool)$data['authentication_required'];
            }

            return $ModuleConfigurationObject;
        }
    }
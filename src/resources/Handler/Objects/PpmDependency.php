<?php


    namespace Handler\Objects;


    use Exception;
    use ppm\ppm;

    /**
     * Class PpmDependency
     * @package Handler\Objects
     */
    class PpmDependency
    {
        /**
         * The name of the package
         *
         * @var string
         */
        public $PackageName;

        /**
         * The version of the package to import
         *
         * @var string
         */
        public $Version;

        /**
         * Imports the dependencies recursively
         *
         * @var bool
         */
        public $ImportDependencies;

        /**
         * Indicates if an arrow should be thrown when importing the package
         *
         * @var bool
         */
        public $ThrowError;

        /**
         * Imports the library to memory
         *
         * @throws Exception
         */
        public function import()
        {
            if(defined("PPM") == false)
            {
                /** @noinspection PhpIncludeInspection */
                require("ppm");

                if(defined("PPM") == false)
                {
                    throw new Exception("Cannot import PPM, is it installed?");
                }
            }

            ppm::import($this->PackageName, $this->Version, $this->ImportDependencies, $this->ThrowError);
        }

        /**
         * Returns an array which represents this object
         *
         * @param bool $as_object
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "package" => $this->PackageName,
                "version" => $this->Version,
                "import_dependencies" => (bool)$this->ImportDependencies,
                "throw_error" => (bool)$this->ThrowError
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @param string $name
         * @return PpmDependency
         */
        public static function fromArray(array $data): PpmDependency
        {
            $PpmDependencyObject = new PpmDependency();

            if(isset($data["package"]))
            {
                $PpmDependencyObject->PackageName = $data["package"];
            }

            if(isset($data["version"]))
            {
                $PpmDependencyObject->Version = $data["version"];
            }

            if(isset($data["import_dependencies"]))
            {
                $PpmDependencyObject->ImportDependencies = (bool)$data["import_dependencies"];
            }

            if(isset($data["throw_error"]))
            {
                $PpmDependencyObject->ThrowError = (bool)$data["throw_error"];
            }

            return $PpmDependencyObject;
        }
    }
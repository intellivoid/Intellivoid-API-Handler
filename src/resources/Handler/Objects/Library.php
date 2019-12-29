<?php


    namespace Handler\Objects;


    use Exception;

    /**
     * Class Library
     * @package Handler\Objects
     */
    class Library
    {
        /**
         * The name of the library
         *
         * @var string
         */
        public $Name;

        /**
         * The name of the directory that's holding the contents of the library
         *
         * @var string
         */
        public $DirectoryName;

        /**
         * The name of the autoloader library
         *
         * @var string
         */
        public $AutoLoader;

        /**
         * The namespace of the library
         *
         * @var string
         */
        public $Namespace;

        /**
         * The name of the main class that's initialized
         *
         * @var string
         */
        public $MainClass;

        /**
         * Check if the class is already imported before importing it
         *
         * @var bool
         */
        public $CheckExists;

        /**
         * Imports the library to memory
         *
         * @throws Exception
         */
        public function import()
        {
            $LibraryDirectory = LIBRARIES_DIRECTORY . DIRECTORY_SEPARATOR . $this->DirectoryName;
            $AutoLoader = $LibraryDirectory . DIRECTORY_SEPARATOR . $this->AutoLoader;

            if(file_exists($LibraryDirectory) == false)
            {
                throw new Exception("The directory for the library '" . $this->Name . "' was not found");
            }

            if(file_exists($AutoLoader) == false)
            {
                throw new Exception("The file '" . $this->AutoLoader . "' for the library '" . $this->Name . "' was not found");
            }

            if($this->CheckExists)
            {
                if(class_exists($this->Namespace . '\\' . $this->MainClass))
                {
                    return;
                }
            }

            /** @noinspection PhpIncludeInspection */
            include_once($AutoLoader);
        }

        /**
         * Returns an array which represents this object
         *
         * @param bool $as_object
         * @return array
         */
        public function toArray(bool $as_object=false): array
        {
            if($as_object)
            {
                return array(
                    $this->Name => array(
                        'DIRECTORY_NAME' => $this->DirectoryName,
                        'AUTOLOADER' => $this->AutoLoader,
                        'NAMESPACE' => $this->Namespace,
                        'MAIN_CLASS' => $this->MainClass,
                        'CHECK_EXISTS' => $this->CheckExists
                    )
                );
            }
            else
            {
                return array(
                    'DIRECTORY_NAME' => $this->DirectoryName,
                    'AUTOLOADER' => $this->AutoLoader,
                    'NAMESPACE' => $this->Namespace,
                    'MAIN_CLASS' => $this->MainClass,
                    'CHECK_EXISTS' => $this->CheckExists
                );
            }
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @param string $name
         * @return Library
         */
        public static function fromArray(array $data, string $name): Library
        {
            $LibraryObject = new Library();
            $LibraryObject->Name = $name;

            if(isset($data['DIRECTORY_NAME']))
            {
                $LibraryObject->DirectoryName = $data['DIRECTORY_NAME'];
            }

            if(isset($data['AUTOLOADER']))
            {
                $LibraryObject->AutoLoader = $data['AUTOLOADER'];
            }

            if(isset($data['NAMESPACE']))
            {
                $LibraryObject->Namespace = $data['NAMESPACE'];
            }

            if(isset($data['MAIN_CLASS']))
            {
                $LibraryObject->MainClass = $data['MAIN_CLASS'];
            }

            if(isset($data['CHECK_EXISTS']))
            {
                $LibraryObject->CheckExists = (bool)$data['CHECK_EXISTS'];
            }

            return $LibraryObject;
        }
    }
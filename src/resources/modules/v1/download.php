<?php

    namespace modules\v1;

    use Handler\Abstracts\Module;
    use Handler\Interfaces\Response;
    use IntellivoidAPI\Objects\AccessRecord;

    /**
     * Class ping
     */
    class download extends Module implements  Response
    {
        /**
         * The name of the module
         *
         * @var string
         */
        public $name = 'download';

        /**
         * The version of this module
         *
         * @var string
         */
        public $version = '1.0.0.0';

        /**
         * The description of this module
         *
         * @var string
         */
        public $description = "Downloads a text file";

        /**
         * Optional access record for this module
         *
         * @var AccessRecord
         */
        public $access_record;

        /**
         * The content to give on the response
         *
         * @var string
         */
        private $response_content;

        /**
         * @inheritDoc
         */
        public function getContentType(): string
        {
            return 'application/octet-stream';
        }

        /**
         * @inheritDoc
         */
        public function getContentLength(): int
        {
            return strlen($this->response_content);
        }

        /**
         * @inheritDoc
         */
        public function getBodyContent(): string
        {
            return $this->response_content;
        }

        /**
         * @inheritDoc
         */
        public function getResponseCode(): int
        {
            return 200;
        }

        /**
         * @inheritDoc
         */
        public function isFile(): bool
        {
            return true;
        }

        /**
         * @inheritDoc
         */
        public function getFileName(): string
        {
            return time() . '.txt';
        }

        /**
         * @inheritDoc
         */
        public function processRequest()
        {
            $this->response_content = "Hello World";
        }
    }
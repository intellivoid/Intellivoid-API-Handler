<?php

    namespace modules\v1;

    use Exception;
    use Handler\Abstracts\Module;
    use Handler\Interfaces\Response;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\Objects\AccessRecord;

    /**
     * Class error
     */
    class error extends Module implements  Response
    {
        /**
         * The name of the module
         *
         * @var string
         */
        public $name = 'error';

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
        public $description = "Raises an error without catching";

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
            return 'application/json';
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
            return false;
        }

        /**
         * @inheritDoc
         */
        public function getFileName(): string
        {
            return null;
        }

        /**
         * @inheritDoc
         */
        public function processRequest()
        {
            $ResponsePayload = array(
                'success' => true,
                'response_code' => 200,
                'payload' => $this->access_record->toArray(),
                'reference_code' => null
            );

            $this->response_content = json_encode($ResponsePayload);

            throw new InvalidSearchMethodException();

        }
    }
<?php


    namespace Handler\Interfaces;

    /**
     * Response interface
     *
     * Interface Response
     * @package Handler\Interfaces
     */
    interface Response
    {
        /**
         * Returns the content type which is used for the header
         *
         * @return string
         */
        public function getContentType(): string;

        /**
         * Returns the content length
         *
         * @return int
         */
        public function getContentLength(): int;

        /**
         * Returns the body content
         *
         * @return string
         */
        public function getBodyContent(): string;

        /**
         * Returns the HTTP response code
         *
         * @return int
         */
        public function getResponseCode(): int;

        /**
         * Indicates if the response is a file download
         *
         * @return bool
         */
        public function isFile(): bool;

        /**
         * Returns the file name if the response is a file download
         *
         * @return string
         */
        public function getFileName(): string;

        /**
         * Main execution point, it processes the request before it determines the values for this request
         *
         * @return mixed
         */
        public function processRequest();
    }
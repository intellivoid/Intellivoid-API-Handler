<?php


    namespace IntellivoidAPI\Exceptions;


    use Exception;

    /**
     * Class RateLimitExceededException
     * @package IntellivoidAPI\Exceptions
     */
    class RateLimitExceededException extends Exception
    {
        /**
         * @var int
         */
        private $secondsLeft;

        /**
         * @var int
         */
        private $maxState;

        /**
         * RateLimitExceededException constructor.
         * @param int $time_left
         * @param int $max_state
         */
        public function __construct(int $time_left, int $max_state)
        {
            $this->secondsLeft = $time_left;
            $this->maxState = $max_state;
            parent::__construct("The rate limit has been exceeded", 0, null);
        }

        /**
         * @return int
         */
        public function getSecondsLeft(): int
        {
            return $this->secondsLeft;
        }

        /**
         * @return int
         */
        public function getMaxState(): int
        {
            return $this->maxState;
        }
    }
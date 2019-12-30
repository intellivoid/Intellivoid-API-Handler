<?php


    namespace IntellivoidAPI\Objects\RateLimitTypes;


    use IntellivoidAPI\Exceptions\RateLimitExceededException;

    /**
     * Class IntervalLimit Configuration
     * @package IntellivoidAPI\Objects\RateLimitTypes
     */
    class IntervalLimit
    {
        /**
         * The interval limit
         *
         * @var int
         */
        public $Interval;

        /**
         * The next interval that the state will reset
         *
         * @var int
         */
        public $NextInterval;

        /**
         * The current state of the limit
         *
         * @var int
         */
        public $CurrentState;

        /**
         * The max state that the current state can achieve
         *
         * @var int
         */
        public $MaxState;

        /**
         * Processes the rate limit and throws an exception if the limit has exceeded
         *
         * @return bool
         * @throws RateLimitExceededException
         */
        public function process(): bool
        {
            if((int)time() > $this->NextInterval)
            {
                $this->NextInterval = (int)time() + $this->Interval;
                $this->CurrentState = 0;
            }

            if($this->MaxState == $this->CurrentState)
            {
                throw new RateLimitExceededException($this->NextInterval - (int)time(), $this->$this->MaxState);
            }

            $this->CurrentState += 1;
            return true;
        }

        /**
         * Creates a interval limit object
         *
         * @param int $Interval
         * @param int $max_state
         * @return IntervalLimit
         */
        public static function create(int $Interval, int $max_state): IntervalLimit
        {
            $IntervalLimitObject = new IntervalLimit();

            $IntervalLimitObject->Interval = $Interval;
            $IntervalLimitObject->NextInterval = (int)time() + $Interval;
            $IntervalLimitObject->CurrentState = 0;
            $IntervalLimitObject->MaxState = $max_state;

            return $IntervalLimitObject;
        }

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'interval' => (int)$this->Interval,
                'next_interval' => (int)$this->NextInterval,
                'current_state' => (int)$this->CurrentState,
                'max_state' => (int)$this->MaxState
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return IntervalLimit
         */
        public static function fromArray(array $data): IntervalLimit
        {
            $IntervalLimitObject = new IntervalLimit();

            if(isset($data['interval']))
            {
                $IntervalLimitObject->Interval = (int)$data['interval'];
            }

            if(isset($data['next_interval']))
            {
                $IntervalLimitObject->NextInterval = (int)$data['next_interval'];
            }

            if(isset($data['current_state']))
            {
                $IntervalLimitObject->CurrentState = (int)$data['current_state'];
            }

            if(isset($data['max_state']))
            {
                $IntervalLimitObject->CurrentState = (int)$data['max_state'];
            }

            return $IntervalLimitObject;
        }

    }
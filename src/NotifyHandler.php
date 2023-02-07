<?php
    namespace Patienceman\Notifier;

    use Patienceman\Notifier\Traits\DatabaseNotifier;
    use Patienceman\Notifier\Traits\DelegatesToResource;
    use Illuminate\Notifications\Notification;

    abstract class NotifyHandler extends Notification {
        use DelegatesToResource,
            DatabaseNotifier;

        /**
         * Instance resource holder
         * @var object
         */
        protected $resource;

        /**
         * does notifier queueable
         * @var bool
         */
        public $queueuable = false;

        /**
         * does notifier queueable
         * @var bool
         */
        public $notifiables;

        /**
         * Class constructor.
         */
        public function __construct(array $payload = []) {
            $this->resource = (Object) $payload;
        }

        /**
         * Execute notification
         * @return mixed
         */
        abstract public function handle();

        /**
         * Set the notification Payload
         * @return NotifyHandler
         */
        public static function process(array $payload = []){
            return new static($payload);
        }

        /**
         * Register single queue instance
         * @return NotifyHandler
         */
        public function onQueue() {
            $this->queueuable = true;
            return $this;
        }

        /**
         * Register single queue instance
         * @return NotifyHandler
         */
        public function users($users) {
            $this->notifiables = $users;
            return $this;
        }

        /**
         * Register single queue instance
         * @return mixed
         */
        public function recipients() :mixed {
            return $this->notifiables;
        }

        /**
         * Format user params
         *
         * @param array $users
         * @return array
         */
        public function assocUsers($users) {
            foreach ($users as $key => $param) {
                $formatted['user_'.$key+1] = $param;
            }
            return $formatted;
        }

        /**
         * Perfom some action to all users
         *
         * @param callable $handler
         * @return void
         */
        public function foreachUser(callable $handler) {
            foreach($this->recipients() as $reciever){
                $handler($reciever);
            }
        }
    }

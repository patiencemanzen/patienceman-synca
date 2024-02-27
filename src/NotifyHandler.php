<?php
    namespace Patienceman\Synca;

    use Exception;
    use Patienceman\Synca\Traits\DatabaseNotifier;
    use Patienceman\Synca\Traits\DelegatesToResource;
    use Patienceman\Synca\Traits\NotifyPayload;

    abstract class NotifyHandler {
        use DelegatesToResource,
            DatabaseNotifier,
            NotifyPayload;

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
         *
         * @return NotifyHandler
         */
        public function users(array $users) {
            if($users) {
                foreach ($users as $key => $param) {
                    $this->offsetSet($key, $param);
                }
                $this->notifiables = $users;
            }

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
         * Perfom some action to all users
         *
         * @param callable $handler
         * @return void
         */
        public function foreachUser(callable $handler) {
            if ($this->recipients()) {
                foreach($this->recipients() as $reciever) {
                    $handler($reciever);
                }
            }
        }

        public function sendToDatabase($notifiable) {
            if(!$notifiable) throw new Exception('Unable to find notifiable');
            $data = $this->toDatabase($notifiable);

            return $notifiable->routeNotificationFor('database')->create([
                'id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 15),
                'type' => (new \ReflectionClass($this))->getNamespaceName(),
                'notifiable_type' => (new \ReflectionClass($notifiable))->getNamespaceName(),
                'notifiable_id' => $notifiable->id,
                'data' => $data,
                'read_at' => null,
            ]);
        }
    }

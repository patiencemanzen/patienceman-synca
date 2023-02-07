<?php
    namespace Patienceman\Notifier;

    class Notifier {
        /**
         * All notification handlers
         * @var array
         */
        protected $notifers = [];

        /**
         * Does all queueable
         */
        public bool $onQueue = false;

        /**
         * set on queue status on
         * @return Notifier
         */
        public function onQueue() {
            $this->onQueue = true;
            return $this;
        }

        /**
         * Send custom notification from notification handler
         * @param mixed notification
         */
        public function send(mixed $notification) {
            return $this->resolveNotifiers(...func_get_args())->dispatch();
        }

        /**
         * Resolve and register notifications
         *
         * @param Notification Notification
         * @return Notifier
         */
        public function resolveNotifiers($notifications) {
            if(is_array($notifications)){
                foreach($notifications as $notification)
                    if($notification instanceof NotifyHandler)
                        array_push($this->notifers, $notification);
            }else{
                if($notifications instanceof NotifyHandler)
                    array_push($this->notifers, $notifications);
            }

            return $this;
        }

        /**
         * Dispatch the notifications
         * @return void
         */
        public function dispatch() {
            ($this->onQueue)
                ? dispatch(new NotifyQueuer($this->notifers))
                : $this->directCall();
        }

        /**
         * Call each notifiers
         *
         * @param Notification Notification
         * @return void
         */
        public function directCall() {
            foreach($this->notifers as $notifier){
                ($notifier->queueuable)
                    ? dispatch(new NotifyQueuer($notifier))
                    : $notifier->handle();
            }
        }
    }

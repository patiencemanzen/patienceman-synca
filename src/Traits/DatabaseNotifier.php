<?php
    namespace Patienceman\Synca\Traits;

    use Exception;
    use Patienceman\Synca\NotifyHandler;

    trait DatabaseNotifier {
        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed  $notifiable
         * @return array
         */
        public function via($notifiable) {
            return [NotifyHandler::class];
        }

        /**
         * Send the given notification.
         *
         * @param  mixed  $notifiable
         * @param  \Illuminate\Notifications\Notification  $notification
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function dbNotification($notifiable, callable $notification) {
            if(!$notifiable) throw new Exception('Unable to find notifiable');

            return $notifiable->routeNotificationFor('database')->create([
                'id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 15),
                'type' => (new \ReflectionClass($this))->getNamespaceName(),
                'notifiable_type' => (new \ReflectionClass($notifiable))->getNamespaceName(),
                'notifiable_id' => $notifiable->id,
                'data' => $notification($notifiable),
                'read_at' => null,
            ]);
        }
    }

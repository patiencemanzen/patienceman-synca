<?php

    namespace Patienceman\Notifier;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Patienceman\Notifier\NotifyHandler;

    class NotifyQueuer implements ShouldQueue {
        use Dispatchable,
            InteractsWithQueue,
            Queueable,
            SerializesModels;

        /**
         * @var array|NotifyHandler notifiers
         */
        protected array|NotifyHandler $notifiers;

        /**
         * Create a new job instance.
         *
         * @return void
         */
        public function __construct(array|NotifyHandler $notifier) {
            $this->notifiers = $notifier;
        }

        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle() {
            if(is_array($this->notifiers)):
                foreach($this->notifiers as $notifier)
                    $notifier->handle();
            else:
                $this->notifiers->handle();
            endif;
        }
    }

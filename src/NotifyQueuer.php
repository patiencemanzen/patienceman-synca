<?php

    namespace Patienceman\Synca;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Patienceman\Synca\NotifyHandler;

    class NotifyQueuer implements ShouldQueue, ShouldBeUniqueUntilProcessing {
        use Dispatchable,
            InteractsWithQueue,
            Queueable,
            SerializesModels;

        /**
         * @var array|NotifyHandler notifiers
         */
        protected array|NotifyHandler $notifiers;

        /**
         * The number of seconds after which the job's unique lock will be released.
         *
         * @var int
         */
        public $uniqueFor = 3600;

        /**
         * The unique ID of the job.
         *
         * @return string
         */
        public function uniqueId() {
            return uniqid('patienceman_cron_job_', true);
        }

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

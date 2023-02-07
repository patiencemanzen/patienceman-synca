<?php

    namespace Patienceman\Notifier;

    use Illuminate\Support\ServiceProvider;
    use Patienceman\Notifier\Console\InstallNotifierCommand;

    final class NotifierServiceProvider extends ServiceProvider {
        /**
         * Register services.
         *
         * @return void
         */
        public function register() {

        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot(): void {
            if ($this->app->runningInConsole()) {
                $this->commands([
                    InstallNotifierCommand::class
                ]);
            }
        }
    }

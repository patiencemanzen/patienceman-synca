<?php

    namespace Patienceman\Synca;

    use Illuminate\Support\ServiceProvider;
    use Patienceman\Synca\Console\InstallNotifierCommand;
    use Illuminate\Foundation\AliasLoader;

    final class NotifierServiceProvider extends ServiceProvider {
        /**
         * Register services.
         *
         * @return void
         */
        public function register() {
            $this->app->singleton('Notifier', function () {
                return new Notifier();
            });

            // Register the facade
            $loader = AliasLoader::getInstance();
            $loader->alias('Notifier', \Patienceman\Synca\Facades\Notifier::class);
        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot(): void {
            if ($this->app->runningInConsole()) {
                $this->commands([ InstallNotifierCommand::class ]);
            }
        }
    }

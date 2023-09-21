<?php
    namespace Patienceman\Synca\Facades;

    use Illuminate\Support\Facades\Facade;

    class Notifier extends Facade {
        protected static function getFacadeAccessor(){
            return 'Notifier';
        }
    }

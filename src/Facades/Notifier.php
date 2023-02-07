<?php
    namespace Patienceman\Notifier\Facades;

    use Illuminate\Support\Facades\Facade;

    class Notifier extends Facade {
        protected static function getFacadeAccessor(){
            return 'Notifier';
        }
    }

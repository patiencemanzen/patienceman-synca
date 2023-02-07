<?php
    namespace Patienceman\Notifier\Traits;

    trait NotifyPayload {
        /**
         * Get all packed data
         */
        public function payload() :object {
            return $this->resource;
        }

        /**
         * Get all packed data in array format
         * @return array
         */
        public function toArray() :array {
            return (Array) $this->resource;
        }
    }

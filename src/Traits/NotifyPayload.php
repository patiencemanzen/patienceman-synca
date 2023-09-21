<?php
    namespace Patienceman\Synca\Traits;

    trait NotifyPayload {
        /**
         * Get all packed data
         */
        public function payload() :object {
            return (Object) array_diff_key(
                (Array) $this->resource,
                $this->notifiables
            );
        }

        /**
         * Get all packed data in array format
         * @return array
         */
        public function payloadAsArray() :array {
            return (Array) $this->payload();
        }
    }

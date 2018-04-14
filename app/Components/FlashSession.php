<?php

    namespace App\Components;

    /**
     * Class for handling alerts.
     */
    class FlashSession {

        /**
         * Function to determine the alerts.
         */
        public static function render() {
            // Define the request:
            $request = app(\Illuminate\Http\Request::class);
            // Define the flash:
            $flash = $request->session()->exists('flashSession') ? $request->session()->get('flashSession') : array();
            // Define the keys of the array:
            $types = array_keys($flash);
            // Define an array for the result:
            $result = array();
            // Loop trough all the flashes:
            foreach ($types as $type) {
                // Loop trough the messages in the type:
                foreach($flash[$type] as $message) {
                    // Determine the alert:
                    $alert = static::getAlert($type, $message);
                    // Add the alert to the result:
                    if(!is_null($alert)){
                        $result[] = $alert;
                    }
                }
            }
            // Reset the flash variable:
            $request->session()->put('flashSession', array());
            // Return the result:
            return implode('<br><br>', $result);
        }

        /**
         * Function to determine the alerts.
         * @param $type,            The type of the alert.
         * @param $message,         The message for the alert.
         * @return mixed,           The alert object.
         */
        private static function getAlert($type, $message) {
            // Determine the alert:
            switch($type) {
                case 'success':
                    return '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
                case 'info':
                    return '<div class="alert alert-info alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
                case 'error':
                    return '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
                case 'warning':
                    return '<div class="alert alert-warning alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
                default:
                    return null;
            }
        }

        /**
         * Function to determine the alerts.
         * @param $type,            The type of the alert.
         * @param $message,         The message for the alert.
         */
        public static function addAlert($type, $message){
            // Define the request:
            $request = app(\Illuminate\Http\Request::class);
            // Retrieve the session:
            $flash = $request->session()->get('flashSession');
            // Add the alert:
            $flash[$type][] = $message;
            // Store the session:
            $request->session()->put('flashSession', $flash);
        }
    }

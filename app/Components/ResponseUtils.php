<?php

    namespace App\Components;

    /**
     * Class for handling json responses.
     */
    class ResponseUtils {

        /** STATUS OK */
        const STATUS_OK = 200;
        /** STATUS MISSING PARAMETERS */
        const STATUS_MISSING_PARAMETERS = 502;
        /** STATUS NOT FOUND */
        const STATUS_NOT_FOUND = 404;

        /**
         * Function to send a response.
         * @param int $status,                The status code to send. Default is 'STATUS_OK'.
         * @param array $body,                Assoc array of data to send. Default is none.
         * @param string $content_type,       The content type to serve. Default is 'application/json'.
         * @return array $result,             The result.
         */
        public static function sendResponse($status = self::STATUS_OK, $body = array(), $content_type = 'application/json'){
            // Define the header:
            $status_header = 'HTTP/1.1 ' . self::STATUS_OK . ' ' . self::getStatusCodeMessage($status);
            header($status_header);
            // Define the content type:
            header('Content-type: ' . $content_type);
            // Define the result:
            $result = array(
                'status' => $status,
                'message' => self::getStatusCodeMessage($status),
                'result' => $body,
            );
            // Return the result as json:
            echo json_encode($result);
        }

        /**
         * Function to get the message of the status code.
         * @param $status,          Numeric status.
         * @return string,          String of the message.
         */
        public static function getStatusCodeMessage($status) {
            // Switch statement to get the message:
            switch($status) {
                case self::STATUS_OK:
                    return 'success';
                default:
                    return 'error';
            }
        }
    }

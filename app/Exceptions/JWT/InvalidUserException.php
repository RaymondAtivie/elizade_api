<?php 

    namespace App\Exceptions\JWT;

    class InvalidUserException extends \Exception {
         // Redefine the exception so message isn't optional
        public function __construct($message = null, $code = 0, Exception $previous = null) {
            // if no message was passed
            if(empty($message)){
                // set exception message
                $message = 'Could not attempt jwt authentication, param content is not identifiable.';
            }
            // assign everything to parent class
            parent::__construct($message, $code, $previous);
        }

        // custom string representation of object
        public function __toString() {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }
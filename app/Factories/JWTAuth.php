<?php 

    namespace App\Factories;

    use App\Exceptions\JWT\AttemptParamException;
    use App\Exceptions\JWT\InvalidUserException;
    use App\Exceptions\JWT\TokenException;
    use Illuminate\Support\Facades\Hash;
    use App\Factories\SoapConnect;

    use Illuminate\Http\Request;

    class JWTAuth {

        protected $token;

        protected $user;

        public function attempt($user){

            // get user
            $this->user = $this->getUserFomObject($user);
            // create payload
            $payload = [
                "iss"=> config('jwt.iss'),
                "exp"=> (time() + env("TOKEN_EXPIRE", (60*60*24)) ),
                "iat"=> time(),
                "sub"=> $this->user->id,
            ];
            
            // encode and return token
            $this->token = $this->encode($payload, config('jwt.secret'));

            // return class object to enable method chaining
            return $this;
        }

        public function attemptStaff($user){

            // get user
            $this->user = $this->getUserFomCRM($user);
            // create payload
            $payload = [
                "iss"=> config('jwt.iss'),
                "exp"=> (time() + env("TOKEN_EXPIRE", (60*60*24)) ),
                "iat"=> time(),
                "sub"=> $this->user,
            ];
            
            // encode and return token
            $this->token = $this->encode($payload, config('jwt.secret'));

            // return class object to enable method chaining
            return $this;
        }

        public function parseToken(){
            // set token default to null
            $this->token = null;
            // if token is not in get request
            if(!isset($_GET['token'])){
                if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                    $this->token = $_SERVER['HTTP_AUTHORIZATION'];
                }
            }else{
                $this->token = $_GET['token'];
            }
            // if there was a token in the request
            if($this->token){
                // remove bearer for header based token
                $this->token = str_replace('Bearer ', '', $this->token);
            }
            // return class object to enable method chaining
            return $this;
        }

        public function authenticate(){
            // if no token
            if(!$this->token){
                // return false
                throw new TokenException('token_absent');
            }
            
            // get payload from token
            $payload = $this->decode($this->token);
            
            if($payload->exp < time())
            {
                throw new TokenException('token_expired');
            }
            
            // set user model
            $model = new \App\User;
            
            // get user from payload
            if(!$this->user = $model->where("id", $payload->sub)->first())
            {
                throw new TokenException('invalid_user');
            }

            // return class object to enable method chaining
            return $this;
            
        }

        public function authenticateCRM(){
            // if no token
            if(!$this->token){
                // return false
                throw new TokenException('token_absent');
            }
            
            // get payload from token
            $payload = $this->decode($this->token);
            
            if($payload->exp < time())
            {
                throw new TokenException('token_expired');
            }
            
            // get user from payload
            if(!is_array($payload->sub)){
                throw new TokenException('invalid_user');
            }else{
                $this->user = $payload->sub;
            }

            // return class object to enable method chaining
            return $this;
            
        }

        public function getUser(){
            // return the user
            return $this->user;
        }

        public function getToken(){
            // return token
            return $this->token;
        }

        private function getUserFomCRM($user){
            // instantiate model
            $model = new \App\User;
            // if user object is passed
            if($user instanceof \App\User ){
                // do nothing
            }elseif(is_array($user)){ 
                // else if user is an array
                if(isset($user['username']) && isset($user['password'])){ // if user email and password were supplied
                    $SC = new SoapConnect();

                    $CRMUser = $SC->getStaffDetails($user['username'], $user['password']);
                    
                    if(!$CRMUser){
                        throw new InvalidUserException('Invalid username or password.');
                    }
                    // return user
                    return $CRMUser;
                }else{
                    // throw new invalid user data supplied
                    throw new InvalidUserException;
                }
            }else{
                // throw new attempt param exception
                throw new AttemptParamException;
            }
        }

        private function getUserFomObject($user){
            // instantiate model
            $model = new \App\User;
            // if user object is passed
            if($user instanceof \App\User ){
                // do nothing
            }elseif(is_array($user))
            { // else if user is an array
                if(isset($user['email']) && isset($user['password']))
                { // if user email and password were supplied
                    $uPassword = $user['password'];
                    // find user
                    if(!$user = $model->where('email', $user['email'])
                    ->first())
                    {
                        // invalid login credentials
                        throw new InvalidUserException('Invalid email or password.');
                    }

                    if(!Hash::check($uPassword, $user->password)){
                        throw new InvalidUserException('Invalid email or password.');
                    }
                    // return user
                    return $user;
                }elseif(isset($user['surname']) && isset($user['passport_no']))
                { // else if user surname and passport number were supplied
                    // find user
                    if(!$user = $model->where('surname', $user['surname'])
                    ->where('passport_no', $user['passport_no'])
                    ->first())
                    {
                        // invalid login credentials
                        throw new InvalidUserException('Invalid surname or passport number.');
                    }
                    // return user
                    return $user;
                }else{
                    // throw new invalid user data supplied
                    throw new InvalidUserException;
                }
            }elseif(is_int($user))
            { // else if user is an integer (user id)
                // find user
                if(!$user = $model->find($user))
                {
                    // invalid login credentials
                    throw new InvalidUserException('Invalid user.');
                }
                // return user
                return $user;
            }elseif(is_string($user))
            { // else if user is an integer (user id)
                // find user
                if(!$user = $model->where('id', $user)->first())
                {
                    // invalid login credentials
                    throw new InvalidUserException('Invalid user.');
                }
                // return user
                return $user;
            }else
            {
                // throw new attempt param exception
                throw new AttemptParamException;
            }
        }

        private function encode($payload, $key, $algo = 'HS256')
        {
            $header = array('typ' => 'JWT', 'alg' => $algo);
            $segments = array(
                $this->urlsafeB64Encode(json_encode($header)),
                $this->urlsafeB64Encode(json_encode($payload))
            );
            $signing_input = implode('.', $segments);
            $signature = $this->sign($signing_input, $key, $algo);
            $segments[] = $this->urlsafeB64Encode($signature);
            return implode('.', $segments);
        }

        private function decode($jwt, $key = null, $algo = null)
        {
            $tks = explode('.', $jwt);
            if (count($tks) != 3) {
                throw new TokenException('segment_count');
            }
            list($headb64, $payloadb64, $cryptob64) = $tks;
            if (null === ($header = json_decode($this->urlsafeB64Decode($headb64)))) {
                throw new TokenException('invalid_encoding');
            }
            if (null === $payload = json_decode($this->urlsafeB64Decode($payloadb64))) {
                throw new TokenException('invalid_encoding');
            }
            $sig = $this->urlsafeB64Decode($cryptob64);
            if (isset($key)) {
                if (empty($header->alg)) {
                    throw new TokenException('empty_alg');
                }
                if (!$this->verifySignature($sig, "$headb64.$payloadb64", $key, $algo)) {
                    throw new TokenException('invald_token');
                }
            }
            return $payload;
        }
        private function verifySignature($signature, $input, $key, $algo)
        {
            switch ($algo) {
                case'HS256':
                case'HS384':
                case'HS512':
                    return $this->sign($input, $key, $algo) === $signature;
                case 'RS256':
                    return (boolean) openssl_verify($input, $signature, $key, OPENSSL_ALGO_SHA256);
                case 'RS384':
                    return (boolean) openssl_verify($input, $signature, $key, OPENSSL_ALGO_SHA384);
                case 'RS512':
                    return (boolean) openssl_verify($input, $signature, $key, OPENSSL_ALGO_SHA512);
                default:
                    throw new TokenException("invalid_alg");
            }
        }
        private function sign($input, $key, $algo)
        {
            switch ($algo) {
                case 'HS256':
                    return hash_hmac('sha256', $input, $key, true);
                case 'HS384':
                    return hash_hmac('sha384', $input, $key, true);
                case 'HS512':
                    return hash_hmac('sha512', $input, $key, true);
                case 'RS256':
                    return $this->generateRSASignature($input, $key, OPENSSL_ALGO_SHA256);
                case 'RS384':
                    return $this->generateRSASignature($input, $key, OPENSSL_ALGO_SHA384);
                case 'RS512':
                    return $this->generateRSASignature($input, $key, OPENSSL_ALGO_SHA512);
                default:
                    throw new TokenException("invalid_alg");
            }
        }
        private function generateRSASignature($input, $key, $algo)
        {
            if (!openssl_sign($input, $signature, $key, $algo)) {
                throw new Exception("unable_to_sign");
            }
            return $signature;
        }
        private function urlSafeB64Encode($data)
        {
            $b64 = base64_encode($data);
            $b64 = str_replace(array('+', '/', '\r', '\n', '='),
                    array('-', '_'),
                    $b64);
            return $b64;
        }
        private function urlSafeB64Decode($b64)
        {
            $b64 = str_replace(array('-', '_'),
                    array('+', '/'),
                    $b64);
            return base64_decode($b64);
        }
    }
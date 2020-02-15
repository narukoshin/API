<?php
    /**
     * @package Router class
     * @author Yuu Hirokabe
     * @version 1.0.0
     */
    class router{
        /**
         * Storing all routes
         * 
         * @var array
         */
        private $routes = [];
        /**
         * Storing request method
         * 
         * @var string
         */
        private $method;
        /**
         * Storing active route
         * 
         * @var string
         */
        private $request;
        private $user;
        private $db;
        /**
         * @return void
         */
        public function __construct(\PDO $db){
            $this->method = strtolower($_SERVER['REQUEST_METHOD']);
            $this->request = $_GET['route'];
            $this->db = $db;
            $this->user = new user($this->db);
        }
        /**
         * @param callable $func
         * @return void
         */
        public function routes(callable $func){
            $func();
            // Starting routes
            $this->init();
        }
        /**
         * Adding routes
         * 
         * @param array $routes
         */
        public function add(array $routes){
            array_push($this->routes, $routes);
        }
        /**
         * Start routing
         * 
         * @return void
         */
        private function init(){
            foreach($this->routes as $routes){
                foreach($routes as $key => $val){
                    if(preg_match("#{$key}#", $this->request)){
                        $methods = $val['methods'];
                        if (!in_array($this->method, $methods)){
                            echo json_encode(['error' => 'true', 'message' => 'That reuqest method not allowed!']);
                            exit;
                        }
                        $t = explode('/', $this->request);
                        # Checking if have /v1/{controller} or /v1/{controller}/{parameter}
                        if (count($t) == 1){
                            // Setting parameter to null
                            $parameter = null;
                            list ($controller) = $t;
                        } else 
                            list($controller, $parameter) = $t;
                        if(class_exists(strtolower($controller))){
                            switch($this->method){
                                case 'post':
                                    if(!method_exists($this->{$controller}, 'insert')){
                                        echo json_encode(['error' => 'true', 'message' => "Object {$controller} method insert() not found"]);
                                        exit;
                                    }
                                    $this->{$controller}->insert($parameter);
                                break;
                                case 'get':
                                    if(!method_exists($this->{$controller}, 'get')){
                                        echo json_encode(['error' => 'true', 'message' => "Object {$controller} method get() not found"]);
                                        exit;
                                    }
                                    $this->{$controller}->get($parameter);
                                break;
                                case 'put':
                                case 'patch':
                                    if(!method_exists($this->{$controller}, 'update')){
                                        echo json_encode(['error' => 'true', 'message' => "Object {$controller} method update() not found"]);
                                        exit;
                                    }
                                    $this->{$controller}->update($parameter, null);
                                break;
                                case 'delete':
                                    if(!method_exists($this->{$controller}, 'delete')){
                                        echo json_encode(['error' => 'true', 'message' => "Object {$controller} method delete() not found"]);
                                        exit;
                                    }
                                    $this->{$controller}->delete($parameter);
                                break;
                            }
                        } else{
                            echo json_encode([
                                'error' => 'true',
                                'message' => "Controller '{$controller}' class not found!"
                            ]);
                            exit;
                        }
                        break;
                    }
                }
            }
        }
    }
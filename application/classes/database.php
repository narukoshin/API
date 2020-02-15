<?php
    /**
     * @package Database class
     * @author Yuu Hirokabe
     * @version 1.0.0
     */
    class database{
        /**
         * PDO Instance
         * 
         * @var object
         */
        private $db;
        /**
         * @return void
         */
        public function __construct(){}
        /**
         * Connecting to the database
         * 
         * @param array $data
         * @return object \PDO
         */
        public function connect(array $data){
            try{
                extract($data);
                $this->db = new pdo("mysql:host={$host};dbname={$base}", $user, $pass);
            }catch(\PDOException $e){
                # If database not found..
                if ($e->getCode() == 1049) {
                    
                }
            }
            return $this->db;
        }
    }
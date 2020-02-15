<?php
    /**
     * @package User class
     * @author Yuu Hirokabe
     * @version 1.0.0
     */
    class user {
        /**
         * \PDO Object
         * 
         * @var object
         */
        private $db;
        /**
         * @var string
         */
        private $table;
        /**
         * @param object \PDO Object
         * @return void
         */
        public function __construct(\PDO $db){
            # Setting \PDO Instance
            $this->db = $db;
            # Setting database table
            $this->table = 'users';
        }
        /**
         * POST Method - Creates new user in database
         * 
         * @param int|null $id
         * @return json
         */
        public function insert($id){
            $required = ['name', 'age', 'job'];
            $data = json_decode(file_get_contents('php://input'), true);
            foreach($required as $require){
                if (!@array_key_exists($require, $data)) {
                    echo json_encode([
                        'error' => 'true',
                        'message' => 'Please enter required elements name|age|job!'
                    ]);
                    exit;
                }
            }
            extract($data);
            $stmt = $this->db->prepare("INSERT INTO `{$this->table}` (`name`, `age`, `job`) VALUES(?, ?, ?)");
            $result = $stmt->execute([$name, $age, $job]);
            if($result){
                echo json_encode(['error'=>'false','message'=>'User successfuly created..']);
                exit;
            } else{
                echo json_encode(['error'=>'true','message'=>'User creating failed...']);
                exit;
            }
        }
        /**
         * GET Method - Get users from database
         * 
         * @param int|null $id
         * @return void
         */
        public function get($id){
            $id = $id ?? false;
            echo $id;
            if ($id){
                # Get user by ID
                $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `id` = :id;");
                $result = $stmt->execute([':id' => $id]);
                if ($result){
                    # If user not found
                    if($stmt->rowCount() == 0){
                        echo json_encode([
                            'error' => 'true',
                            'message' => 'User not found'
                        ]);
                        exit;
                    }
                    $data = $stmt->fetch(PDO::FETCH_NAMED);
                    echo json_encode($data);
                    exit;
                }
            } else{
                # Get all users
                $result = [];
                $stmt = $this->db->query("SELECT * FROM `{$this->table}`;");
                if(!$stmt->rowCount() > 0){
                    echo json_encode([
                        'error' => 'true',
                        'message' => 'No users found'
                    ]);
                    exit;
                } else{
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($result, $data);
                    }
                    echo json_encode($result);
                    exit;
                }
            }
        }
        /**
         * UPDATE Method - Update user information
         * 
         * @param int|null $id
         * @return json
         */
        public function update($id){
            # Getting and decoding json update post details
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data){
                foreach ($data as $key => $val){
                    $keys[] = "`{$key}` = :{$key}";
                    $vals[":{$key}"] = $val;
                }
                # Storing user id in vals array
                $vals[':id'] = $id;
                # Imploding all together in one line
                $prep = join(',', $keys);
                # Preparing sql statament
                $stmt = $this->db->prepare("UPDATE `{$this->table}` SET {$prep} WHERE `id` = :id;");
                # Executing sql statament
                $result = $stmt->execute($vals);
                # Checking if statament executes successfuly
                if ($result){
                    // If user don't exists
                    if ($stmt->rowCount() == 0){
                        echo json_encode([
                            'error' => 'true',
                            'message' => 'User don\'t exists!'
                        ]);
                        exit;
                    }
                    echo json_encode([
                        'error' => 'false',
                        'message' => 'User successfuly updated!'
                    ]);
                    exit;
                } else{
                    # If statament executing failed..
                    echo json_encode([
                        'error' => 'true',
                        'message' => 'Execute error: Check if column name is typed correctly!'
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'error' => 'true',
                    'message' => 'No update details found!'
                ]);
                exit;
            }
        }
        /**
         * DELETE Method - Deletes user from database
         * 
         * @param int|null $id
         * @return json
         */
        public function delete($id){
            # Preparing delete statament
            $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `id` = ?;");
            # Executing delete statament
            $result = $stmt->execute([$id]);
            # Check if executing was successful
            if($result){
                # Printing message if executing was successful
                if ($stmt->rowCount() == 0){
                    // If user to delete not found
                    echo json_encode([
                        'error' => 'true',
                        'message' => 'User don\'t exists!'
                    ]);
                    exit;
                }
                // If user exists
                echo json_encode([
                    'error' => 'false',
                    'message' => 'User successfuly deleted!'
                ]);
                exit;
            } else {
                # Printing message if executing was not successful
                echo json_encode(['error'=>'true','message'=>'User deletion failed!']);
                exit;
            }
        }
    }
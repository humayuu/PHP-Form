<?php
// Class Start Here
class Database
{

    private $dsn = "mysql:host=localhost;dbname=php_form_db;charset=utf8mb4";
    private $user = "root";
    private $password = "";

    private $pdo = null;
    private $result = [];

    // Constructor: Initialize PDO connection
    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Throw exceptions on errors
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays by default
                    PDO::ATTR_EMULATE_PREPARES => false               // Use real prepared statements
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }


    // Function to check if a table exists in the database
    protected function tableExists($table)
    {
        try {
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE :table");
            $stmt->execute([':table' => $table]);

            if ($stmt->rowCount() === 1) {
                return true;
            } else {
                $this->result[] = "Table `$table` does not exist in this database.";
                return false;
            }
        } catch (PDOException $e) {
            $this->result[] = "Error checking table: " . $e->getMessage();
            return false;
        }
    }


    // Function for Insert Record into the Database
    public function insert($table, $params = [], $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        if (empty($params) || !is_array($params)) {
            $this->result = "No Data Provided For Insert.";
            return false;
        }

        try {
            $column = implode(",", array_keys($params));
            $value  = ":" . implode(",:", array_keys($params));

            $stmt = $this->pdo->prepare("INSERT INTO $table ($column) VALUES ($value)");
            $result =  $stmt->execute($params);

            if ($result) {
                $lastId = $this->pdo->lastInsertId();

                if ($redirect) {
                    header("Location: " . $redirect);
                    exit;
                }

                return $lastId;
            }
            return false;
        } catch (PDOException $e) {
            $this->result[] = "Error in Inserting Record: " . $e->getMessage();
            return false;
        }
    }























































    // Function for Close Connection to Database
    public function __destruct()
    {
        $this->pdo = null;
    }
} // Class Ends Here
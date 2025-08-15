<?php
// Class Start Here
class Database
{
    private $dsn = "mysql:host=localhost;dbname=php_form_db;charset=utf8mb4";
    private $userName = "root";
    private $password = "";
    private $pdo = null;
    private $result = [];
    private $error = [];

    //Function for Connection to Database
    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->userName,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Connection failed in " . $e->getMessage());
        }
    }

    // Function for Check if the table is Exists in Database
    protected function tableExists($table)
    {
        try {

            $stmt =  $this->pdo->query("SHOW TABLES LIKE " . $this->pdo->quote($table));

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                $this->result[] = "Table " . $table . " does not exists in this database.";
                return false;
            }
        } catch (PDOException $e) {
            $this->error[] = "Error in check table " . $e->getMessage();
            return false;
        }
    }

    // Function for insert data into Database
    public function insert($table, $params = [], $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }


        if (empty($params) || !is_array($params)) {
            $this->error[] = "No data provided for insert.";
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $columns = implode(', ', array_keys($params));
            $values  = ":" .  implode(' ,:', array_keys($params));

            $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();

                $errorInfo = $stmt->errorInfo();
                $this->error[] = "Insert failed: " . ($errorInfo[2] ?? 'Unknown error');
                return false;
            }

            $lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();

            if ($redirect) {
                header("Location: " . $redirect);
                exit;
            }

            return $lastId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $this->error[] = "Error in insert " . $e->getMessage();
            return false;
        }
    }

    // Function for Fetch all Data from Database
    public function selectAll($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }
        $sql = "SELECT $rows FROM $table";
        if ($join !== null)  $sql .= " $join";
        if ($where !== null) $sql .= " WHERE $where";
        if ($order !== null) $sql .= " ORDER BY $order";
        if ($limit !== null) $sql .= " LIMIT $limit";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $this->result =  $stmt->fetchAll();
            return $this->result;
        } catch (PDOException $e) {
            $this->error[] = "Error in Fetch all Data " . $e->getMessage();
            return false;
        }
    }


    // Function for Fetch all Data from Database
    public function selectId($table, $rows = "*", $join = null, $where = null, $params = [], $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }
        $sql = "SELECT $rows FROM $table";
        if ($join !== null)  $sql .= " $join";
        if ($where !== null) $sql .= " WHERE $where";
        if ($order !== null) $sql .= " ORDER BY $order";
        if ($limit !== null) $sql .= " LIMIT $limit";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $this->result =  $stmt->fetch(); //Single Row
            return $this->result;
        } catch (PDOException $e) {
            $this->error[] = "Error in Fetch all Data " . $e->getMessage();
            return false;
        }
    }

    // Function for update Data into Database
    public function update($table, $params = [], $where = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        if (empty($params) || !is_array($params)) {
            $this->error[] = "No data provided for update.";
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $setClause = implode(", ", array_map(function ($col) {
                return "$col = :$col";
            }, array_keys($params)));

            $sql = "UPDATE $table SET $setClause";
            if ($where !== null) $sql .= " WHERE $where";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $stmt->errorInfo();
                $this->error[] = "Update failed: " . ($errorInfo[2] ?? "Unknown Error");
                return false;
            }

            $affected = $stmt->rowCount();
            $this->pdo->commit();

            return $affected; // return affected rows
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->error[] = "Error in update: " . $e->getMessage();
            return false;
        }
    }

    // Function for Delete Data in Database
    public function delete($table, $where = null, $params = [], $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $sql = "DELETE FROM $table";
            if ($where !== null) $sql .= " WHERE $where";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $stmt->errorInfo();
                $this->error[] = "Delete failed " . ($errorInfo[2] ?? "Unknown Error");
                return false;
            }

            $affected = $stmt->rowCount();
            $this->pdo->commit();

            if ($redirect) {
                header("Location: " . $redirect);
                exit;
            }
            return $affected;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->error[] = "Error in delete " . $e->getMessage();
            return false;
        }
    }


    // Function for Get Error
    public function getErrors()
    {
        return $this->error;
    }

    // Function for Get Error
    public function getResult()
    {
        return $this->result;
    }

    // Function for close Connection
    public function __destruct()
    {
        $this->pdo = null;
    }
} // Class Ends Here
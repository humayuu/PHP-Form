<?php
// Database Class Start Here

class Database
{

    private $dsn = "mysql:host=localhost;dbname=php_form_db;charset=utf8mb4";
    private $user = "root";
    private $password = "";
    private $pdo = null;
    public $result = [];

    // Function for Connection to Database
    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed " . $e->getMessage());
        }
    }


    // Function for Check if Table Exists in Database
    protected function tableExists($table)
    {
        try {
            // Escape table name safely
            $stmt = $this->pdo->query("SHOW TABLES LIKE " . $this->pdo->quote($table));

            if ($stmt->rowCount() > 0) {
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


    // Function for Insert Record in Database
    public function insert($table, $param = [], $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        if (empty($param) || !is_array($param)) {
            $this->result[] = "No data provided for insert.";
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $columns = implode(',', array_keys($param));
            $values  = ":" . implode(',:', array_keys($param));

            $insert = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
            $result = $insert->execute($param);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $insert->errorInfo();
                $this->result[] = "Insert failed: " . ($errorInfo[2] ?? 'Unknown error');
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
            $this->pdo->rollBack();
            $this->result[] = "Error inserting record: " . $e->getMessage();
            return false;
        }
    }



    // Function For Fetch Record Form Database
    public function select($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        $sql = "SELECT $rows FROM $table";

        if ($join != null) {
            $sql .= " $join";
        }

        if ($where != null) {
            $sql .= " WHERE $where";
        }

        if ($order != null) {
            $sql .= " ORDER BY $order";
        }

        if ($limit != null) {
            $sql .= " LIMIT $limit";
        }
        try {
            $select = $this->pdo->prepare($sql);
            $select->execute();
            $this->result = $select->fetchAll(PDO::FETCH_ASSOC);
            return $this->result;
        } catch (PDOException $e) {
            $this->result[] = "Error in fetch record " . $e->getMessage();
            return false;
        }
    }

    // Function For Fetch Single Record by ID or Condition
    public function selectId($table, $rows = "*", $join = null, $where = null, $params = [], $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        $sql = "SELECT $rows FROM `$table`";

        if ($join) {
            $sql .= " $join";
        }

        if ($where) {
            $sql .= " WHERE $where";
        }

        if ($order) {
            $sql .= " ORDER BY $order";
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC); // single row
        } catch (PDOException $e) {
            $this->result[] = "Error in fetch record: " . $e->getMessage();
            return false;
        }
    }



    // Function for Update Record in Database
    public function update($table, $param = [], $where = null, $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        if (empty($param) || !is_array($param)) {
            $this->result[] = "No data provide for update.";
            return false;
        }

        try {
            $this->pdo->beginTransaction();
            $setClause = implode(", ", array_map(function ($col) {
                return "$col = :$col";
            }, array_keys($param)));

            $sql = "UPDATE $table SET $setClause";

            if ($where != null) {
                $sql .= " WHERE $where";
            }

            $update = $this->pdo->prepare($sql);
            $result = $update->execute($param);
            if (!$result) {
                // rollback and capture error info
                $this->pdo->rollBack();
                $errorInfo = $update->errorInfo();
                $this->result[] = "Update failed: " . (isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown error');
                return false;
            }

            $this->pdo->commit();
            if ($redirect) {
                header("Location: " . $redirect);
                exit;
            }

            return true;
        } catch (PDOException $e) {
            $this->result[] = "Error in update record " . $e->getMessage();
            return false;
        }
    }
    // Function for Delete Record in Database
    public function delete($table, $where = null, $redirect = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $sql = "DELETE FROM `$table`";

            if ($where !== null) {
                $sql .= " WHERE $where";
            }

            $delete = $this->pdo->prepare($sql);

            $result = $delete->execute();

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $delete->errorInfo();
                $this->result[] = "Delete failed: " . ($errorInfo[2] ?? 'Unknown error');
                return false;
            }

            $this->pdo->commit();

            if ($redirect) {
                header("Location: " . $redirect);
                exit;
            }

            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->result[] = "Error in delete record: " . $e->getMessage();
            return false;
        }
    }




    // Function for Close Connection to Database
    public function __destruct()
    {
        $this->pdo =  null;
    }
} // Class Ends Here
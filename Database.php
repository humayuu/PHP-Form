<?php
// Class Starts Here
class Database
{

    private $dsn = "mysql:host=localhost;dbname=php_form_db;charset=utf8mb4";
    private $user = "root";
    private $password = "";
    private $pdo = null;

    private $result = [];
    private $error = [];

    // Function for Connection to Database
    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE =>  PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]

            );
        } catch (PDOException $e) {
            throw new Exception("Database Connection Error " . $e->getMessage());
        }
    }

    // Function for Check if the table is Exists in this Database or not
    protected function tableExists($table)
    {
        try {

            $sql = $this->pdo->query("SHOW TABLES LIKE " . $this->pdo->quote($table));
            if ($sql->rowCount() > 0) {
                return true;
            } else {
                $this->error[] = "Table " . $table . " does not exists in this database.";
                return false;
            }
        } catch (PDOException $e) {
            $this->error[] = "Error in Finding Table " . $e->getMessage();
            return false;
        }
    }

    // Function for insert data into Database
    public function insert($table, $params = [], $redirect = null)
    {
        if (!$this->tableExists($table)) return false;


        if (empty($params) || !is_array($params)) {
            $this->error[] = "does not provide data for insert";
            return false;
        }

        $columns = implode(', ', array_keys($params));
        $values = ":" . implode(',:', array_keys($params));

        try {

            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $this->pdo->errorInfo();
                $this->error[] = "Insert failed" .  ($errorInfo[2] ?? 'Unknown error');
                return false;
            }

            $lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();

            if ($redirect) {
                header("Location: " .  $redirect);
                exit;
            }

            return $lastId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            $this->error[] = "Error in insert " . $e->getMessage();
            return false;
        }
    }

    // Function for Select All Data from Database
    public function selectAll($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null) $sql  .= " $join";
        if ($where !== null) $sql  .= " WHERE $where";
        if ($order !== null) $sql  .= " ORDER BY $order";
        if ($limit !== null) $sql  .= " LIMIT $limit";

        try {

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $this->result = $stmt->fetchAll();
            return $this->result;
        } catch (PDOException $e) {
            $this->error[] = "Error in select All data " . $e->getMessage();
            return false;
        }
    }



    // Function for Select All Data from Database
    public function selectOne($table, $rows = "*", $join = null, $where = null, $params = [], $order = null, $limit = null)
    {
        if (!$this->tableExists($table)) return false;

        $sql = "SELECT $rows FROM $table";

        if ($join  !== null) $sql  .= " $join";
        if ($where !== null) $sql  .= " WHERE $where";
        if ($order !== null) $sql  .= " ORDER BY $order";
        if ($limit !== null) $sql  .= " LIMIT $limit";

        try {

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $this->result = $stmt->fetch(); // Single Row
            return $this->result;
        } catch (PDOException $e) {
            $this->error[] = "Error in select All data " . $e->getMessage();
            return false;
        }
    }

    // Function for update data into the Database
    public function update($table, $params = [], $where = null)
    {
        if (!$this->tableExists($table)) {
            return false;
        }

        if (empty($params) || !is_array($params)) {
            $this->error[] = "does not provide data for update";
            return false;
        }

        $setClause = implode(', ', array_map(function ($col) {
            return "$col = :$col";
        }, array_keys($params)));

        $sql = "UPDATE $table SET $setClause";
        if ($where !== null) $sql  .= " WHERE $where";

        try {

            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $this->pdo->errorInfo();
                $this->error[] = "Update failed" .  ($errorInfo[2] ?? 'Unknown error');
                return false;
            }

            $affected = $stmt->rowCount();
            $this->pdo->commit();

            return $affected;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            $this->error[] = "Error in update Data " . $e->getMessage();
            return false;
        }
    }

    // Function for delete data from Database
    public function delete($table, $where = null, $params = [], $redirect = null)
    {
        if (!$this->tableExists($table)) return false;

        if (empty($params) || !is_array($params)) {
            $this->error[] = "does not provide data for delete";
            return false;
        }

        $sql = "DELETE FROM $table";
        if ($where !== null) $sql .= " WHERE $where";

        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if (!$result) {
                $this->pdo->rollBack();
                $errorInfo = $this->pdo->errorInfo();
                $this->error[] = "Delete failed" .  ($errorInfo[2] ?? 'Unknown error');
                return false;
            }
            $affected = $stmt->rowCount();
            $this->pdo->commit();
            if ($redirect) {
                header("Location: " . $redirect);
                exit;
            }
            return $affected; // return affected rows
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




    // Function for Close Connection to Database
    public function __destruct()
    {
        $this->pdo = null;
    }
} // Class Ends Here
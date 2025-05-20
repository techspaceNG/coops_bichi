<?php
declare(strict_types=1);

namespace App\Helpers;

use PDO;
use PDOException;
use PDOStatement;
use App\Helpers\Environment;

/**
 * Database Connection Helper
 */
class Database
{
    private static ?PDO $connection = null;

    /**
     * Get database connection
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = self::getConfig();
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            
            try {
                self::$connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                // Log error and display user-friendly message
                error_log("Database Connection Error: " . $e->getMessage());
                die("Failed to connect to the database. Please try again later.");
            }
        }
        
        return self::$connection;
    }
    
    /**
     * Get database configuration from environment variables
     * 
     * @return array Database configuration
     */
    private static function getConfig(): array
    {
        // Ensure environment variables are loaded
        Environment::load();
        
        return [
            'host'     => Environment::get('DB_HOST', 'localhost'),
            'dbname'   => Environment::get('DB_NAME', 'coops_bichi'),
            'username' => Environment::get('DB_USER', 'root'),
            'password' => Environment::get('DB_PASS', ''),
            'charset'  => Environment::get('DB_CHARSET', 'utf8mb4'),
            'options'  => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ],
        ];
    }
    
    /**
     * Execute a query with parameters
     *
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public static function executeQuery(string $query, array $params = []): PDOStatement
    {
        $stmt = self::getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Fetch a single record
     *
     * @param string $query
     * @param array $params
     * @return array|null
     */
    public static function fetchOne(string $query, array $params = []): ?array
    {
        try {
            $stmt = self::executeQuery($query, $params);
            $result = $stmt->fetch();
            
            return $result ?: null;
        } catch (\PDOException $e) {
            // Enhanced error logging for SQL query failures
            error_log('Database fetchOne error: ' . $e->getMessage());
            error_log('SQL Query: ' . $query);
            error_log('Query Parameters: ' . json_encode($params));
            error_log('Error Code: ' . $e->getCode());
            error_log('Error Info: ' . json_encode($e->errorInfo ?? []));
            
            // Re-throw the exception to maintain original behavior
            throw $e;
        }
    }
    
    /**
     * Fetch multiple records
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public static function fetchAll(string $query, array $params = []): array
    {
        try {
            $stmt = self::executeQuery($query, $params);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Enhanced error logging for SQL query failures
            error_log('Database fetchAll error: ' . $e->getMessage());
            error_log('SQL Query: ' . $query);
            error_log('Query Parameters: ' . json_encode($params));
            error_log('Error Code: ' . $e->getCode());
            error_log('Error Info: ' . json_encode($e->errorInfo ?? []));
            
            // Re-throw the exception to maintain original behavior
            throw $e;
        }
    }
    
    /**
     * Insert a record and return the last insert ID
     *
     * @param string $table
     * @param array $data
     * @return int|null
     */
    public static function insert(string $table, array $data): ?int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        self::executeQuery($query, array_values($data));
        
        return (int)self::getConnection()->lastInsertId();
    }
    
    /**
     * Update a record
     *
     * @param string $table
     * @param array $data
     * @param array $where Associative array of conditions
     * @return int Number of affected rows
     */
    public static function update(string $table, array $data, array $where): int
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "$column = ?";
        }
        
        $whereConditions = [];
        foreach (array_keys($where) as $column) {
            $whereConditions[] = "$column = ?";
        }
        
        $setClause = implode(', ', $sets);
        $whereClause = implode(' AND ', $whereConditions);
        
        $query = "UPDATE $table SET $setClause WHERE $whereClause";
        
        $params = array_merge(array_values($data), array_values($where));
        $stmt = self::executeQuery($query, $params);
        
        return $stmt->rowCount();
    }
    
    /**
     * Delete a record
     *
     * @param string $table
     * @param array $where
     * @return int Number of affected rows
     */
    public static function delete(string $table, array $where): int
    {
        $whereConditions = [];
        foreach (array_keys($where) as $column) {
            $whereConditions[] = "$column = ?";
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        $query = "DELETE FROM $table WHERE $whereClause";
        
        $stmt = self::executeQuery($query, array_values($where));
        
        return $stmt->rowCount();
    }
} 
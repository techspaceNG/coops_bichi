<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database connection manager
 */
final class Database
{
    /**
     * PDO instance
     */
    private static ?PDO $connection = null;
    
    /**
     * Get database connection
     * 
     * @return PDO Database connection
     * @throws PDOException If connection fails
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = self::getConfig();
            
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['port'] ?? 3306,
                $config['dbname'],
                $config['charset']
            );
            
            try {
                self::$connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
                // Disable ONLY_FULL_GROUP_BY for compatibility with existing queries
                self::$connection->exec("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            } catch (PDOException $e) {
                // Log error and rethrow
                error_log('Database connection failed: ' . $e->getMessage());
                die("Core Database Connection Failed: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
    
    /**
     * Get database configuration
     * 
     * @return array Database configuration
     */
    public static function getConfig(): array
    {
        $config = \App\Core\Config::getDatabaseConfig();
        
        return [
            'host'     => $config['host'],
            'dbname'   => $config['dbname'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset'  => $config['charset'],
            'port'     => $config['port'],
            'options'  => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_SSL_CA       => true,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ],
        ];
    }
    
    /**
     * Fetch a single row from database
     *
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @return array|null Row as associative array or null if not found
     */
    public static function fetchOne(string $query, array $params = []): ?array
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            error_log('Database fetchOne error: ' . $e->getMessage() . ' - Query: ' . $query);
            throw $e;
        }
    }
    
    /**
     * Fetch multiple rows from database
     *
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @return array Rows as associative arrays
     */
    public static function fetchAll(string $query, array $params = []): array
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database fetchAll error: ' . $e->getMessage() . ' - Query: ' . $query);
            throw $e;
        }
    }
    
    /**
     * Insert data into database
     *
     * @param string $table Table name
     * @param array $data Data to insert (column => value)
     * @return int|null Last insert ID or null on failure
     */
    public static function insert(string $table, array $data): ?int
    {
        try {
            $columns = array_keys($data);
            $placeholders = array_fill(0, count($columns), '?');
            
            $query = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $table,
                implode(', ', $columns),
                implode(', ', $placeholders)
            );
            
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute(array_values($data));
            
            return (int)self::getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log('Database insert error: ' . $e->getMessage() . ' - Table: ' . $table);
            return null;
        }
    }
    
    /**
     * Update data in database
     *
     * @param string $table Table name
     * @param array $data Data to update (column => value)
     * @param array $where Conditions (column => value)
     * @return int Number of affected rows
     */
    public static function update(string $table, array $data, array $where): int
    {
        try {
            $setParts = [];
            $whereParts = [];
            $params = [];
            
            // Build SET clause
            foreach ($data as $column => $value) {
                $setParts[] = "$column = ?";
                $params[] = $value;
            }
            
            // Build WHERE clause
            foreach ($where as $column => $value) {
                $whereParts[] = "$column = ?";
                $params[] = $value;
            }
            
            $query = sprintf(
                "UPDATE %s SET %s WHERE %s",
                $table,
                implode(', ', $setParts),
                implode(' AND ', $whereParts)
            );
            
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Database update error: ' . $e->getMessage() . ' - Table: ' . $table);
            return 0;
        }
    }
    
    /**
     * Delete data from database
     *
     * @param string $table Table name
     * @param array $where Conditions (column => value)
     * @return int Number of affected rows
     */
    public static function delete(string $table, array $where): int
    {
        try {
            $whereParts = [];
            $params = [];
            
            // Build WHERE clause
            foreach ($where as $column => $value) {
                $whereParts[] = "$column = ?";
                $params[] = $value;
            }
            
            $query = sprintf(
                "DELETE FROM %s WHERE %s",
                $table,
                implode(' AND ', $whereParts)
            );
            
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Database delete error: ' . $e->getMessage() . ' - Table: ' . $table);
            return 0;
        }
    }
    
    /**
     * Execute a custom SQL query
     *
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @return int Number of affected rows
     */
    public static function query(string $query, array $params = []): int
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Database query error: ' . $e->getMessage() . ' - Query: ' . $query);
            return 0;
        }
    }
    
    /**
     * Execute a direct SQL query
     *
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @return bool True on success or false on failure
     */
    public static function execute(string $query, array $params = []): bool
    {
        try {
            $stmt = self::getConnection()->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log('Database execute error: ' . $e->getMessage() . ' - Query: ' . $query);
            throw $e;
        }
    }
    
    /**
     * Begin a transaction
     * 
     * @return bool True on success or false on failure
     */
    public static function beginTransaction(): bool
    {
        try {
            return self::getConnection()->beginTransaction();
        } catch (PDOException $e) {
            error_log('Database beginTransaction error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Commit a transaction
     * 
     * @return bool True on success or false on failure
     */
    public static function commit(): bool
    {
        try {
            return self::getConnection()->commit();
        } catch (PDOException $e) {
            error_log('Database commit error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Rollback a transaction
     * 
     * @return bool True on success or false on failure
     */
    public static function rollback(): bool
    {
        try {
            return self::getConnection()->rollBack();
        } catch (PDOException $e) {
            error_log('Database rollback error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if inside a transaction
     * 
     * @return bool True if inside a transaction, false otherwise
     */
    public static function inTransaction(): bool
    {
        try {
            return self::getConnection()->inTransaction();
        } catch (PDOException $e) {
            error_log('Database inTransaction error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Close database connection
     */
    public static function closeConnection(): void
    {
        self::$connection = null;
    }
    
    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}
} 
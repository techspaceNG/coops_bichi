<?php
declare(strict_types=1);

namespace App\Config;

use App\Helpers\Database as HelpersDatabase;
use PDO;
use PDOException;

/**
 * Database connection manager
 * This is a wrapper class for App\Helpers\Database to maintain backward compatibility
 */
final class Database
{
    /**
     * Get database connection
     * 
     * @return PDO Database connection
     * @throws PDOException If connection fails
     */
    public static function getConnection(): PDO
    {
        return HelpersDatabase::getConnection();
    }
    
    /**
     * Close database connection
     */
    public static function closeConnection(): void
    {
        // Not implemented in the helper
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
        return HelpersDatabase::fetchOne($query, $params);
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
        return HelpersDatabase::fetchAll($query, $params);
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
        return HelpersDatabase::insert($table, $data);
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
        return HelpersDatabase::update($table, $data, $where);
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
        return HelpersDatabase::delete($table, $where);
    }
} 
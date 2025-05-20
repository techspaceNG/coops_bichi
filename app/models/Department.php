<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Department Model
 */
class Department
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $created_at;
    public string $updated_at;
    
    private static ?PDO $db = null;
    
    /**
     * Get database connection
     */
    private static function getDb(): PDO
    {
        if (self::$db === null) {
            self::$db = Database::getConnection();
        }
        return self::$db;
    }
    
    /**
     * Get all departments
     * 
     * @return array Array of departments
     */
    public static function getAll(): array
    {
        $db = self::getDb();
        $stmt = $db->query("SELECT * FROM departments ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get department by ID
     * 
     * @param int $id Department ID
     * @return array|false Department data or false if not found
     */
    public static function getById(int $id): array|false
    {
        $db = self::getDb();
        $stmt = $db->prepare("SELECT * FROM departments WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get department ID by name
     * 
     * @param string $name Department name
     * @return int|null Department ID or null if not found
     */
    public static function getIdByName(string $name): ?int
    {
        $db = self::getDb();
        $stmt = $db->prepare("SELECT id FROM departments WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : null;
    }
    
    /**
     * Create a new department
     * 
     * @param string $name Department name
     * @param string|null $description Department description
     * @return bool Success status
     */
    public static function create(string $name, ?string $description = null): bool
    {
        $db = self::getDb();
        $stmt = $db->prepare("
            INSERT INTO departments (name, description, created_at) 
            VALUES (:name, :description, NOW())
        ");
        
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
} 
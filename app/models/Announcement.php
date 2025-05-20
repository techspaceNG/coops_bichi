<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Database;
use PDO;

/**
 * Announcement model class
 */
final class Announcement
{
    /**
     * Properties
     */
    public ?int $id = null;
    public string $title = '';
    public string $content = '';
    public string $created_by = '';
    public string $created_at = '';
    public string $updated_at = '';
    public string $status = 'published'; // draft, published, archived
    public ?string $publish_date = null;
    public ?string $expire_date = null;
    public string $category = 'general'; // general, important, event, etc.
    
    /**
     * Database connection
     */
    private PDO $db;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    /**
     * Save announcement to database (insert or update)
     *
     * @return bool True on success, false on failure
     */
    public function save(): bool
    {
        try {
            if ($this->id) {
                // Update existing announcement
                $sql = "UPDATE announcements SET 
                    title = :title,
                    content = :content,
                    created_by = :created_by,
                    updated_at = :updated_at,
                    status = :status,
                    publish_date = :publish_date,
                    expire_date = :expire_date,
                    category = :category
                WHERE id = :id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
                $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            } else {
                // Insert new announcement
                $sql = "INSERT INTO announcements (
                    title, content, created_by, created_at, updated_at, 
                    status, publish_date, expire_date, category
                ) VALUES (
                    :title, :content, :created_by, :created_at, :updated_at, 
                    :status, :publish_date, :expire_date, :category
                )";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
                $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            }
            
            // Bind parameters
            $stmt->bindValue(':title', $this->title);
            $stmt->bindValue(':content', $this->content);
            $stmt->bindValue(':created_by', $this->created_by);
            $stmt->bindValue(':status', $this->status);
            $stmt->bindValue(':publish_date', $this->publish_date);
            $stmt->bindValue(':expire_date', $this->expire_date);
            $stmt->bindValue(':category', $this->category);
            
            $result = $stmt->execute();
            
            if (!$this->id && $result) {
                $this->id = (int)$this->db->lastInsertId();
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log('Error saving announcement: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find an announcement by ID
     *
     * @param int $id Announcement ID
     * @return Announcement|null Announcement object or null if not found
     */
    public static function findById(int $id): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM announcements WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding announcement by ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all published announcements
     *
     * @param int $limit Number of announcements to return (0 for all)
     * @param string $category Filter by category (empty for all)
     * @return array Array of Announcement objects
     */
    public static function getPublished(int $limit = 0, string $category = ''): array
    {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT * FROM announcements 
                   WHERE status = 'published' 
                   AND (publish_date IS NULL OR publish_date <= CURRENT_DATE())
                   AND (expire_date IS NULL OR expire_date >= CURRENT_DATE())";
            
            $params = [];
            
            if (!empty($category)) {
                $sql .= " AND category = :category";
                $params[':category'] = $category;
            }
            
            $sql .= " ORDER BY publish_date DESC, created_at DESC";
            
            if ($limit > 0) {
                $sql .= " LIMIT :limit";
                $params[':limit'] = $limit;
            }
            
            $stmt = $db->prepare($sql);
            
            foreach ($params as $key => $value) {
                if ($key === ':limit') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            
            $announcements = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $data) {
                $announcements[] = self::createFromArray($data);
            }
            
            return $announcements;
        } catch (\PDOException $e) {
            error_log('Error fetching announcements: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all announcements (for admin use)
     *
     * @param string $status Filter by status (empty for all)
     * @return array Array of Announcement objects
     */
    public static function getAll(string $status = ''): array
    {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT * FROM announcements";
            $params = [];
            
            if (!empty($status)) {
                $sql .= " WHERE status = :status";
                $params[':status'] = $status;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            $announcements = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $data) {
                $announcements[] = self::createFromArray($data);
            }
            
            return $announcements;
        } catch (\PDOException $e) {
            error_log('Error fetching all announcements: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Delete an announcement
     *
     * @param int $id Announcement ID
     * @return bool True on success, false on failure
     */
    public static function delete(int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM announcements WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error deleting announcement: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create an announcement object from an array
     *
     * @param array $data Announcement data
     * @return Announcement Announcement object
     */
    public static function createFromArray(array $data): self
    {
        $announcement = new self();
        
        $announcement->id = (int)$data['id'];
        $announcement->title = $data['title'];
        $announcement->content = $data['content'];
        $announcement->created_by = $data['created_by'];
        $announcement->created_at = $data['created_at'];
        $announcement->updated_at = $data['updated_at'];
        $announcement->status = $data['status'];
        $announcement->publish_date = $data['publish_date'];
        $announcement->expire_date = $data['expire_date'];
        $announcement->category = $data['category'];
        
        return $announcement;
    }
} 
<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\Database;
use PDO;

/**
 * FAQ model class
 */
final class Faq
{
    /**
     * Properties
     */
    public ?int $id = null;
    public string $question = '';
    public string $answer = '';
    public string $created_at = '';
    public string $updated_at = '';
    public string $category = 'general';
    public int $order = 0;
    public bool $is_active = true;
    
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
     * Save FAQ to database (insert or update)
     *
     * @return bool True on success, false on failure
     */
    public function save(): bool
    {
        try {
            if ($this->id) {
                // Update existing FAQ
                $sql = "UPDATE faqs SET 
                    question = :question,
                    answer = :answer,
                    updated_at = :updated_at,
                    category = :category,
                    `order` = :order,
                    is_active = :is_active
                WHERE id = :id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
                $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            } else {
                // Insert new FAQ
                $sql = "INSERT INTO faqs (
                    question, answer, created_at, updated_at, 
                    category, `order`, is_active
                ) VALUES (
                    :question, :answer, :created_at, :updated_at, 
                    :category, :order, :is_active
                )";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
                $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            }
            
            // Bind parameters
            $stmt->bindValue(':question', $this->question);
            $stmt->bindValue(':answer', $this->answer);
            $stmt->bindValue(':category', $this->category);
            $stmt->bindValue(':order', $this->order, PDO::PARAM_INT);
            $stmt->bindValue(':is_active', $this->is_active ? 1 : 0, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            
            if (!$this->id && $result) {
                $this->id = (int)$this->db->lastInsertId();
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log('Error saving FAQ: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find an FAQ by ID
     *
     * @param int $id FAQ ID
     * @return Faq|null FAQ object or null if not found
     */
    public static function findById(int $id): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM faqs WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding FAQ by ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all active FAQs
     *
     * @param string $category Filter by category (empty for all)
     * @return array Array of FAQ objects
     */
    public static function getAll(string $category = ''): array
    {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT * FROM faqs WHERE is_active = 1";
            $params = [];
            
            if (!empty($category)) {
                $sql .= " AND category = :category";
                $params[':category'] = $category;
            }
            
            $sql .= " ORDER BY `order` ASC, id ASC";
            
            $stmt = $db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            $faqs = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $data) {
                $faqs[] = self::createFromArray($data);
            }
            
            return $faqs;
        } catch (\PDOException $e) {
            error_log('Error fetching FAQs: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all FAQs (for admin use, including inactive)
     *
     * @return array Array of FAQ objects
     */
    public static function getAllForAdmin(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM faqs ORDER BY `order` ASC, id ASC");
            $stmt->execute();
            
            $faqs = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $data) {
                $faqs[] = self::createFromArray($data);
            }
            
            return $faqs;
        } catch (\PDOException $e) {
            error_log('Error fetching all FAQs: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Delete an FAQ
     *
     * @param int $id FAQ ID
     * @return bool True on success, false on failure
     */
    public static function delete(int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM faqs WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error deleting FAQ: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create an FAQ object from an array
     *
     * @param array $data FAQ data from database
     * @return Faq FAQ object
     */
    public static function createFromArray(array $data): self
    {
        $faq = new self();
        
        $faq->id = isset($data['id']) ? (int)$data['id'] : null;
        $faq->question = $data['question'] ?? '';
        $faq->answer = $data['answer'] ?? '';
        $faq->created_at = $data['created_at'] ?? '';
        $faq->updated_at = $data['updated_at'] ?? '';
        $faq->category = $data['category'] ?? 'general';
        $faq->order = isset($data['order']) ? (int)$data['order'] : 0;
        $faq->is_active = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        
        return $faq;
    }
    
    /**
     * Seed the database with sample FAQs if none exist
     *
     * @return bool True on success, false on failure
     */
    public static function seedSampleData(): bool
    {
        try {
            $db = Database::getConnection();
            
            // Check if table is empty
            $stmt = $db->query("SELECT COUNT(*) FROM faqs");
            $count = (int)$stmt->fetchColumn();
            
            if ($count > 0) {
                // Data already exists, no need to seed
                return true;
            }
            
            // Sample FAQs
            $sampleFaqs = [
                [
                    'question' => 'What is FCET Bichi COOPS?',
                    'answer' => 'FCET Bichi Staff Multipurpose Cooperative Society is a financial cooperative established to provide financial services and support to staff members of the Federal College of Education (Technical) Bichi.',
                    'category' => 'general',
                    'order' => 1
                ],
                [
                    'question' => 'How do I become a member?',
                    'answer' => 'To become a member, you need to fill out the registration form available on this website or visit our office. You\'ll need to provide your staff ID and other personal details.',
                    'category' => 'membership',
                    'order' => 1
                ],
                [
                    'question' => 'What types of loans do you offer?',
                    'answer' => 'We offer various loan types including personal loans, emergency loans, and household item financing with competitive interest rates for our members.',
                    'category' => 'loans',
                    'order' => 1
                ],
                [
                    'question' => 'How do I apply for a loan?',
                    'answer' => 'You can apply for a loan by logging into your member account, navigating to the Loans section, and completing the loan application form. Alternatively, you can visit our office for assistance.',
                    'category' => 'loans',
                    'order' => 2
                ],
                [
                    'question' => 'What are the admin charges for loans?',
                    'answer' => 'Our admin charges vary depending on the type of loan. Personal loans are typically offered at 5% admin charge, while emergency loans may have different rates. Check the specific loan details for accurate information.',
                    'category' => 'loans',
                    'order' => 3
                ]
            ];
            
            // Insert sample FAQs
            $sql = "INSERT INTO faqs (question, answer, created_at, updated_at, category, `order`, is_active) 
                   VALUES (:question, :answer, :created_at, :updated_at, :category, :order, :is_active)";
            
            $stmt = $db->prepare($sql);
            $now = date('Y-m-d H:i:s');
            
            foreach ($sampleFaqs as $faqData) {
                $stmt->bindValue(':question', $faqData['question']);
                $stmt->bindValue(':answer', $faqData['answer']);
                $stmt->bindValue(':created_at', $now);
                $stmt->bindValue(':updated_at', $now);
                $stmt->bindValue(':category', $faqData['category']);
                $stmt->bindValue(':order', $faqData['order'], PDO::PARAM_INT);
                $stmt->bindValue(':is_active', 1, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log('Error seeding FAQ data: ' . $e->getMessage());
            return false;
        }
    }
} 
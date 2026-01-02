<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Member model class
 */
final class Member
{
    /**
     * Properties
     */
    public ?int $id = null;
    public string $coop_no = '';
    public ?string $ti_number = null;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $address = null;
    public ?int $department_id = null;
    public ?string $department = null;
    public ?string $staff_id = null;
    public ?string $registration_date = null;
    public ?string $profile_image = null;
    public float $savings_balance = 0.0;
    public float $loan_balance = 0.0;
    public float $household_balance = 0.0;
    public float $shares_balance = 0.0;
    public string $status = 'pending';
    public bool $is_active = false;
    public bool $is_locked = false;
    public int $failed_attempts = 0;
    public bool $email_verified = false;
    public ?string $verification_token = null;
    public ?string $last_login = null;
    public string $password = '';
    public string $created_at = '';
    public string $updated_at = '';
    
    // Bank account properties
    public ?string $account_number = null;
    public ?string $bank_name = null;
    public ?string $account_name = null;
    public ?string $bvn = null;
    
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
     * Save member to database (insert or update)
     *
     * @return bool True on success, false on failure
     */
    public function save(): bool
    {
        try {
            // Combine first_name and last_name to create the name field
            $fullName = trim($this->first_name . ' ' . $this->last_name);
            
            if ($this->id) {
                // Update existing member
                $sql = "UPDATE members SET 
                    coop_no = :coop_no,
                    ti_number = :ti_number,
                    name = :name,
                    email = :email,
                    phone = :phone,
                    address = :address,
                    department_id = :department_id,
                    profile_image = :profile_image,
                    is_active = :is_active,
                    is_locked = :is_locked,
                    failed_attempts = :failed_attempts,
                    email_verified = :email_verified,
                    verification_token = :verification_token,
                    last_login = :last_login,
                    password = :password,
                    account_number = :account_number,
                    bank_name = :bank_name,
                    account_name = :account_name,
                    bvn = :bvn,
                    updated_at = NOW()
                WHERE id = :id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            } else {
                // Insert new member
                $sql = "INSERT INTO members (
                    coop_no, ti_number, name, email, phone, address, 
                    department_id, profile_image, is_active, is_locked, failed_attempts,
                    email_verified, verification_token, last_login, password, 
                    account_number, bank_name, account_name, bvn,
                    created_at, updated_at
                ) VALUES (
                    :coop_no, :ti_number, :name, :email, :phone, :address,
                    :department_id, :profile_image, :is_active, :is_locked, :failed_attempts,
                    :email_verified, :verification_token, :last_login, :password,
                    :account_number, :bank_name, :account_name, :bvn,
                    NOW(), NOW()
                )";
                
                $stmt = $this->db->prepare($sql);
            }
            
            // Bind parameters
            $stmt->bindValue(':coop_no', $this->coop_no);
            $stmt->bindValue(':ti_number', $this->ti_number);
            $stmt->bindValue(':name', $fullName);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':phone', $this->phone);
            $stmt->bindValue(':address', $this->address);
            $stmt->bindValue(':department_id', $this->department_id, PDO::PARAM_INT);
            $stmt->bindValue(':profile_image', $this->profile_image);
            $stmt->bindValue(':is_active', $this->is_active, PDO::PARAM_BOOL);
            $stmt->bindValue(':is_locked', $this->is_locked, PDO::PARAM_BOOL);
            $stmt->bindValue(':failed_attempts', $this->failed_attempts, PDO::PARAM_INT);
            $stmt->bindValue(':email_verified', $this->email_verified, PDO::PARAM_BOOL);
            $stmt->bindValue(':verification_token', $this->verification_token);
            $stmt->bindValue(':last_login', $this->last_login);
            $stmt->bindValue(':password', $this->password);
            $stmt->bindValue(':account_number', $this->account_number);
            $stmt->bindValue(':bank_name', $this->bank_name);
            $stmt->bindValue(':account_name', $this->account_name);
            $stmt->bindValue(':bvn', $this->bvn);
            
            $result = $stmt->execute();
            
            if (!$this->id && $result) {
                $this->id = (int)$this->db->lastInsertId();
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log('Error saving member: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find a member by ID
     *
     * @param int $id Member ID
     * @return Member|null Member object or null if not found
     */
    public static function findById(int $id): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find a member by cooperative number
     *
     * @param string $coop_no Cooperative number
     * @return Member|null Member object or null if not found
     */
    public static function findByCoopNo(string $coop_no): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE coop_no = :coop_no");
            $stmt->bindValue(':coop_no', $coop_no);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by cooperative number: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find a member by staff ID
     *
     * @param string $staff_id Staff ID
     * @return Member|null Member object or null if not found
     */
    public static function findByStaffId(string $staff_id): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE staff_id = :staff_id");
            $stmt->bindValue(':staff_id', $staff_id);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by staff ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find a member by email
     *
     * @param string $email Email address
     * @return Member|null Member object or null if not found
     */
    public static function findByEmail(string $email): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by email: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find a member by phone number
     *
     * @param string $phone Phone number
     * @return Member|null Member object or null if not found
     */
    public static function findByPhone(string $phone): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE phone = :phone");
            $stmt->bindValue(':phone', $phone);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by phone: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find a member by remember token
     *
     * @param string $token Remember token
     * @return Member|null Member object or null if not found
     */
    public static function findByRememberToken(string $token): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE remember_token = :token");
            $stmt->bindValue(':token', $token);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by remember token: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all members
     * 
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @param string $status Filter by status (optional - 'active' or 'pending')
     * @return array Array of Member objects
     */
    public static function getAll(int $limit = 100, int $offset = 0, string $status = ''): array
    {
        try {
            $db = Database::getConnection();
            
            if ($status) {
                // Convert status to is_active boolean value
                $isActive = ($status === 'active') ? 1 : 0;
                $sql = "SELECT * FROM members WHERE is_active = :is_active ORDER BY id DESC LIMIT :limit OFFSET :offset";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);
            } else {
                $sql = "SELECT * FROM members ORDER BY id DESC LIMIT :limit OFFSET :offset";
                $stmt = $db->prepare($sql);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $members = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $members[] = self::createFromArray($data);
            }
            
            return $members;
        } catch (\PDOException $e) {
            error_log('Error getting all members: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get count of members
     *
     * @param string $status Filter by status (optional - 'active' or 'pending')
     * @return int Number of members
     */
    public static function getCount(string $status = ''): int
    {
        try {
            $db = Database::getConnection();
            
            if ($status) {
                // Convert status to is_active boolean value
                $isActive = ($status === 'active') ? 1 : 0;
                $sql = "SELECT COUNT(*) FROM members WHERE is_active = :is_active";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);
            } else {
                $sql = "SELECT COUNT(*) FROM members";
                $stmt = $db->prepare($sql);
            }
            
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('Error getting member count: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get the last registered member
     *
     * @return Member|null The last member or null if no members
     */
    public static function getLastMember(): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error getting last member: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Search members
     *
     * @param string $query Search query
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of Member objects
     */
    public static function search(string $query, int $limit = 20, int $offset = 0): array
    {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT * FROM members
                    WHERE coop_no LIKE :query 
                    OR staff_id LIKE :query 
                    OR first_name LIKE :query 
                    OR last_name LIKE :query 
                    OR email LIKE :query 
                    OR phone LIKE :query
                    OR department LIKE :query
                    LIMIT :limit OFFSET :offset";
                    
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':query', "%$query%");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $members = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $members[] = self::createFromArray($data);
            }
            
            return $members;
        } catch (\PDOException $e) {
            error_log('Error searching members: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get full name
     *
     * @return string Full name
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * Create a member object from an array
     *
     * @param array $data Member data
     * @return Member New Member object
     */
    private static function createFromArray(array $data): self
    {
        $member = new self();
        $member->id = (int)$data['id'];
        $member->coop_no = $data['coop_no'];
        $member->ti_number = $data['ti_number'] ?? null;
        
        // Handle single name field from database
        if (isset($data['name']) && !isset($data['first_name'])) {
            // Split name into first and last name
            $nameParts = explode(' ', $data['name'], 2);
            $member->first_name = $nameParts[0];
            $member->last_name = isset($nameParts[1]) ? $nameParts[1] : '';
        } else {
            // Use first_name and last_name if they exist
            $member->first_name = $data['first_name'] ?? '';
            $member->last_name = $data['last_name'] ?? '';
        }
        
        $member->email = $data['email'];
        $member->phone = $data['phone'] ?? null;
        $member->address = $data['address'] ?? null;
        $member->department_id = isset($data['department_id']) ? (int)$data['department_id'] : null;
        $member->department = $data['department'] ?? null;
        $member->staff_id = $data['staff_id'] ?? null;
        $member->registration_date = $data['registration_date'] ?? $data['created_at'] ?? null;
        $member->profile_image = $data['profile_image'] ?? null;
        $member->savings_balance = (float)($data['savings_balance'] ?? 0.0);
        $member->loan_balance = (float)($data['loan_balance'] ?? 0.0);
        $member->household_balance = (float)($data['household_balance'] ?? 0.0);
        $member->shares_balance = (float)($data['shares_balance'] ?? 0.0);
        $member->is_active = (bool)($data['is_active'] ?? false);
        $member->status = $member->is_active ? 'active' : 'pending';
        $member->is_locked = (bool)($data['is_locked'] ?? false);
        $member->failed_attempts = (int)($data['failed_attempts'] ?? 0);
        $member->email_verified = (bool)($data['email_verified'] ?? false);
        $member->verification_token = $data['verification_token'] ?? null;
        $member->last_login = $data['last_login'] ?? null;
        $member->password = $data['password'] ?? '';
        $member->created_at = $data['created_at'] ?? '';
        $member->updated_at = $data['updated_at'] ?? '';
        
        // Bank account information
        $member->account_number = $data['account_number'] ?? null;
        $member->bank_name = $data['bank_name'] ?? null;
        $member->account_name = $data['account_name'] ?? null;
        $member->bvn = $data['bvn'] ?? null;
        
        return $member;
    }
    
    /**
     * Get member's financial summary
     * 
     * @param int $member_id Member ID
     * @return array Financial summary
     */
    public static function getFinancialSummary(int $member_id): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT 
                    savings_balance,
                    loan_balance,
                    household_balance,
                    shares_balance
                FROM members
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $member_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return [
                    'savings_balance' => (float)$data['savings_balance'],
                    'loan_balance' => (float)$data['loan_balance'],
                    'household_balance' => (float)$data['household_balance'],
                    'shares_balance' => (float)($data['shares_balance'] ?? 0.0)
                ];
            }
            
            return [
                'savings_balance' => 0.0,
                'loan_balance' => 0.0,
                'household_balance' => 0.0,
                'shares_balance' => 0.0
            ];
        } catch (\PDOException $e) {
            error_log('Error getting financial summary: ' . $e->getMessage());
            return [
                'savings_balance' => 0.0,
                'loan_balance' => 0.0,
                'household_balance' => 0.0,
                'shares_balance' => 0.0
            ];
        }
    }
    
    /**
     * Find a member by TI Number
     *
     * @param string $ti_number Treasury Integrated Number
     * @return Member|null Member object or null if not found
     */
    public static function findByTiNumber(string $ti_number): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM members WHERE ti_number = :ti_number");
            $stmt->bindValue(':ti_number', $ti_number);
            $stmt->execute();
            
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                return self::createFromArray($data);
            }
            
            return null;
        } catch (\PDOException $e) {
            error_log('Error finding member by TI Number: ' . $e->getMessage());
            return null;
        }
    }
} 
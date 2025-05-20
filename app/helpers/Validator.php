<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Validator helper for input validation
 */
final class Validator
{
    /**
     * Store validation errors
     *
     * @var array
     */
    private static array $errors = [];
    
    /**
     * Get validation errors
     *
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }
    
    /**
     * Check if there are validation errors
     *
     * @return bool
     */
    public static function hasErrors(): bool
    {
        return !empty(self::$errors);
    }
    
    /**
     * Reset validation errors
     *
     * @return void
     */
    public static function resetErrors(): void
    {
        self::$errors = [];
    }
    
    /**
     * Add an error message
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    private static function addError(string $field, string $message): void
    {
        self::$errors[$field] = $message;
    }
    
    /**
     * Validate required fields
     *
     * @param array $data
     * @param array $fields
     * @return bool
     */
    public static function required(array $data, array $fields): bool
    {
        $valid = true;
        
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                self::addError($field, ucfirst(str_replace('_', ' ', $field)) . ' is required');
                $valid = false;
            }
        }
        
        return $valid;
    }
    
    /**
     * Validate minimum length
     *
     * @param array $data
     * @param string $field
     * @param int $min
     * @return bool
     */
    public static function minLength(array $data, string $field, int $min): bool
    {
        if (!isset($data[$field])) {
            return false;
        }
        
        if (strlen($data[$field]) < $min) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least $min characters");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate maximum length
     *
     * @param array $data
     * @param string $field
     * @param int $max
     * @return bool
     */
    public static function maxLength(array $data, string $field, int $max): bool
    {
        if (!isset($data[$field])) {
            return false;
        }
        
        if (strlen($data[$field]) > $max) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed $max characters");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate email
     *
     * @param array $data
     * @param string $field
     * @return bool
     */
    public static function email(array $data, string $field): bool
    {
        if (!isset($data[$field])) {
            return false;
        }
        
        if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            self::addError($field, 'Please enter a valid email address');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate numeric value
     *
     * @param array $data
     * @param string $field
     * @return bool
     */
    public static function numeric(array $data, string $field): bool
    {
        if (!isset($data[$field])) {
            return false;
        }
        
        if (!is_numeric($data[$field])) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a number');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate minimum numeric value
     *
     * @param array $data
     * @param string $field
     * @param float $min
     * @return bool
     */
    public static function min(array $data, string $field, float $min): bool
    {
        if (!isset($data[$field]) || !is_numeric($data[$field])) {
            return false;
        }
        
        if ((float)$data[$field] < $min) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least $min");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate maximum numeric value
     *
     * @param array $data
     * @param string $field
     * @param float $max
     * @return bool
     */
    public static function max(array $data, string $field, float $max): bool
    {
        if (!isset($data[$field]) || !is_numeric($data[$field])) {
            return false;
        }
        
        if ((float)$data[$field] > $max) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed $max");
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate pattern with regular expression
     *
     * @param array $data
     * @param string $field
     * @param string $pattern
     * @param string $message
     * @return bool
     */
    public static function pattern(array $data, string $field, string $pattern, string $message): bool
    {
        if (!isset($data[$field])) {
            return false;
        }
        
        if (!preg_match($pattern, $data[$field])) {
            self::addError($field, $message);
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate password confirmation
     *
     * @param array $data
     * @param string $password1
     * @param string $password2
     * @return bool
     */
    public static function passwordConfirmation(array $data, string $password1, string $password2): bool
    {
        if (!isset($data[$password1]) || !isset($data[$password2])) {
            return false;
        }
        
        if ($data[$password1] !== $data[$password2]) {
            self::addError($password2, 'Password confirmation does not match');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate unique field in database
     *
     * @param string $table
     * @param string $field
     * @param mixed $value
     * @param int|null $excludeId
     * @return bool
     */
    public static function unique(string $table, string $field, mixed $value, ?int $excludeId = null): bool
    {
        $query = "SELECT COUNT(*) as count FROM $table WHERE $field = ?";
        $params = [$value];
        
        if ($excludeId !== null) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = Database::fetchOne($query, $params);
        
        if ($result && $result['count'] > 0) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . ' is already taken');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate if value exists in database
     *
     * @param string $table
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public static function exists(string $table, string $field, mixed $value): bool
    {
        $query = "SELECT COUNT(*) as count FROM $table WHERE $field = ?";
        $result = Database::fetchOne($query, [$value]);
        
        if (!$result || $result['count'] == 0) {
            self::addError($field, ucfirst(str_replace('_', ' ', $field)) . ' does not exist');
            return false;
        }
        
        return true;
    }
    
    /**
     * Sanitize input data
     *
     * @param array $data
     * @return array
     */
    public static function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove HTML tags and strip whitespace
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Validate an email address
     *
     * @param string $email The email to validate
     * @return bool True if valid, false otherwise
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate a phone number (Nigerian format)
     *
     * @param string $phone The phone number to validate
     * @return bool True if valid, false otherwise
     */
    public static function isValidPhone(string $phone): bool
    {
        // Allow +234 format or 0 starting format
        return preg_match('/^(\+234|0)[0-9]{10}$/', $phone) === 1;
    }
    
    /**
     * Check if a password is strong enough
     * Must contain at least 8 characters, one uppercase, one lowercase, and one number
     *
     * @param string $password The password to validate
     * @return bool True if strong, false otherwise
     */
    public static function isStrongPassword(string $password): bool
    {
        // Check for minimum length
        if (strlen($password) < 8) {
            return false;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate a date in Y-m-d format
     *
     * @param string $date The date to validate
     * @return bool True if valid, false otherwise
     */
    public static function isValidDate(string $date): bool
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateTime && $dateTime->format('Y-m-d') === $date;
    }
    
    /**
     * Validate a decimal amount (for currency)
     *
     * @param string $amount The amount to validate
     * @return bool True if valid, false otherwise
     */
    public static function isValidAmount(string $amount): bool
    {
        return preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $amount) === 1;
    }
    
    /**
     * Sanitize a string by removing tags and special characters
     *
     * @param string $input The input to sanitize
     * @return string The sanitized input
     */
    public static function sanitizeString(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitize an email address
     *
     * @param string $email The email to sanitize
     * @return string The sanitized email
     */
    public static function sanitizeEmail(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Format an amount as currency
     *
     * @param float $amount The amount to format
     * @return string The formatted amount
     */
    public static function formatCurrency(float $amount): string
    {
        return '₦' . number_format($amount, 2);
    }
    
    /**
     * Validate and sanitize inputs
     *
     * @param array $data The input data
     * @param array $rules The validation rules
     * @return array The validated and sanitized data
     */
    public static function validateInputs(array $data, array $rules): array
    {
        $validData = [];
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            // Check required fields
            if (isset($rule['required']) && $rule['required'] && (is_null($value) || $value === '')) {
                $errors[$field] = $rule['message'] ?? "$field is required";
                continue;
            }
            
            // Skip validation if field is not required and empty
            if ((!isset($rule['required']) || !$rule['required']) && (is_null($value) || $value === '')) {
                continue;
            }
            
            // Validate based on type
            switch ($rule['type'] ?? '') {
                case 'email':
                    if (!self::isValidEmail($value)) {
                        $errors[$field] = $rule['message'] ?? "Please enter a valid email address";
                    } else {
                        $validData[$field] = self::sanitizeEmail($value);
                    }
                    break;
                
                case 'phone':
                    if (!self::isValidPhone($value)) {
                        $errors[$field] = $rule['message'] ?? "Please enter a valid phone number";
                    } else {
                        $validData[$field] = $value;
                    }
                    break;
                
                case 'password':
                    if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                        $errors[$field] = $rule['message'] ?? "Password must be at least {$rule['min_length']} characters";
                    } elseif (isset($rule['strong']) && $rule['strong'] && !self::isStrongPassword($value)) {
                        $errors[$field] = $rule['message'] ?? "Password must include uppercase, lowercase, and numbers";
                    } else {
                        $validData[$field] = $value;
                    }
                    break;
                
                case 'date':
                    if (!self::isValidDate($value)) {
                        $errors[$field] = $rule['message'] ?? "Please enter a valid date (YYYY-MM-DD)";
                    } else {
                        $validData[$field] = $value;
                    }
                    break;
                
                case 'amount':
                    if (!self::isValidAmount($value)) {
                        $errors[$field] = $rule['message'] ?? "Please enter a valid amount";
                    } else {
                        $validData[$field] = (float)$value;
                    }
                    break;
                
                case 'number':
                    if (!is_numeric($value)) {
                        $errors[$field] = $rule['message'] ?? "Please enter a valid number";
                    } else {
                        $validData[$field] = (float)$value;
                    }
                    break;
                
                case 'boolean':
                    $validData[$field] = (bool)$value;
                    break;
                
                default:
                    // String validation
                    if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                        $errors[$field] = $rule['message'] ?? "$field must be at least {$rule['min_length']} characters";
                    } elseif (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                        $errors[$field] = $rule['message'] ?? "$field must be at most {$rule['max_length']} characters";
                    } else {
                        $validData[$field] = self::sanitizeString($value);
                    }
                    break;
            }
        }
        
        return ['data' => $validData, 'errors' => $errors];
    }
} 
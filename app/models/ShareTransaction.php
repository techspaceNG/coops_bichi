<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ShareTransaction
{
    private int $id;
    private int $share_id;
    private string $transaction_type;
    private int $quantity;
    private float $unit_price;
    private float $total_amount;
    private string $transaction_date;
    private string $description;
    private string $created_at;
    private string $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->share_id = isset($data['share_id']) ? (int)$data['share_id'] : 0;
        $this->transaction_type = $data['transaction_type'] ?? '';
        
        // Check if it's a units/unit_value schema or quantity/unit_price schema
        if (isset($data['units'])) {
            $this->quantity = isset($data['units']) ? (int)$data['units'] : 0;
        } else {
            $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
        }
        
        if (isset($data['unit_value'])) {
            $this->unit_price = isset($data['unit_value']) ? (float)$data['unit_value'] : 0.00;
        } else {
            $this->unit_price = isset($data['unit_price']) ? (float)$data['unit_price'] : 0.00;
        }
        
        $this->total_amount = isset($data['total_amount']) ? (float)$data['total_amount'] : 0.00;
        $this->transaction_date = $data['transaction_date'] ?? date('Y-m-d');
        
        // Support both description and notes fields
        if (isset($data['notes'])) {
            $this->description = $data['notes'] ?? '';
        } else {
            $this->description = $data['description'] ?? '';
        }
        
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public static function findById(int $id): ?self
    {
        $query = "
            SELECT 
                id, share_id, member_id, transaction_type,
                units as quantity, unit_value as unit_price,
                total_amount, processed_by, notes as description,
                transaction_date, created_at, updated_at
            FROM share_transactions 
            WHERE id = ?";
        $result = Database::fetchOne($query, [$id]);
        
        return $result ? new self($result) : null;
    }

    public static function findByShareId(int $share_id): array
    {
        $query = "
            SELECT 
                id, share_id, member_id, transaction_type,
                units as quantity, unit_value as unit_price,
                total_amount, processed_by, notes as description,
                transaction_date, created_at, updated_at
            FROM share_transactions 
            WHERE share_id = ? 
            ORDER BY transaction_date DESC";
        $results = Database::fetchAll($query, [$share_id]);
        
        return array_map(fn($data) => new self($data), $results);
    }

    public function save(): bool
    {
        if ($this->id > 0) {
            return $this->update();
        }
        
        return $this->insert();
    }

    private function insert(): bool
    {
        $query = "
            INSERT INTO share_transactions (
                share_id, transaction_type, units, unit_value, total_amount,
                transaction_date, notes, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $params = [
            $this->share_id,
            $this->transaction_type,
            $this->quantity,
            $this->unit_price,
            $this->total_amount,
            $this->transaction_date,
            $this->description,
            $this->created_at,
            $this->updated_at
        ];
        
        $result = Database::execute($query, $params);
        
        if ($result) {
            $this->id = (int)Database::lastInsertId();
        }
        
        return $result;
    }

    private function update(): bool
    {
        $query = "
            UPDATE share_transactions SET
                transaction_type = ?,
                units = ?,
                unit_value = ?,
                total_amount = ?,
                transaction_date = ?,
                notes = ?,
                updated_at = ?
            WHERE id = ?
        ";
        
        $params = [
            $this->transaction_type,
            $this->quantity,
            $this->unit_price,
            $this->total_amount,
            $this->transaction_date,
            $this->description,
            date('Y-m-d H:i:s'),
            $this->id
        ];
        
        return Database::execute($query, $params);
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getShareId(): int { return $this->share_id; }
    public function getTransactionType(): string { return $this->transaction_type; }
    public function getQuantity(): int { return $this->quantity; }
    public function getPrice(): float { return $this->unit_price; }
    public function getTotalAmount(): float { return $this->total_amount; }
    public function getTransactionDate(): string { return $this->transaction_date; }
    public function getDescription(): string { return $this->description; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }

    // Setters
    public function setTransactionType(string $type): void { $this->transaction_type = $type; }
    public function setQuantity(int $quantity): void { $this->quantity = $quantity; }
    public function setPrice(float $price): void { $this->unit_price = $price; }
    public function setTotalAmount(float $amount): void { $this->total_amount = $amount; }
    public function setTransactionDate(string $date): void { $this->transaction_date = $date; }
    public function setDescription(string $description): void { $this->description = $description; }
} 
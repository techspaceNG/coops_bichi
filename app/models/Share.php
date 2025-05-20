<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Models\ShareTransaction;

final class Share
{
    private int $id;
    private int $member_id;
    private string $share_type;
    private int $quantity;
    private float $unit_price;
    private float $total_value;
    private string $purchase_date;
    private string $status;
    private ?string $sale_date = null;
    private ?float $sale_price = null;
    private string $created_at;
    private string $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->member_id = isset($data['member_id']) ? (int)$data['member_id'] : 0;
        $this->share_type = $data['share_type'] ?? 'ordinary';
        $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
        $this->unit_price = isset($data['unit_price']) ? (float)$data['unit_price'] : 0.00;
        $this->total_value = isset($data['total_value']) ? (float)$data['total_value'] : 0.00;
        $this->purchase_date = $data['purchase_date'] ?? date('Y-m-d');
        $this->status = $data['status'] ?? 'active';
        $this->sale_date = $data['sale_date'] ?? null;
        $this->sale_price = isset($data['sale_price']) ? (float)$data['sale_price'] : null;
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }

    public static function findById(int $id): ?self
    {
        $query = "SELECT * FROM shares WHERE id = ?";
        $result = Database::fetchOne($query, [$id]);
        
        return $result ? new self($result) : null;
    }

    public static function findByMemberId(int $member_id): array
    {
        $query = "SELECT * FROM shares WHERE member_id = ? ORDER BY purchase_date DESC";
        $results = Database::fetchAll($query, [$member_id]);
        
        return array_map(fn($data) => new self($data), $results);
    }

    public static function getMemberTotalShares(int $member_id): float
    {
        $query = "SELECT COALESCE(SUM(total_value), 0) as total FROM shares WHERE member_id = ? AND status = 'active'";
        $result = Database::fetchOne($query, [$member_id]);
        
        return (float)($result['total'] ?? 0);
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
        // Calculate total_value to ensure it's correct
        $this->total_value = $this->quantity * $this->unit_price;
        
        $query = "
            INSERT INTO shares (
                member_id, share_type, units, unit_value,
                purchase_date, status, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $params = [
            $this->member_id,
            $this->share_type,
            $this->quantity, // Insert into units column
            $this->unit_price, // Insert into unit_value column
            $this->purchase_date,
            $this->status,
            $this->created_at,
            $this->updated_at
        ];
        
        $result = Database::execute($query, $params);
        
        if ($result) {
            $this->id = (int)Database::lastInsertId();
            $this->updateMemberSharesBalance();
        }
        
        return $result;
    }

    private function update(): bool
    {
        // Calculate total_value to ensure it's correct
        $this->total_value = $this->quantity * $this->unit_price;
        
        $query = "
            UPDATE shares SET
                share_type = ?,
                units = ?,
                unit_value = ?,
                purchase_date = ?,
                status = ?,
                updated_at = ?
            WHERE id = ?
        ";
        
        $params = [
            $this->share_type,
            $this->quantity, // Update units column
            $this->unit_price, // Update unit_value column
            $this->purchase_date,
            $this->status,
            date('Y-m-d H:i:s'),
            $this->id
        ];
        
        $result = Database::execute($query, $params);
        
        if ($result) {
            $this->updateMemberSharesBalance();
        }
        
        return $result;
    }

    private function updateMemberSharesBalance(): void
    {
        $total_shares = self::getMemberTotalShares($this->member_id);
        
        $query = "UPDATE members SET shares_balance = ? WHERE id = ?";
        Database::execute($query, [$total_shares, $this->member_id]);
    }

    public function recordTransaction(string $type, int $quantity, float $unit_price, string $description = ''): bool
    {
        $query = "
            INSERT INTO share_transactions (
                share_id, member_id, transaction_type, units, unit_value, total_amount,
                transaction_date, notes, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $total_amount = $quantity * $unit_price;
        
        $params = [
            $this->id,
            $this->member_id,
            $type,
            $quantity,
            $unit_price,
            $total_amount,
            date('Y-m-d'),
            $description,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ];
        
        return Database::execute($query, $params);
    }

    public function getTransactions(): array
    {
        return ShareTransaction::findByShareId($this->id);
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getMemberId(): int { return $this->member_id; }
    public function getShareType(): string { return $this->share_type; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unit_price; }
    public function getTotalValue(): float { return $this->total_value; }
    public function getPurchaseDate(): string { return $this->purchase_date; }
    public function getStatus(): string { return $this->status; }
    public function getSaleDate(): ?string { return $this->sale_date; }
    public function getSalePrice(): ?float { return $this->sale_price; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }

    // Setters
    public function setShareType(string $type): void { $this->share_type = $type; }
    public function setQuantity(int $quantity): void { $this->quantity = $quantity; }
    public function setUnitPrice(float $price): void { $this->unit_price = $price; }
    public function setTotalValue(float $value): void { $this->total_value = $value; }
    public function setPurchaseDate(string $date): void { $this->purchase_date = $date; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setSaleDate(?string $date): void { $this->sale_date = $date; }
    public function setSalePrice(?float $price): void { $this->sale_price = $price; }
} 
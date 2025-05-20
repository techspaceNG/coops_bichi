<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;

/**
 * LogsController for Superadmin
 * Handles system logs functionality
 */
final class LogsController extends AbstractController
{
    /**
     * Display system logs
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 50;
        $logType = $_GET['type'] ?? 'all';
        
        // Build query conditions
        $conditions = [];
        $params = [];
        
        if ($logType !== 'all') {
            $conditions[] = "action_type = ?";
            $params[] = $logType;
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Count total records
        $countQuery = "SELECT COUNT(*) as total FROM audit_logs {$whereClause}";
        $countResult = Database::fetchOne($countQuery, $params);
        $total = (int)($countResult['total'] ?? 0);
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get logs
        $query = "
            SELECT 
                al.*,
                CASE
                    WHEN al.user_type = 'admin' THEN (SELECT name FROM admin_users WHERE id = al.user_id)
                    WHEN al.user_type = 'member' THEN (SELECT name FROM members WHERE id = al.user_id)
                    ELSE 'System'
                END as user_name
            FROM 
                audit_logs al
            {$whereClause}
            ORDER BY 
                al.timestamp DESC
            LIMIT 
                {$offset}, {$perPage}
        ";
        
        $logs = Database::fetchAll($query, $params);
        
        // Get log type counts for filtering
        $logTypeCounts = [];
        $logTypeQuery = "
            SELECT 
                action_type, 
                COUNT(*) as count
            FROM 
                audit_logs
            GROUP BY 
                action_type
            ORDER BY 
                count DESC
        ";
        
        $logTypeResults = Database::fetchAll($logTypeQuery);
        foreach ($logTypeResults as $result) {
            $logTypeCounts[$result['action_type']] = $result['count'];
        }
        
        $this->renderSuperAdmin('superadmin/system-logs', [
            'logs' => $logs,
            'logTypeCounts' => $logTypeCounts,
            'pagination' => [
                'current_page' => $page,
                'last_page' => $totalPages,
                'total' => $total,
                'per_page' => $perPage
            ],
            'filters' => [
                'type' => $logType
            ],
            'current_page' => 'system_logs',
            'pageTitle' => 'System Logs'
        ]);
    }
} 
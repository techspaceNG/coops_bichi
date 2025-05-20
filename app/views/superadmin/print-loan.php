<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php 
        if (isset($is_initial_balance) && $is_initial_balance) {
            echo 'Initial Balance';
        } elseif (isset($is_application) && $is_application) {
            echo 'Loan Application';
        } else {
            echo 'Loan';
        }
    ?> #<?php echo htmlspecialchars($loan['display_id'] ?? $loan['id'] ?? 'N/A'); ?> - FCET Bichi Staff Cooperative</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .header p {
            margin-top: 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .col {
            flex: 1;
        }
        .label {
            font-weight: bold;
            margin-right: 10px;
        }
        .value {
            color: #333;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 0.8em;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .pending { background-color: #FFF3CD; color: #856404; }
        .approved { background-color: #D4EDDA; color: #155724; }
        .rejected, .declined { background-color: #F8D7DA; color: #721C24; }
        .completed { background-color: #D1ECF1; color: #0C5460; }
        .initial_balance { background-color: #E2E3E5; color: #383d41; }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Validate that we have loan data
    if (empty($loan) || !isset($loan['id'])) {
        echo '<div style="text-align: center; margin: 50px; color: red;">';
        echo '<h1>Error: Loan data not found</h1>';
        echo '<p>Unable to display loan details. Please go back and try again.</p>';
        echo '<button onclick="window.close();">Close</button>';
        echo '</div>';
        exit;
    }
    ?>
    
    <div class="header">
        <h1>FCET BICHI STAFF COOPERATIVE SOCIETY</h1>
        <p><?php 
            if (isset($is_initial_balance) && $is_initial_balance) {
                echo 'Initial Loan Balance';
            } elseif (isset($is_application) && $is_application) {
                echo 'Loan Application';
            } else {
                echo 'Loan';
            }
        ?> Receipt - ID: <?php echo htmlspecialchars($loan['display_id'] ?? $loan['id']); ?></p>
    </div>
    
    <div class="section">
        <h2>Loan Information</h2>
        <div class="row">
            <div class="col">
                <span class="label">Loan ID:</span>
                <span class="value"><?php echo htmlspecialchars($loan['display_id'] ?? $loan['id'] ?? 'N/A'); ?></span>
            </div>
            <div class="col">
                <span class="label">Status:</span>
                <span class="value">
                    <?php
                    $statusClass = '';
                    $status = $loan['status'] ?? 'unknown';
                    switch($status) {
                        case 'pending': $statusClass = 'pending'; break;
                        case 'approved': $statusClass = 'approved'; break;
                        case 'rejected': $statusClass = 'rejected'; break;
                        case 'declined': $statusClass = 'declined'; break;
                        case 'completed': $statusClass = 'completed'; break;
                        case 'initial_balance': $statusClass = 'initial_balance'; break;
                        default: $statusClass = ''; break;
                    }
                    
                    // Normalize status text for display
                    $statusText = $status;
                    if ($statusText === 'declined' || $statusText === 'rejected') {
                        $statusText = 'Rejected';
                    } elseif ($statusText === 'initial_balance') {
                        $statusText = 'Initial Balance';
                    } else {
                        $statusText = ucfirst($statusText);
                    }
                    ?>
                    <span class="status <?php echo $statusClass; ?>">
                        <?php echo htmlspecialchars($statusText); ?>
                    </span>
                </span>
            </div>
        </div>
        <?php if (!isset($is_initial_balance) || !$is_initial_balance): ?>
        <div class="row">
            <div class="col">
                <span class="label">Application Date:</span>
                <span class="value"><?php echo isset($loan['created_at']) ? date('F j, Y', strtotime($loan['created_at'])) : 'N/A'; ?></span>
            </div>
            <?php if (isset($loan['approval_date']) && !empty($loan['approval_date'])): ?>
            <div class="col">
                <span class="label">Approval Date:</span>
                <span class="value"><?php echo date('F j, Y', strtotime($loan['approval_date'])); ?></span>
            </div>
            <?php elseif (isset($loan['updated_at']) && !empty($loan['updated_at']) && ($loan['status'] === 'rejected' || $loan['status'] === 'declined' || $loan['status'] === 'approved')): ?>
            <div class="col">
                <span class="label"><?php echo ($loan['status'] === 'rejected' || $loan['status'] === 'declined') ? 'Rejection' : 'Approval'; ?> Date:</span>
                <span class="value"><?php echo date('F j, Y', strtotime($loan['updated_at'])); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($is_initial_balance) && $is_initial_balance): ?>
        <div class="row">
            <div class="col">
                <span class="label">Record Date:</span>
                <span class="value"><?php echo isset($loan['created_at']) ? date('F j, Y', strtotime($loan['created_at'])) : date('F j, Y'); ?></span>
            </div>
            <div class="col">
                <span class="label">Balance Type:</span>
                <span class="value">Initial Loan Record</span>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (isset($loan['comment']) && !empty($loan['comment'])): ?>
        <div class="row">
            <div class="col">
                <span class="label"><?php echo ($loan['status'] === 'rejected' || $loan['status'] === 'declined') ? 'Rejection Reason:' : 'Comment:'; ?></span>
                <span class="value"><?php echo nl2br(htmlspecialchars($loan['comment'])); ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h2>Member Information</h2>
        <div class="row">
            <div class="col">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($loan['member_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="col">
                <span class="label">COOPS No:</span>
                <span class="value"><?php echo htmlspecialchars($loan['coop_no'] ?? 'N/A'); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <span class="label">Department:</span>
                <span class="value"><?php echo htmlspecialchars($loan['department_name'] ?? 'Not specified'); ?></span>
            </div>
            <div class="col">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($loan['email'] ?? $loan['member_email'] ?? 'Not provided'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="section">
        <h2>Financial Details</h2>
        <table>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td><?php echo (isset($is_initial_balance) && $is_initial_balance) ? 'Initial Balance' : 'Loan Amount'; ?></td>
                <td>₦<?php echo isset($loan['loan_amount']) && $loan['loan_amount'] !== null ? number_format($loan['loan_amount'], 2) : '0.00'; ?></td>
            </tr>
            <?php if (!isset($is_initial_balance) || !$is_initial_balance): ?>
            <tr>
                <td>IP Figure</td>
                <td>₦<?php echo isset($loan['ip_figure']) && $loan['ip_figure'] !== null ? number_format($loan['ip_figure'], 2) : '0.00'; ?></td>
            </tr>
            <tr>
                <td>Admin Charges</td>
                <td><?php echo isset($loan['interest_rate']) && $loan['interest_rate'] !== null ? number_format($loan['interest_rate'], 2) : '0.00'; ?>%</td>
            </tr>
            <tr>
                <td>Repayment Period</td>
                <td><?php echo htmlspecialchars($loan['repayment_period'] ?? $loan['loan_duration'] ?? 'N/A'); ?> months</td>
            </tr>
            <tr>
                <td>Total Repayment</td>
                <td>₦<?php echo isset($loan['total_repayment']) && $loan['total_repayment'] !== null ? number_format($loan['total_repayment'], 2) : '0.00'; ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Current Balance</td>
                <td>₦<?php 
                    if (isset($is_initial_balance) && $is_initial_balance) {
                        echo isset($loan['loan_amount']) ? number_format($loan['loan_amount'], 2) : '0.00';
                    } else {
                        echo isset($remaining_balance) ? number_format($remaining_balance, 2) : (isset($loan['balance']) && $loan['balance'] !== null ? number_format($loan['balance'], 2) : '0.00');
                    }
                ?></td>
            </tr>
            <?php if (!isset($is_initial_balance) || !$is_initial_balance): ?>
            <tr>
                <td>Total Paid</td>
                <td>₦<?php echo isset($total_paid) ? number_format($total_paid, 2) : '0.00'; ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    
    <?php if (isset($loan['purpose']) && !empty($loan['purpose'])): ?>
    <div class="section">
        <h2>Loan Purpose</h2>
        <p><?php echo nl2br(htmlspecialchars($loan['purpose'])); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (isset($loan['processed_by_name']) && !empty($loan['processed_by_name'])): ?>
    <div class="section">
        <div class="row">
            <div class="col">
                <span class="label">Approved By:</span>
                <span class="value"><?php echo htmlspecialchars($loan['processed_by_name']); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($is_initial_balance) && $is_initial_balance): ?>
    <div class="section">
        <h2>Initial Balance Information</h2>
        <p>This is an initial loan balance record imported from previous accounting records. It represents an outstanding loan balance from a previous period.</p>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Printed on: <?php echo date('F j, Y, g:i a'); ?></p>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print();">Print Document</button>
        <button onclick="window.close();">Close</button>
    </div>
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html> 
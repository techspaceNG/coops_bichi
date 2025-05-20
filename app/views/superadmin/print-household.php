<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household Purchase #<?php echo htmlspecialchars($purchase['id']); ?> - FCET Bichi Staff Cooperative</title>
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
        .declined { background-color: #F8D7DA; color: #721C24; }
        .completed { background-color: #D1ECF1; color: #0C5460; }
        
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
    <div class="header">
        <h1>FCET BICHI STAFF COOPERATIVE SOCIETY</h1>
        <p>Household Purchase Application Receipt</p>
    </div>
    
    <div class="section">
        <h2>Purchase Information</h2>
        <div class="row">
            <div class="col">
                <span class="label">Purchase ID:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['id']); ?></span>
            </div>
            <div class="col">
                <span class="label">Reference Number:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['reference_number'] ?? 'N/A'); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <span class="label">Status:</span>
                <span class="value">
                    <?php
                    $statusClass = '';
                    switch($purchase['status']) {
                        case 'pending': $statusClass = 'pending'; break;
                        case 'approved': $statusClass = 'approved'; break;
                        case 'declined': $statusClass = 'declined'; break;
                        case 'completed': $statusClass = 'completed'; break;
                    }
                    ?>
                    <span class="status <?php echo $statusClass; ?>">
                        <?php echo ucfirst(htmlspecialchars($purchase['status'])); ?>
                    </span>
                </span>
            </div>
            <div class="col">
                <span class="label">Application Date:</span>
                <span class="value"><?php echo date('F j, Y', strtotime($purchase['created_at'])); ?></span>
            </div>
        </div>
    </div>
    
    <div class="section">
        <h2>Member Information</h2>
        <div class="row">
            <div class="col">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['member_name']); ?></span>
            </div>
            <div class="col">
                <span class="label">COOPS No:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['member_coop_no'] ?? 'N/A'); ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <span class="label">Department:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['department_name'] ?? 'Not specified'); ?></span>
            </div>
            <div class="col">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($purchase['member_email'] ?? 'Not provided'); ?></span>
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
                <td>Purchase Amount</td>
                <td>₦<?php echo number_format($purchase['amount'], 2); ?></td>
            </tr>
            <tr>
                <td>IP Figure</td>
                <td>₦<?php echo isset($purchase['ip_figure']) ? number_format($purchase['ip_figure'], 2) : 'N/A'; ?></td>
            </tr>
            <tr>
                <td><strong>Total Repayment (incl. 5% admin charges)</strong></td>
                <td><strong>₦<?php echo number_format($purchase['total_repayment'] ?? ($purchase['amount'] * 1.05), 2); ?></strong></td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td>₦<?php echo number_format($totalPaid ?? 0, 2); ?></td>
            </tr>
            <tr>
                <td>Remaining Balance</td>
                <td>₦<?php echo number_format($remainingBalance, 2); ?></td>
            </tr>
        </table>
    </div>
    
    <?php if (!empty($purchase['description'])): ?>
    <div class="section">
        <h2>Purchase Description</h2>
        <p><?php echo nl2br(htmlspecialchars($purchase['description'])); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($purchase['comment'])): ?>
    <div class="section">
        <h2>Additional Notes</h2>
        <p><?php echo nl2br(htmlspecialchars($purchase['comment'])); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <p>Printed on: <?php echo date('F j, Y, g:i a'); ?></p>
        <p>FCET Bichi Staff Cooperative Society &copy; <?php echo date('Y'); ?></p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Print Receipt</button>
        <button onclick="window.close()">Close</button>
    </div>
</body>
</html> 
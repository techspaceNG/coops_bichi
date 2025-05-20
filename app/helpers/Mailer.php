<?php
declare(strict_types=1);

/**
 * Mailer Helper
 * Handles sending emails for the application
 */
class Mailer
{
    /**
     * SMTP configuration
     */
    private static array $config = [
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'notifications@example.com',
        'password' => 'your-smtp-password',
        'from_email' => 'notifications@fcetbichi-coops.org',
        'from_name' => 'FCET Bichi COOPS'
    ];
    
    /**
     * Send email
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @param array $attachments Optional file attachments [['path' => '/path/to/file', 'name' => 'filename.pdf']]
     * @return bool True if email sent successfully, false otherwise
     */
    public static function send(string $to, string $subject, string $body, array $attachments = []): bool
    {
        try {
            // Load PHPMailer
            // Note: This assumes PHPMailer is installed - would need to be added via Composer
            if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
                error_log('PHPMailer not installed');
                return false;
            }
            
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = self::$config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = self::$config['username'];
            $mail->Password = self::$config['password'];
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = self::$config['port'];
            
            // Sender
            $mail->setFrom(self::$config['from_email'], self::$config['from_name']);
            
            // Recipients
            $mail->addAddress($to);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            // Attachments
            foreach ($attachments as $attachment) {
                if (isset($attachment['path']) && file_exists($attachment['path'])) {
                    $mail->addAttachment(
                        $attachment['path'],
                        $attachment['name'] ?? basename($attachment['path'])
                    );
                }
            }
            
            // Send email
            $mail->send();
            
            return true;
        } catch (\Exception $e) {
            error_log('Error sending email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send loan approval notification
     *
     * @param array $member Member data
     * @param array $loan Loan data
     * @return bool True if email sent successfully, false otherwise
     */
    public static function sendLoanApprovalNotification(array $member, array $loan): bool
    {
        $subject = 'Loan Application Approved';
        
        $body = self::getEmailTemplate('loan_approval', [
            'name' => $member['first_name'] . ' ' . $member['last_name'],
            'coop_no' => $member['coop_no'],
            'loan_amount' => number_format($loan['loan_amount'], 2),
            'monthly_payment' => number_format($loan['ip_figure'], 2),
            'approval_date' => date('F j, Y', strtotime($loan['approval_date'])),
            'first_payment_date' => date('F j, Y', strtotime('+1 month', strtotime($loan['approval_date']))),
        ]);
        
        return self::send($member['email'], $subject, $body);
    }
    
    /**
     * Send loan rejection notification
     *
     * @param array $member Member data
     * @param array $loan Loan data
     * @return bool True if email sent successfully, false otherwise
     */
    public static function sendLoanRejectionNotification(array $member, array $loan): bool
    {
        $subject = 'Loan Application Status Update';
        
        $body = self::getEmailTemplate('loan_rejection', [
            'name' => $member['first_name'] . ' ' . $member['last_name'],
            'coop_no' => $member['coop_no'],
            'loan_amount' => number_format($loan['loan_amount'], 2),
            'rejection_date' => date('F j, Y', strtotime($loan['rejection_date'])),
            'rejection_reason' => $loan['rejection_reason'] ?? 'Insufficient funds or eligibility criteria not met.',
        ]);
        
        return self::send($member['email'], $subject, $body);
    }
    
    /**
     * Send household purchase approval notification
     *
     * @param array $member Member data
     * @param array $purchase Household purchase data
     * @return bool True if email sent successfully, false otherwise
     */
    public static function sendHouseholdApprovalNotification(array $member, array $purchase): bool
    {
        $subject = 'Household Purchase Application Approved';
        
        $body = self::getEmailTemplate('household_approval', [
            'name' => $member['first_name'] . ' ' . $member['last_name'],
            'coop_no' => $member['coop_no'],
            'purchase_amount' => number_format($purchase['purchase_amount'], 2),
            'monthly_payment' => number_format($purchase['ip_figure'], 2),
            'approval_date' => date('F j, Y', strtotime($purchase['approval_date'])),
            'first_payment_date' => date('F j, Y', strtotime('+1 month', strtotime($purchase['approval_date']))),
        ]);
        
        return self::send($member['email'], $subject, $body);
    }
    
    /**
     * Send monthly statement
     *
     * @param array $member Member data
     * @param array $statement Statement data
     * @return bool True if email sent successfully, false otherwise
     */
    public static function sendMonthlyStatement(array $member, array $statement): bool
    {
        $subject = 'Your Monthly Cooperative Statement - ' . date('F Y');
        
        $body = self::getEmailTemplate('monthly_statement', [
            'name' => $member['first_name'] . ' ' . $member['last_name'],
            'coop_no' => $member['coop_no'],
            'statement_date' => date('F j, Y'),
            'savings_balance' => number_format($statement['savings_balance'], 2),
            'loan_balance' => number_format($statement['loan_balance'], 2),
            'household_balance' => number_format($statement['household_balance'], 2),
            'total_deduction' => number_format($statement['total_deduction'], 2),
        ]);
        
        // Generate PDF statement
        $statementPdf = self::generateStatementPdf($member, $statement);
        
        $attachments = [];
        if ($statementPdf) {
            $attachments[] = [
                'path' => $statementPdf,
                'name' => 'Statement_' . date('Y_m') . '.pdf'
            ];
        }
        
        return self::send($member['email'], $subject, $body, $attachments);
    }
    
    /**
     * Generate PDF statement
     *
     * @param array $member Member data
     * @param array $statement Statement data
     * @return string|null Path to generated PDF or null on failure
     */
    private static function generateStatementPdf(array $member, array $statement): ?string
    {
        // This is a placeholder - in a real implementation, you would
        // use a library like TCPDF or FPDF to generate the PDF
        
        $pdfPath = BASE_DIR . '/uploads/statements/';
        
        // Create directory if it doesn't exist
        if (!is_dir($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }
        
        $filename = $pdfPath . $member['coop_no'] . '_' . date('Y_m') . '.pdf';
        
        // In a real implementation, you would generate the PDF here
        // For now, just create a placeholder file
        file_put_contents($filename, 'Placeholder for PDF statement');
        
        return $filename;
    }
    
    /**
     * Get HTML email template and replace placeholders
     *
     * @param string $template Template name (without .php extension)
     * @param array $data Data to replace in template
     * @return string Rendered HTML template
     */
    private static function getEmailTemplate(string $template, array $data): string
    {
        $templatePath = BASE_DIR . '/app/views/emails/' . $template . '.php';
        
        // Check if template exists
        if (!file_exists($templatePath)) {
            // Use a default template if the specific one doesn't exist
            $templatePath = BASE_DIR . '/app/views/emails/default.php';
            
            // If even the default template doesn't exist, return a simple message
            if (!file_exists($templatePath)) {
                return self::getDefaultTemplate($data);
            }
        }
        
        // Start output buffering
        ob_start();
        
        // Extract data to make variables available in the template
        extract($data);
        
        // Include the template
        include $templatePath;
        
        // Get the output buffer and clean it
        $html = ob_get_clean();
        
        return $html;
    }
    
    /**
     * Get default email template when no template file exists
     *
     * @param array $data Template data
     * @return string HTML email content
     */
    private static function getDefaultTemplate(array $data): string
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>FCET Bichi Cooperative Society</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; padding: 20px 0; border-bottom: 1px solid #eee; }
                .content { padding: 20px 0; }
                .footer { text-align: center; padding: 20px 0; border-top: 1px solid #eee; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>FCET Bichi Staff Multipurpose Cooperative Society</h2>
                </div>
                <div class="content">';
        
        // Add data as paragraphs
        foreach ($data as $key => $value) {
            $html .= '<p><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . $value . '</p>';
        }
        
        $html .= '</div>
                <div class="footer">
                    <p>This is an automated message from FCET Bichi Staff Multipurpose Cooperative Society.</p>
                    <p>Please do not reply to this email. For inquiries, contact the cooperative office.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
} 
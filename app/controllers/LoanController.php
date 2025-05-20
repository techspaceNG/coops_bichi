<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Utility;
use App\Helpers\Validator;
use App\Models\Member;
use App\Models\Loan;
use App\Config\Database;
use App\Helpers\Session;

/**
 * Loan Controller
 * Handles member loan functionality
 */
final class LoanController extends Controller
{
    /**
     * Display member loan details
     *
     * @return void
     */
    public function index(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get loan details
        $loan = Loan::getByMemberId($memberId);
        
        // Get recent loan transactions
        $transactions = Database::fetchAll(
            "SELECT * FROM transaction_history 
            WHERE member_id = ? AND transaction_type = 'loan'
            ORDER BY created_at DESC 
            LIMIT 10",
            [$memberId]
        );
        
        $this->render('member/loans/index', [
            'title' => 'My Loans - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'loan' => $loan,
            'transactions' => $transactions
        ]);
    }
    
    /**
     * Display and process loan application
     *
     * @return void
     */
    public function apply(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Check if member already has an active loan
        try {
            $existingLoan = Loan::getByMemberId($memberId);
            $hasActiveLoan = $existingLoan && isset($existingLoan['balance']) && $existingLoan['balance'] > 0;
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            $existingLoan = null;
            $hasActiveLoan = false;
        }
        
        // Check if member has a pending loan application
        try {
            // First check if the loan_applications table exists
            $tableExists = Database::fetchOne(
                "SELECT COUNT(*) as count FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_name = 'loan_applications'"
            );
            
            $pendingApplication = null;
            if ($tableExists && $tableExists['count'] > 0) {
                $pendingApplication = Database::fetchOne(
                    "SELECT * FROM loan_applications 
                    WHERE member_id = ? AND status = 'pending'",
                    [$memberId]
                );
            }
        } catch (\PDOException $e) {
            $pendingApplication = null;
        }
        
        $errors = [];
        $application_success = false;
        
        // Handle loan application form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            Validator::resetErrors();
            Validator::required($input, ['loan_amount', 'ip_figure', 'loan_duration', 'bank_name', 'account_number', 'account_name', 'account_type']);
            Validator::numeric($input, 'loan_amount');
            Validator::numeric($input, 'ip_figure');
            Validator::numeric($input, 'loan_duration');
            Validator::min($input, 'loan_amount', 1000);
            Validator::min($input, 'ip_figure', 100);
            
            if ($input['ip_figure'] > $input['loan_amount']) {
                Validator::addError('ip_figure', 'Monthly repayment cannot be greater than loan amount');
            }
            
            // Validate account number (must be 10 digits)
            if (isset($input['account_number']) && !preg_match('/^\d{10}$/', $input['account_number'])) {
                Validator::addError('account_number', 'Account number must be 10 digits');
            }
            
            if (Validator::hasErrors()) {
                $errors = Validator::getErrors();
            } else {
                try {
                    // Create loan application
                    if ($tableExists && $tableExists['count'] > 0) {
                        // If loan_applications table exists, use it
                        $applicationId = Loan::createApplication(
                            $memberId,
                            $member->getFullName(),
                            $member->coop_no,
                            (float)$input['loan_amount'],
                            (float)$input['ip_figure'],
                            (int)$input['loan_duration'],
                            $input['purpose'] ?? null,
                            $input['additional_info'] ?? null,
                            $input['bank_name'] ?? null,
                            $input['account_number'] ?? null,
                            $input['account_name'] ?? null,
                            $input['account_type'] ?? null
                        );
                    } else {
                        // Directly create a loan entry instead of going through application process
                        $repayment = Utility::calculateLoanRepayment(
                            (float)$input['loan_amount'],
                            (float)$input['ip_figure']
                        );
                        
                        $loanCreated = Loan::createOrUpdate(
                            $memberId,
                            (float)$input['loan_amount'],
                            isset($input['loan_duration']) ? (int)$input['loan_duration'] : 12, // Use form value or default to 12 months
                            (float)$input['ip_figure'],
                            $repayment['total_repayment'] ?? ((float)$input['loan_amount'] * 1.05), // Default to 5% interest
                            (float)$input['loan_amount']
                        );
                        
                        $applicationId = $loanCreated ? 1 : null; // Set a dummy ID to indicate success
                    }
                    
                    if ($applicationId) {
                        // Save bank details to member profile if they're not already set
                        $member = Member::findById($memberId);
                        if ($member) {
                            $shouldUpdate = false;
                            
                            if (empty($member->bank_name) && !empty($input['bank_name'])) {
                                $member->bank_name = $input['bank_name'];
                                $shouldUpdate = true;
                            }
                            
                            if (empty($member->account_number) && !empty($input['account_number'])) {
                                $member->account_number = $input['account_number'];
                                $shouldUpdate = true;
                            }
                            
                            if (empty($member->account_name) && !empty($input['account_name'])) {
                                $member->account_name = $input['account_name'];
                                $shouldUpdate = true;
                            }
                            
                            if ($shouldUpdate) {
                                $member->save();
                            }
                        }
                        
                        Session::setFlash('success', 'Loan application submitted successfully. You will be notified when it is reviewed.');
                        header('Location: /Coops_Bichi/public/member/loans/applications');
                        exit;
                    } else {
                        $errors['application'] = 'Failed to submit loan application. Please try again.';
                    }
                } catch (\PDOException $e) {
                    error_log('Loan application error: ' . $e->getMessage());
                    $errors['application'] = 'Database error occurred. Please try again later.';
                }
            }
        }
        
        $this->render('member/loans/apply', [
            'title' => 'Apply for Loan - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'hasActiveLoan' => $hasActiveLoan,
            'pendingApplication' => $pendingApplication,
            'errors' => $errors,
            'application_success' => $application_success
        ]);
    }
    
    /**
     * Display member loan applications
     *
     * @return void
     */
    public function applications(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get page number from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Get loan applications
        $applications = Loan::getApplicationsByMemberId($memberId, $page);
        
        $this->render('member/loans/applications', [
            'title' => 'My Loan Applications - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'applications' => $applications
        ]);
    }
    
    /**
     * Display loan calculator
     *
     * @return void
     */
    public function calculator(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        $loanAmount = isset($_GET['amount']) ? (float)$_GET['amount'] : 100000;
        $ipFigure = isset($_GET['monthly']) ? (float)$_GET['monthly'] : 10000;
        
        // Calculate loan repayment
        $repaymentDetails = Utility::calculateLoanRepayment($loanAmount, $ipFigure);
        
        $this->render('member/loans/calculator', [
            'title' => 'Loan Repayment Calculator - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'loanAmount' => $loanAmount,
            'ipFigure' => $ipFigure,
            'repaymentDetails' => $repaymentDetails
        ]);
    }
} 
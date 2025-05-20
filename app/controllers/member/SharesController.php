<?php
declare(strict_types=1);

namespace App\Controllers\Member;

use App\Core\Controller;
use App\Models\Share;
use App\Helpers\Auth;

final class SharesController extends Controller
{
    public function index(): void
    {
        $member_id = Auth::getMemberId();
        $shares = Share::findByMemberId($member_id);
        
        $this->render('member/shares/index', [
            'shares' => $shares,
            'total_value' => Share::getMemberTotalShares($member_id),
            'pageTitle' => 'My Shares'
        ]);
    }
    
    public function view(string $id): void
    {
        $share_id = (int)$id;
        $member_id = Auth::getMemberId();
        $share = Share::findById($share_id);
        
        if (!$share || $share->getMemberId() !== $member_id) {
            $this->setFlash('error', 'Share not found');
            $this->redirect('/member/shares');
            return;
        }
        
        $transactions = $share->getTransactions();
        
        $this->render('member/shares/view', [
            'share' => $share,
            'transactions' => $transactions,
            'pageTitle' => 'Share Details'
        ]);
    }
    
    public function purchase(): void
    {
        // Always render the contact admin page regardless of request method
        $this->render('member/shares/purchase', [
            'pageTitle' => 'Purchase Shares'
        ]);
        return;
    }
    
    public function sell(string $id): void
    {
        $share_id = (int)$id;
        $member_id = Auth::getMemberId();
        $share = Share::findById($share_id);
        
        if (!$share || $share->getMemberId() !== $member_id) {
            $this->setFlash('error', 'Share not found');
            $this->redirect('/member/shares');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('member/shares/sell', [
                'share' => $share,
                'pageTitle' => 'Sell Shares'
            ]);
            return;
        }
        
        try {
            $quantity = (int)($_POST['quantity'] ?? 0);
            $unit_price = (float)($_POST['unit_price'] ?? 0);
            
            if ($quantity <= 0 || $unit_price <= 0) {
                throw new \Exception('Invalid quantity or unit price');
            }
            
            if ($quantity > $share->getQuantity()) {
                throw new \Exception('Cannot sell more shares than owned');
            }
            
            // Record the sale transaction
            $share->recordTransaction('sale', $quantity, $unit_price, 'Share sale');
            
            // Update share quantity and status
            $remaining_quantity = $share->getQuantity() - $quantity;
            $share->setQuantity($remaining_quantity);
            $share->setTotalValue($remaining_quantity * $share->getUnitPrice());
            
            if ($remaining_quantity === 0) {
                $share->setStatus('sold');
            }
            
            if ($share->save()) {
                $this->setFlash('success', 'Shares sold successfully');
                $this->redirect('/member/shares');
            } else {
                throw new \Exception('Failed to sell shares');
            }
        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/member/shares/sell/' . $id);
        }
    }
} 
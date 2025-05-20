<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Faq;
use App\Models\Contact;
use App\Models\Announcement;

/**
 * Home Controller
 * Handles public pages of the website
 */
final class HomeController extends Controller
{
    /**
     * Display homepage
     */
    public function index(): void
    {
        // Get recent announcements (limit to 3)
        $announcements = Announcement::getPublished(3);
        
        $this->render('home/index', [
            'announcements' => $announcements,
            'pageTitle' => 'Home'
        ]);
    }
    
    /**
     * Display about page
     */
    public function about(): void
    {
        $this->render('home/about', [
            'pageTitle' => 'About Us'
        ]);
    }
    
    /**
     * Display contact page
     */
    public function contact(): void
    {
        $this->render('home/contact', [
            'pageTitle' => 'Contact Us'
        ]);
    }
    
    /**
     * Process contact form submission
     */
    public function submitContact(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact = new Contact();
            $contact->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $contact->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $contact->subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $contact->message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
            $contact->status = 'unread';
            $contact->created_at = date('Y-m-d H:i:s');

            if (empty($contact->name) || empty($contact->email) || empty($contact->subject) || empty($contact->message)) {
                $this->setFlash('error', 'All fields are required');
                header('Location: /contact');
                exit;
            }

            if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                $this->setFlash('error', 'Invalid email format');
                header('Location: /contact');
                exit;
            }

            if ($contact->save()) {
                $this->setFlash('success', 'Your message has been sent successfully');
            } else {
                $this->setFlash('error', 'Failed to send your message. Please try again.');
            }
            
            header('Location: /contact');
            exit;
        }
        
        header('Location: /contact');
    }
    
    /**
     * Display FAQ page
     */
    public function faq(): void
    {
        // Seed sample data if the table is empty
        Faq::seedSampleData();
        
        $faqs = Faq::getAll();
        $this->render('home/faq', [
            'faqs' => $faqs,
            'pageTitle' => 'Frequently Asked Questions'
        ]);
    }
    
    /**
     * Display all announcements page
     */
    public function announcements(): void
    {
        $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING) ?: '';
        $announcements = Announcement::getPublished(0, $category);
        $this->render('home/announcements', [
            'announcements' => $announcements,
            'pageTitle' => 'Announcements & News',
            'category' => $category
        ]);
    }
    
    /**
     * Display single announcement
     * 
     * @param int $id Announcement ID
     */
    public function announcement(int $id): void
    {
        $id = (int) $id;
        $announcement = Announcement::findById($id);
        
        if (!$announcement || ($announcement->status !== 'published')) {
            $this->setFlash('error', 'Announcement not found');
            $this->redirect('/announcements');
            return;
        }
        
        // Get 5 recent announcements excluding current one
        $recentAnnouncements = [];
        $allRecent = Announcement::getPublished(6);
        
        foreach ($allRecent as $recent) {
            if ((int)$recent->id !== $id && count($recentAnnouncements) < 5) {
                $recentAnnouncements[] = $recent;
            }
        }
        
        $this->render('home/announcement-detail', [
            'announcement' => $announcement,
            'recentAnnouncements' => $recentAnnouncements,
            'pageTitle' => $announcement->title
        ]);
    }
} 
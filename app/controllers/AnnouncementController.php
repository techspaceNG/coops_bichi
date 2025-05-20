<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Validator;
use App\Models\Announcement;

/**
 * Announcement Controller
 * Handles announcement management for the admin panel
 */
final class AnnouncementController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Only admin can manage announcements
        $this->requireAdmin();
    }
    
    /**
     * Display list of all announcements
     */
    public function index(): void
    {
        // Get status filter from query string
        $status = $_GET['status'] ?? '';
        
        // Get all announcements (or filtered by status)
        $announcements = Announcement::getAll($status);
        
        $this->render('admin/announcements/index', [
            'announcements' => $announcements,
            'status' => $status,
            'pageTitle' => 'Announcements'
        ]);
    }
    
    /**
     * Display form to create a new announcement
     */
    public function create(): void
    {
        $this->render('admin/announcements/create', [
            'pageTitle' => 'Create Announcement'
        ]);
    }
    
    /**
     * Store a new announcement
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/announcements');
            return;
        }
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        $validator = new Validator($input);
        $validator->required('title', 'Title is required');
        $validator->required('content', 'Content is required');
        $validator->required('status', 'Status is required');
        
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getFirstError());
            $this->redirect('/admin/announcements/create');
            return;
        }
        
        // Create new announcement
        $announcement = new Announcement();
        $announcement->title = $input['title'];
        $announcement->content = $input['content'];
        $announcement->status = $input['status'];
        $announcement->category = $input['category'] ?? 'general';
        $announcement->created_by = Auth::getAdminId(); // Store admin ID
        
        // Handle optional dates
        if (!empty($input['publish_date'])) {
            $announcement->publish_date = $input['publish_date'];
        }
        
        if (!empty($input['expire_date'])) {
            $announcement->expire_date = $input['expire_date'];
        }
        
        // Save announcement
        $result = $announcement->save();
        
        if ($result) {
            // Log action
            Auth::logAction('admin', Auth::getAdminId(), 'Created announcement', [
                'announcement_id' => $announcement->id
            ]);
            
            $this->setFlash('success', 'Announcement created successfully');
        } else {
            $this->setFlash('error', 'Failed to create announcement');
        }
        
        $this->redirect('/admin/announcements');
    }
    
    /**
     * Display form to edit an announcement
     * 
     * @param int $id Announcement ID
     */
    public function edit(int $id): void
    {
        // Get announcement
        $announcement = Announcement::findById($id);
        
        if (!$announcement) {
            $this->setFlash('error', 'Announcement not found');
            $this->redirect('/admin/announcements');
            return;
        }
        
        $this->render('admin/announcements/edit', [
            'announcement' => $announcement,
            'pageTitle' => 'Edit Announcement'
        ]);
    }
    
    /**
     * Update an announcement
     * 
     * @param int $id Announcement ID
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/announcements');
            return;
        }
        
        // Get announcement
        $announcement = Announcement::findById($id);
        
        if (!$announcement) {
            $this->setFlash('error', 'Announcement not found');
            $this->redirect('/admin/announcements');
            return;
        }
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        $validator = new Validator($input);
        $validator->required('title', 'Title is required');
        $validator->required('content', 'Content is required');
        $validator->required('status', 'Status is required');
        
        if (!$validator->isValid()) {
            $this->setFlash('error', $validator->getFirstError());
            $this->redirect('/admin/announcements/edit/' . $id);
            return;
        }
        
        // Update announcement
        $announcement->title = $input['title'];
        $announcement->content = $input['content'];
        $announcement->status = $input['status'];
        $announcement->category = $input['category'] ?? 'general';
        
        // Handle optional dates
        $announcement->publish_date = !empty($input['publish_date']) ? $input['publish_date'] : null;
        $announcement->expire_date = !empty($input['expire_date']) ? $input['expire_date'] : null;
        
        // Save announcement
        $result = $announcement->save();
        
        if ($result) {
            // Log action
            Auth::logAction('admin', Auth::getAdminId(), 'Updated announcement', [
                'announcement_id' => $announcement->id
            ]);
            
            $this->setFlash('success', 'Announcement updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update announcement');
        }
        
        $this->redirect('/admin/announcements');
    }
    
    /**
     * Delete an announcement
     * 
     * @param int $id Announcement ID
     */
    public function delete(int $id): void
    {
        // Get announcement
        $announcement = Announcement::findById($id);
        
        if (!$announcement) {
            $this->setFlash('error', 'Announcement not found');
            $this->redirect('/admin/announcements');
            return;
        }
        
        // Delete announcement
        $result = Announcement::delete($id);
        
        if ($result) {
            // Log action
            Auth::logAction('admin', Auth::getAdminId(), 'Deleted announcement', [
                'announcement_id' => $id
            ]);
            
            $this->setFlash('success', 'Announcement deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete announcement');
        }
        
        $this->redirect('/admin/announcements');
    }
} 
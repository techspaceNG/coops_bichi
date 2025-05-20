<?php 
// Ensure this page isn't accessed directly
if (!defined('BASE_DIR')) exit('No direct script access allowed');
?>

<div class="container-fluid p-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Notification</h1>
        
        <a href="<?= url('/superadmin/notifications') ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Notifications
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Notification Details</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <strong>Error:</strong> Please fix the following issues:
                    <ul class="mb-0 mt-1">
                        <?php foreach ($_SESSION['errors'] as $field => $message): ?>
                            <li><?= htmlspecialchars($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['warning'])): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($_SESSION['warning']) ?>
                </div>
                <?php unset($_SESSION['warning']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php
            // Retrieve form data from session if available
            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']); // Clear the session data after retrieving
            ?>
            
            <form action="<?= url('/superadmin/save-notification') ?>" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Notification Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($formData['title'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Notification Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="4" required><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="link" class="form-label">Link (Optional)</label>
                    <input type="text" class="form-control" id="link" name="link" value="<?= htmlspecialchars($formData['link'] ?? '') ?>" placeholder="e.g., /member/dashboard or /admin/dashboard">
                    <div class="form-text text-muted">
                        Enter a URL that users will be directed to when they click the notification.
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="target_type" id="target_all_admins" value="all_admins" required>
                        <label class="form-check-label" for="target_all_admins">
                            All Administrators
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="target_type" id="target_specific_admin" value="specific_admin">
                        <label class="form-check-label" for="target_specific_admin">
                            Specific Administrator
                        </label>
                        
                        <div class="ms-4 mt-2 specific-admin-section d-none">
                            <select class="form-select" name="admin_id" id="admin_id">
                                <option value="">Select an Administrator</option>
                                <?php if (isset($admins) && !empty($admins)): ?>
                                    <?php foreach ($admins as $admin): ?>
                                        <option value="<?= $admin['id'] ?>">
                                            <?= htmlspecialchars($admin['name']) ?> (<?= htmlspecialchars($admin['username']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="target_type" id="target_all_members" value="all_members">
                        <label class="form-check-label" for="target_all_members">
                            All Members
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="target_type" id="target_specific_member" value="specific_member">
                        <label class="form-check-label" for="target_specific_member">
                            Specific Member
                        </label>
                        
                        <div class="ms-4 mt-2 specific-member-section d-none">
                            <div class="input-group">
                                <input type="text" class="form-control member-search" id="member_search" placeholder="Search member by name or COOPS number">
                                <button class="btn btn-outline-secondary" type="button" id="search_member_btn">Search</button>
                            </div>
                            <div id="member_search_results" class="mt-2"></div>
                            <input type="hidden" name="member_id" id="member_id">
                            <div id="selected_member" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-4">
                    <a href="<?= url('/superadmin/notifications') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Target type radio buttons
        const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
        const specificAdminSection = document.querySelector('.specific-admin-section');
        const specificMemberSection = document.querySelector('.specific-member-section');
        const adminIdSelect = document.getElementById('admin_id');
        
        // Handle target type change
        targetTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Hide all conditional sections
                specificAdminSection.classList.add('d-none');
                specificMemberSection.classList.add('d-none');
                
                // Show relevant section based on selection
                if (this.value === 'specific_admin') {
                    specificAdminSection.classList.remove('d-none');
                    adminIdSelect.required = true;
                } else {
                    adminIdSelect.required = false;
                }
                
                if (this.value === 'specific_member') {
                    specificMemberSection.classList.remove('d-none');
                    document.getElementById('member_id').required = true;
                } else {
                    document.getElementById('member_id').required = false;
                }
            });
        });
        
        // Handle member search
        const searchMemberBtn = document.getElementById('search_member_btn');
        const memberSearchInput = document.getElementById('member_search');
        const memberSearchResults = document.getElementById('member_search_results');
        const selectedMemberDiv = document.getElementById('selected_member');
        const memberIdInput = document.getElementById('member_id');
        
        searchMemberBtn.addEventListener('click', function() {
            const searchTerm = memberSearchInput.value.trim();
            if (searchTerm.length < 3) {
                memberSearchResults.innerHTML = '<div class="alert alert-warning">Please enter at least 3 characters</div>';
                return;
            }
            
            memberSearchResults.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
            
            // Fetch members matching the search term
            fetch(`<?= url('/superadmin/search-members-api') ?>?term=${encodeURIComponent(searchTerm)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.error || `HTTP error! Status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (data.members && data.members.length > 0) {
                    let html = '<div class="list-group">';
                    data.members.forEach(member => {
                        html += `<a href="#" class="list-group-item list-group-item-action select-member" data-id="${member.id}" data-name="${member.name}" data-coops="${member.coop_no}">
                            ${member.name} (${member.coop_no})
                        </a>`;
                    });
                    html += '</div>';
                    memberSearchResults.innerHTML = html;
                    
                    // Add click handlers for member selection
                    document.querySelectorAll('.select-member').forEach(item => {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');
                            const coops = this.getAttribute('data-coops');
                            
                            memberIdInput.value = id;
                            selectedMemberDiv.innerHTML = `<div class="alert alert-success">
                                Selected: <strong>${name}</strong> (${coops})
                                <button type="button" class="btn-close float-end" id="clear_member"></button>
                            </div>`;
                            
                            // Clear search results
                            memberSearchResults.innerHTML = '';
                            memberSearchInput.value = '';
                            
                            // Add clear handler
                            document.getElementById('clear_member').addEventListener('click', function() {
                                memberIdInput.value = '';
                                selectedMemberDiv.innerHTML = '';
                            });
                        });
                    });
                } else {
                    memberSearchResults.innerHTML = '<div class="alert alert-warning">No members found matching your search.</div>';
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                memberSearchResults.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
            });
        });
        
        // Allow pressing Enter to search
        memberSearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchMemberBtn.click();
            }
        });
    });
</script> 
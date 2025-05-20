<?php /* This file is included by the renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add Savings Deduction</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/savings'); ?>">Savings Management</a></li>
        <li class="breadcrumb-item active">Add Deduction</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Add Manual Savings Deduction
                </div>
                <div class="card-body">
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">Deduction added successfully!</div>
                    <?php endif; ?>
                    
                    <form action="<?php echo url('/superadmin/savings/add'); ?>" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="member_search" class="form-label">Search Member</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="member_search" placeholder="Search by name or coop number...">
                                <button class="btn btn-outline-secondary" type="button" id="search_button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div id="search_results" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                            <input type="hidden" id="member_id" name="member_id" value="<?php echo htmlspecialchars($input['member_id'] ?? ''); ?>" required>
                            <div id="selected_member" class="mt-2 p-2 border rounded <?php echo isset($input['member_id']) ? '' : 'd-none'; ?>">
                                <strong>Selected Member:</strong> <span id="selected_member_name"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger float-end" id="clear_selection">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['member_id'])): ?>
                                <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['member_id']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="form-control <?php echo isset($errors['amount']) ? 'is-invalid' : ''; ?>" 
                                   id="amount" name="amount" min="0.01" step="0.01" required 
                                   value="<?php echo htmlspecialchars($input['amount'] ?? ''); ?>">
                            <?php if (isset($errors['amount'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['amount']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" 
                                   id="description" name="description" required 
                                   value="<?php echo htmlspecialchars($input['description'] ?? 'Monthly Deduction'); ?>">
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div>
                            <?php endif; ?>
                            <div class="form-text">E.g., Monthly Deduction, Special Contribution, etc.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deduction_date" class="form-label">Deduction Date</label>
                            <input type="date" class="form-control" id="deduction_date" name="deduction_date" 
                                   value="<?php echo htmlspecialchars($input['deduction_date'] ?? date('Y-m-d')); ?>">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo url('/superadmin/savings'); ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Deduction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const memberSearch = document.getElementById('member_search');
    const searchButton = document.getElementById('search_button');
    const searchResults = document.getElementById('search_results');
    const memberIdInput = document.getElementById('member_id');
    const selectedMemberDiv = document.getElementById('selected_member');
    const selectedMemberName = document.getElementById('selected_member_name');
    const clearSelectionBtn = document.getElementById('clear_selection');
    
    // If there's a pre-selected member, show it
    if (memberIdInput.value) {
        // Fetch member details to display name
        fetch(`<?php echo url('/superadmin/api/member-details'); ?>?id=${memberIdInput.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.member) {
                    selectedMemberName.textContent = `${data.member.name} (${data.member.coop_no})`;
                    selectedMemberDiv.classList.remove('d-none');
                }
            })
            .catch(error => console.error('Error fetching member details:', error));
    }
    
    // Search for members
    function searchMembers() {
        const searchTerm = memberSearch.value.trim();
        if (searchTerm.length < 2) {
            alert('Please enter at least 2 characters to search');
            return;
        }
        
        // Show loading indicator
        searchResults.innerHTML = '<div class="list-group-item"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        searchResults.style.display = 'block';
        
        // Log for debugging
        console.log('Searching for members with term:', searchTerm);
        
        fetch(`<?php echo url('/superadmin/api/search-members'); ?>?q=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            cache: 'no-cache' // Prevent caching
        })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().catch(error => {
                    console.error('Error parsing JSON:', error);
                    throw new Error('Invalid response format');
                });
            })
            .then(data => {
                console.log('Search results:', data);
                searchResults.innerHTML = '';
                
                if (data.error) {
                    searchResults.innerHTML = `<div class="list-group-item text-danger">${data.error}</div>`;
                    return;
                }
                
                if (data.members && data.members.length > 0) {
                    data.members.forEach(member => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = `${member.name} (${member.coop_no})`;
                        item.dataset.id = member.id;
                        item.dataset.name = member.name;
                        item.dataset.coopNo = member.coop_no;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            selectMember(member.id, member.name, member.coop_no);
                        });
                        
                        searchResults.appendChild(item);
                    });
                } else {
                    searchResults.innerHTML = '<div class="list-group-item">No members found</div>';
                }
                
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Error searching members:', error);
                searchResults.innerHTML = '<div class="list-group-item text-danger">Error searching members. Please try again.</div>';
                searchResults.style.display = 'block';
            });
    }
    
    // Select a member from search results
    function selectMember(id, name, coopNo) {
        memberIdInput.value = id;
        selectedMemberName.textContent = `${name} (${coopNo})`;
        selectedMemberDiv.classList.remove('d-none');
        searchResults.style.display = 'none';
        memberSearch.value = '';
    }
    
    // Clear selected member
    clearSelectionBtn.addEventListener('click', function() {
        memberIdInput.value = '';
        selectedMemberDiv.classList.add('d-none');
    });
    
    // Search on button click
    searchButton.addEventListener('click', searchMembers);
    
    // Search on Enter key
    memberSearch.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchMembers();
        }
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== memberSearch && e.target !== searchButton) {
            searchResults.style.display = 'none';
        }
    });
    
    // Form validation
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script> 
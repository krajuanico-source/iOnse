@extends('layouts/contentNavbarLayout')

@section('title', 'DSWD Rewards Program Overview and Committee')

@section('content')

<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

   <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
    <i class="fas fa-award me-2"></i> DSWD Rewards Program Overview and Committee
        </h1>
        <button id="addMemorandumBtn" class="btn btn-success d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Add Program Objective
        </button>
    </div>


    <!-- Navigation -->
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="rewardsDropdown" role="button" data-bs-toggle="dropdown">
                            Rewards Program
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="#overview">Program Overview</a></li>
                            <li><a class="dropdown-item" href="#committee">Committee</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-light me-2">Logout</a>
                </div>
            </div>
        </div>

    <div class="container-fluid p-0">

        <!-- Main Content -->
        <div class="container my-5">
            <div class="row">
                <div class="col-md-3">
                    <!-- Sidebar Menu -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            Rewards Program
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#overview" class="list-group-item list-group-item-action active">Program Overview</a>
                            <a href="#committee" class="list-group-item list-group-item-action">Committee Members</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <!-- Program Overview Section -->
                    <div class="card mb-5" id="overview">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h5 class="mb-0">Program Overview</h5>
                            <button class="btn btn-sm btn-light edit-btn" id="editOverviewBtn">Edit</button>
                        </div>
                        <div class="card-body editable" id="programOverviewContent">
                            <h4 class="mb-3">Objectives</h4>
                            <ul class="mb-4">
                                <li id="objective1">To recognize outstanding individuals and organizations in the field of social welfare and development.</li>
                                <li id="objective2">To promote best practices and innovations in social service delivery.</li>
                                <li id="objective3">To inspire others to contribute to nation-building through social development initiatives.</li>
                            </ul>
                            
                            <h4 class="mb-3">Award Categories</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="category1">Outstanding Social Worker</td>
                                            <td id="desc1">Recognizes exemplary performance and dedication of social workers.</td>
                                        </tr>
                                        <tr>
                                            <td id="category2">Community Service Award</td>
                                            <td id="desc2">Honors outstanding community-based initiatives and programs.</td>
                                        </tr>
                                        <tr>
                                            <td id="category3">Innovation in Social Welfare</td>
                                            <td id="desc3">Awards novel approaches to addressing social welfare challenges.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Committee Section -->
                    <div class="card" id="committee">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h5 class="mb-0">Evaluation Committee</h5>
                            <button class="btn btn-sm btn-light edit-btn" id="editCommitteeBtn">Edit</button>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 editable" id="committeeInfo">
                                <h4 class="mb-3">Roles and Responsibilities</h4>
                                <ul>
                                    <li>Review and evaluate all nominations based on established criteria</li>
                                    <li>Conduct necessary validations and verifications of nomination documents</li>
                                    <li>Select deserving awardees through a fair and objective process</li>
                                    <li>Recommend awardees to the DSWD Secretary for approval</li>
                                    <li>Present awards during the annual recognition ceremony</li>
                                </ul>
                            </div>
                            
                            <h4 class="mb-4">Committee Members</h4>
                            <div class="row">
                                <!-- Committee Member 1 -->
                                <div class="col-md-4 mb-4">
                                    <div class="card committee-card h-100">
                                        <div class="position-relative">
                                            <img src="https://placehold.co/300x300" class="card-img-top" alt="Portrait of Committee Co-Chair in business attire, smiling warmly">
                                            <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2 edit-member-btn" data-member="2">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="member1-name">Dr. Maria Santos</h5>
                                            <p class="card-text text-muted" id="member1-designation">Chairperson<br>DSWD Undersecretary</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Committee Member 2 -->
                                <div class="col-md-4 mb-4">
                                    <div class="card committee-card h-100">
                                        <div class="position-relative">
                                            <img src="https://placehold.co/300x300" class="card-img-top" alt="Portrait of Committee Co-Chair in business attire, smiling warmly">
                                            <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2 edit-member-btn" data-member="2">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="member2-name">Atty. Juan Dela Cruz</h5>
                                            <p class="card-text text-muted" id="member2-designation">Co-Chair<br>DSWD Assistant Secretary</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Committee Member 3 -->
                                <div class="col-md-4 mb-4">
                                    <div class="card committee-card h-100">
                                        <div class="position-relative">
                                            <img src="https://placehold.co/300x300" class="card-img-top" alt="Portrait of Committee Member in professional attire with glasses">
                                            <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2 edit-member-btn" data-member="3">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="member3-name">Prof. Anna Reyes</h5>
                                            <p class="card-text text-muted" id="member3-designation">Member<br>Academic Representative</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Committee Member 4 -->
                                <div class="col-md-4 mb-4">
                                    <div class="card committee-card h-100">
                                        <div class="position-relative">
                                            <img src="https://placehold.co/300x300" class="card-img-top" alt="Portrait of Committee Co-Chair in business attire, smiling warmly">
                                            <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2 edit-member-btn" data-member="2">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="member2-name">Atty. Francis Jay L. Villas</h5>
                                            <p class="card-text text-muted" id="member2-designation">Co-Chair<br>DSWD Assistant Secretary Mine</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Committee Member 3 -->
                                <div class="col-md-4 mb-4">
                                    <div class="card committee-card h-100">
                                        <div class="position-relative">
                                            <img src="https://placehold.co/300x300" class="card-img-top" alt="Portrait of Committee Member in professional attire with glasses">
                                            <button class="btn btn-sm btn-primary position-absolute top-0 end-0 m-2 edit-member-btn" data-member="3">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="member3-name">Prof. Kimmy Rose A. Juanico</h5>
                                            <p class="card-text text-muted" id="member3-designation">Member<br>Academic Representative</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <strong>Note:</strong> Adding new committee members requires administrative approval. Please contact the system administrator for this enhancement.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal for Program Overview -->
    <div class="modal fade" id="editOverviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Program Overview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="overviewForm">
                        <div class="mb-3">
                            <label class="form-label">Objectives</label>
                            <textarea class="form-control" id="editObjectives" rows="5">To recognize outstanding individuals and organizations in the field of social welfare and development.
To promote best practices and innovations in social service delivery.
To inspire others to contribute to nation-building through social development initiatives.</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Award Categories</label>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" value="Outstanding Social Worker"></td>
                                            <td><input type="text" class="form-control" value="Recognizes exemplary performance and dedication of social workers."></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="form-control" value="Community Service Award"></td>
                                            <td><input type="text" class="form-control" value="Honors outstanding community-based initiatives and programs."></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="form-control" value="Innovation in Social Welfare"></td>
                                            <td><input type="text" class="form-control" value="Awards novel approaches to addressing social welfare challenges."></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveOverviewBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal for Committee Member -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Committee Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="memberForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editMemberName">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" id="editMemberDesignation">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" id="editMemberPhoto" accept="image/*">
                            <small class="text-muted">Only JPG or PNG, max 2MB</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveMemberBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

 
    
    <script>
        // Show edit modals based on user role (simplified for demo)
        const userIsAdmin = true; // This would be set from Laravel auth in actual implementation
        
        if (userIsAdmin) {
            document.querySelectorAll('.edit-btn').forEach(btn => btn.style.display = 'inline-block');
        }
        
        // Program Overview Edit Functionality
        document.getElementById('editOverviewBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('editOverviewModal'));
            modal.show();
        });
        
        document.getElementById('saveOverviewBtn').addEventListener('click', function() {
            // In a real app, this would save to the database via AJAX
            const objectives = document.getElementById('editObjectives').value.split('\n');
            objectives.forEach((obj, i) => {
                document.getElementById(`objective${i+1}`).textContent = obj;
            });
            
            // Update categories - simplified for demo
            const rows = document.querySelectorAll('#overviewForm tbody tr');
            rows.forEach((row, i) => {
                const inputs = row.querySelectorAll('input');
                document.getElementById(`category${i+1}`).textContent = inputs[0].value;
                document.getElementById(`desc${i+1}`).textContent = inputs[1].value;
            });
            
            bootstrap.Modal.getInstance(document.getElementById('editOverviewModal')).hide();
            alert('Program overview updated successfully!');
        });
        
        // Committee Member Edit Functionality
        let currentMemberId = null;
        document.querySelectorAll('.edit-member-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentMemberId = this.getAttribute('data-member');
                const name = document.getElementById(`member${currentMemberId}-name`).textContent;
                const designation = document.getElementById(`member${currentMemberId}-designation`).textContent.replace('<br>', '\n');
                
                document.getElementById('editMemberName').value = name;
                document.getElementById('editMemberDesignation').value = designation;
                
                const modal = new bootstrap.Modal(document.getElementById('editMemberModal'));
                modal.show();
            });
        });
        
        document.getElementById('saveMemberBtn').addEventListener('click', function() {
            const name = document.getElementById('editMemberName').value;
            const designation = document.getElementById('editMemberDesignation').value;
            
            document.getElementById(`member${currentMemberId}-name`).textContent = name;
            document.getElementById(`member${currentMemberId}-designation`).innerHTML = designation.replace(/\n/g, '<br>');
            
            // Handle image upload would be here in a real app
            
            bootstrap.Modal.getInstance(document.getElementById('editMemberModal')).hide();
            alert('Committee member updated successfully!');
        });
    </script>



    



 
    @endsection
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Account Information</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                    <h4 class="mt-3"><?= esc($user['username']) ?></h4>
                    <p class="text-muted"><?= $user['role'] === 'admin' ? 'Administrator' : 'Student' ?></p>
                    
                    <?php if ($user['role'] === 'student'): ?>
                        <div class="mt-2">
                            <?php if (isset($user['verified']) && $user['verified']): ?>
                                <span class="badge bg-success">Verified Account</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pending Verification</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope me-2"></i> Email</span>
                        <span><?= esc($user['email']) ?></span>
                    </li>
                    <?php if (isset($user['student_id']) && !empty($user['student_id'])): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-id-card me-2"></i> Student ID</span>
                        <span><?= esc($user['student_id']) ?></span>
                    </li>
                    <?php endif; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-calendar me-2"></i> Joined</span>
                        <span><?= date('M d, Y', strtotime($user['created_at'])) ?></span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4>Security</h4>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-2"></i> Change Password
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Activity Summary</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-primary"><?= $totalPosts ?></h2>
                            <p class="text-muted mb-0">Total Posts</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-success"><?= $approvedPosts ?></h2>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-warning"><?= $pendingPosts ?></h2>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-danger"><?= $rejectedPosts ?></h2>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid">
                    <a href="<?= base_url('post/my-posts') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i> View All My Posts
                    </a>
                </div>
            </div>
        </div>
        
        <?php if ($user['role'] === 'student'): ?>
        <div class="card">
            <div class="card-header">
                <h4>Verification Information</h4>
            </div>
            <div class="card-body">
                <?php if (isset($user['verified']) && $user['verified']): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Your account has been verified by an administrator. You have full access to all platform features.
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Your account is pending verification by an administrator. Some features may be limited until your account is verified.
                    </div>
                    <p>Verification typically takes 1-2 business days. If your account has been pending for longer, please contact an administrator.</p>
                <?php endif; ?>
                
                <h5 class="mt-4">Why verification is required:</h5>
                <ul>
                    <li>To ensure all users are legitimate students or staff members</li>
                    <li>To maintain the integrity and security of the feedback platform</li>
                    <li>To prevent spam and inappropriate content</li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('profile/update') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= esc($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
                    </div>
                    <?php if (isset($user['student_id']) && !empty($user['student_id'])): ?>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" value="<?= esc($user['student_id']) ?>" disabled>
                        <div class="form-text">Student ID cannot be changed. Contact an administrator if you need to update this information.</div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('profile/change-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text">Password must be at least 6 characters long.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

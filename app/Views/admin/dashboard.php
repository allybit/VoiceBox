<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin dashboard. Here you can manage the Anonymous Feedback Platform.</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="display-4"><?= $totalUsers ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= base_url('verify-users') ?>">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Pending Posts</h5>
                <h2 class="display-4"><?= $pendingPosts ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= base_url('admin/pending-posts') ?>">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Approved Posts</h5>
                <h2 class="display-4"><?= $approvedPosts ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="<?= base_url('admin/approved-posts') ?>">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Rejected Posts</h5>
                <h2 class="display-4"><?= $rejectedPosts ?></h2>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="#">View Details</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- User Verification Statistics -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-user-check me-2"></i> User Verification Statistics</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <h5>Verification Progress</h5>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $verificationPercentage ?>%;" aria-valuenow="<?= $verificationPercentage ?>" aria-valuemin="0" aria-valuemax="100"><?= $verificationPercentage ?>%</div>
                            </div>
                            <p class="mt-2 text-muted"><?= $verifiedStudents ?> of <?= $totalStudents ?> students verified</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Verified Students</h6>
                                                <h2 class="mb-0"><?= $verifiedStudents ?></h2>
                                            </div>
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-warning text-white mb-3">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Pending Verification</h6>
                                                <h2 class="mb-0"><?= $pendingStudents ?></h2>
                                            </div>
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <a href="<?= base_url('verify-users') ?>" class="btn btn-primary">
                                <i class="fas fa-user-check me-2"></i> Manage User Verification
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Registrations -->
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-user-plus me-2"></i> Recent Registrations</h4>
            </div>
            <div class="card-body">
                <?php if (empty($recentUsers)): ?>
                    <div class="alert alert-info">No recent registrations.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td><?= esc($user['username']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <?php if (isset($user['verified']) && $user['verified']): ?>
                                                <span class="badge bg-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-grid mt-3">
                        <a href="<?= base_url('verify-users') ?>" class="btn btn-outline-primary btn-sm">View All Users</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-chart-pie me-2"></i> Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?= base_url('admin/pending-posts') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-clipboard-list me-2"></i> Review Pending Posts
                        <?php if ($pendingPosts > 0): ?>
                            <span class="badge bg-warning float-end"><?= $pendingPosts ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= base_url('verify-users') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-check me-2"></i> Verify Users
                        <?php if ($pendingStudents > 0): ?>
                            <span class="badge bg-warning float-end"><?= $pendingStudents ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= base_url('admin/manage-tags') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-tags me-2"></i> Manage Tags
                    </a>
                    <a href="<?= base_url('admin/create-announcement') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-bullhorn me-2"></i> Create Announcement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

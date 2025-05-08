<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Verify Users</h2>
            </div>
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <div class="alert alert-info">
                        No users pending verification.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Student ID</th>
                                    <th>Registered</th>
                                    <th>Status</th>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= esc($user['username']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= esc($user['student_id'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <?php if (isset($user['verified']) && $user['verified']): ?>
                                                <span class="badge bg-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (session()->get('role') === 'admin'): ?>
                                        <td>
                                            <?php if (!isset($user['verified']) || !$user['verified']): ?>
                                                <a href="<?= base_url('verify-user/' . $user['id']) ?>" class="btn btn-sm btn-success">Verify</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('unverify-user/' . $user['id']) ?>" class="btn btn-sm btn-warning">Unverify</a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('delete-user/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                            <a href="<?= base_url('verification-history/' . $user['id']) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-history"></i> History
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

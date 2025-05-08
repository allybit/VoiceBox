<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Verification History for <?= esc($user['username']) ?></h2>
                <a href="<?= base_url('verify-users') ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Users
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($history)): ?>
                    <div class="alert alert-info">
                        No verification history found for this user.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Admin</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $entry): ?>
                                    <tr>
                                        <td><?= date('M d, Y h:i A', strtotime($entry['created_at'])) ?></td>
                                        <td>
                                            <?php if ($entry['status'] === 'verified'): ?>
                                                <span class="badge bg-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Unverified</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($entry['admin_name']) ?></td>
                                        <td><?= esc($entry['notes']) ?></td>
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

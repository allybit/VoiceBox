<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Announcements</h2>
            <a href="<?= base_url('admin/create-announcement') ?>" class="btn btn-primary">Create New Announcement</a>
        </div>
        
        <?php if (empty($announcements)): ?>
            <div class="alert alert-info">
                No announcements have been created yet.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $announcement): ?>
                            <tr>
                                <td><?= esc($announcement['title']) ?></td>
                                <td>
                                    <?php if ($announcement['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($announcement['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit-announcement/' . $announcement['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="<?= base_url('admin/delete-announcement/' . $announcement['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
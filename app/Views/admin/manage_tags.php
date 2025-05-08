<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Manage Tags</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Create New Tag</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/create-tag') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Tag Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">Enter a descriptive name for the tag (e.g., "Facilities", "Curriculum").</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Create Tag</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Existing Tags</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($tags)): ?>
                            <div class="alert alert-info">
                                No tags have been created yet.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tags as $tag): ?>
                                            <tr>
                                                <td><?= esc($tag['name']) ?></td>
                                                <td><?= date('M d, Y', strtotime($tag['created_at'])) ?></td>
                                                <td>
                                                    <a href="<?= base_url('admin/delete-tag/' . $tag['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this tag? This will remove it from all posts.')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
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
        
        <div class="mt-4">
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
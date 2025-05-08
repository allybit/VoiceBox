<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Posts</h2>
            <a href="<?= base_url('post/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Create New Post
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> You haven't created any posts yet. Click the "Create New Post" button to get started.
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Tags</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= esc($post['title']) ?></td>
                                        <td>
                                            <?php foreach ($post['tags'] as $tag): ?>
                                                <span class="badge bg-primary"><?= esc($tag['name']) ?></span>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php if ($post['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif ($post['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="<?= base_url('post/edit/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="<?= base_url('post/delete/' . $post['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Pending Posts</h2>
            </div>
            <div class="card-body">
                <?php if (empty($posts)): ?>
                    <div class="alert alert-info">
                        No pending posts found.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Tags</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= esc($post['title']) ?></td>
                                        <td><?= esc(substr($post['content'], 0, 100)) ?>...</td>
                                        <td>
                                            <?php foreach ($post['tags'] as $tag): ?>
                                                <span class="badge bg-primary"><?= esc($tag['name']) ?></span>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-sm btn-primary">View</a>
                                            <a href="<?= base_url('admin/approve-post/' . $post['id']) ?>" class="btn btn-sm btn-success">Approve</a>
                                            <a href="<?= base_url('admin/reject-post/' . $post['id']) ?>" class="btn btn-sm btn-warning">Reject</a>
                                            <a href="<?= base_url('admin/delete-post/' . $post['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
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
<?= $this->endSection() ?>

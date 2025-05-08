<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <h2>My Feedback Posts</h2>
        
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                You haven't created any feedback posts yet.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Tags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= esc($post['title']) ?></td>
                                <td>
                                    <?php if ($post['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($post['status'] === 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                <td>
                                    <?php foreach ($post['tags'] as $tag): ?>
                                        <span class="badge bg-primary"><?= esc($tag['name']) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php if ($post['status'] === 'approved'): ?>
                                        <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    <?php elseif ($post['status'] === 'pending'): ?>
                                        <a href="<?= base_url('delete-post/' . $post['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="<?= base_url('create-post') ?>" class="btn btn-primary">Create New Post</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Pending Comments</h2>
            </div>
            <div class="card-body">
                <?php if (empty($comments)): ?>
                    <div class="alert alert-info">
                        No pending comments found.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Comment</th>
                                    <th>User</th>
                                    <th>Post</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments as $comment): ?>
                                    <tr>
                                        <td><?= esc(substr($comment['content'], 0,   ?>
                                    <tr>
                                        <td><?= esc(substr($comment['content'], 0, 100)) ?>...</td>
                                        <td><?= esc($comment['username']) ?></td>
                                        <td><a href="<?= base_url('post/' . $comment['post_id']) ?>"><?= esc($comment['post_title']) ?></a></td>
                                        <td><?= date('M d, Y', strtotime($comment['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('comment/approve/' . $comment['id']) ?>" class="btn btn-sm btn-success">Approve</a>
                                            <a href="<?= base_url('comment/reject/' . $comment['id']) ?>" class="btn btn-sm btn-warning">Reject</a>
                                            <a href="<?= base_url('comment/delete/' . $comment['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
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

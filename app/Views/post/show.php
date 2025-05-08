<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?= esc($post['title']) ?></h2>
            <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card post-card mb-4">
            <div class="card-header post-header d-flex justify-content-between">
                <div>
                    <?php foreach ($post['tags'] as $tag): ?>
                        <a href="<?= base_url('tag/' . $tag['id']) ?>" class="badge bg-primary tag"><?= esc($tag['name']) ?></a>
                    <?php endforeach; ?>
                </div>
                <small class="text-muted">Posted <?= date('M d, Y', strtotime($post['created_at'])) ?></small>
            </div>
            <div class="card-body post-content">
                <div class="row">
                    <div class="col-md-1 col-2">
                        <div class="vote-buttons">
                            <a href="#" class="vote-btn <?= (isset($post['user_vote']) && $post['user_vote'] == 1) ? 'text-primary' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="upvote" id="upvote-<?= $post['id'] ?>">
                                <i class="fas fa-arrow-up fa-2x"></i>
                            </a>
                            <div class="vote-count" id="vote-count-<?= $post['id'] ?>"><?= $post['vote_count'] ?></div>
                            <a href="#" class="vote-btn <?= (isset($post['user_vote']) && $post['user_vote'] == -1) ? 'text-danger' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="downvote" id="downvote-<?= $post['id'] ?>">
                                <i class="fas fa-arrow-down fa-2x"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-11 col-10">
                        <p><?= nl2br(esc($post['content'])) ?></p>
                    </div>
                </div>
            </div>
            <div class="card-footer post-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Status: </span>
                        <?php if ($post['status'] === 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php elseif ($post['status'] === 'pending'): ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (session()->get('isLoggedIn') && (session()->get('id') == $post['user_id'] || session()->get('role') === 'admin')): ?>
                        <div>
                            <?php if (session()->get('id') == $post['user_id']): ?>
                                <a href="<?= base_url('post/edit/' . $post['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            <?php endif; ?>
                            
                            <?php if (session()->get('role') === 'admin'): ?>
                                <?php if ($post['status'] === 'pending'): ?>
                                    <a href="<?= base_url('admin/approve-post/' . $post['id']) ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </a>
                                    <a href="<?= base_url('admin/reject-post/' . $post['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('admin/delete-post/' . $post['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h4><i class="fas fa-comments me-2"></i> Comments</h4>
            </div>
            <div class="card-body">
                <?php if (session()->get('isLoggedIn')): ?>
                    <form action="<?= base_url('comment/add') ?>" method="post" class="mb-4">
                        <?= csrf_field() ?>
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <div class="mb-3">
                            <label for="content" class="form-label">Add a Comment</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Please <a href="<?= base_url('login') ?>">login</a> to leave a comment.
                    </div>
                <?php endif; ?>
        
        <!-- Remove the Pending Comments (Admin Only) section -->
        
        <!-- Comments -->
        <?php if (empty($comments)): ?>
            <div class="alert alert-light">
                <i class="fas fa-comment-slash me-2"></i> No comments yet. Be the first to comment!
            </div>
        <?php else: ?>
            <div class="comments">
                <h5 class="mb-3">All Comments</h5>
                <?php foreach ($comments as $comment): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong><?= esc($comment['username']) ?></strong>
                                    <span class="text-muted ms-2"><?= date('M d, Y h:i A', strtotime($comment['created_at'])) ?></span>
                                </div>
                                <?php if (session()->get('isLoggedIn') && (session()->get('id') == $comment['user_id'] || session()->get('role') === 'admin')): ?>
                                    <div>
                                        <a href="<?= base_url('comment/delete/' . $comment['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0"><?= nl2br(esc($comment['content'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">About This Post</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Created</span>
                        <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                    </li>
                    <?php if (isset($post['updated_at']) && $post['updated_at'] !== $post['created_at']): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Last Updated</span>
                            <span><?= date('M d, Y', strtotime($post['updated_at'])) ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Status</span>
                        <?php if ($post['status'] === 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php elseif ($post['status'] === 'pending'): ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Votes</span>
                        <span><?= $post['vote_count'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Comments</span>
                        <span><?= count($comments) ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Related Tags</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <a href="<?= base_url('tag/' . $tag['id']) ?>" class="btn btn-sm btn-outline-primary m-1">
                            <?= esc($tag['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

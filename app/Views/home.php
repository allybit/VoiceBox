<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <?php if (isset($announcements) && !empty($announcements)): ?>
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-bullhorn me-2"></i> Announcements</h4>
            </div>
            <div class="card-body">
                <?php foreach ($announcements as $announcement): ?>
                <div class="announcement mb-3 pb-3 border-bottom">
                    <h5><?= esc($announcement['title']) ?></h5>
                    <p><?= nl2br(esc($announcement['content'])) ?></p>
                    <div class="text-muted small">
                        Posted on <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="col-md-8">
        <h2>Recent Feedback</h2>
        
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                No feedback posts available yet. Be the first to share your thoughts!
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card post-card mb-4">
                    <div class="card-header post-header d-flex justify-content-between">
                        <h5 class="mb-0"><?= esc($post['title']) ?></h5>
                        <small class="text-muted">Posted <?= date('M d, Y', strtotime($post['created_at'])) ?></small>
                    </div>
                    <div class="card-body post-content">
                        <div class="row">
                            <div class="col-md-1 col-2">
                                <div class="vote-buttons">
                                    <a href="#" class="vote-btn <?= (isset($post['user_vote']) && $post['user_vote'] == 1) ? 'text-primary' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="upvote" id="upvote-<?= $post['id'] ?>">
                                        <i class="fas fa-arrow-up fa-2x"></i>
                                    </a>
                                    <div class="vote-count" id="vote-count-<?= $post['id'] ?>"><?= isset($post['vote_count']) ? $post['vote_count'] : 0 ?></div>
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
                                <?php if (isset($post['tags'])): ?>
                                    <?php foreach ($post['tags'] as $tag): ?>
                                        <a href="<?= base_url('tag/' . $tag['id']) ?>" class="badge bg-primary tag"><?= esc($tag['name']) ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-comment me-1"></i> Comment
                                    <?php if (isset($post['comment_count']) && $post['comment_count'] > 0): ?>
                                        <span class="badge bg-secondary ms-1"><?= $post['comment_count'] ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">About This Platform</h5>
            </div>
            <div class="card-body">
                <p>Welcome to the Anonymous Feedback Platform! This platform allows students to provide anonymous feedback about courses, instructors, and campus facilities.</p>
                <p>All feedback is moderated to ensure a respectful and constructive environment.</p>
                
                <?php if (!session()->get('isLoggedIn')): ?>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('login') ?>" class="btn btn-primary">Login to Participate</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-outline-secondary">Register an Account</a>
                    </div>
                <?php else: ?>
                    <div class="d-grid">
                        <a href="<?= base_url('post/create') ?>" class="btn btn-primary">Share Your Feedback</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Popular Tags</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <?php if (isset($popularTags) && !empty($popularTags)): ?>
                        <?php foreach ($popularTags as $tag): ?>
                            <a href="<?= base_url('tag/' . $tag['id']) ?>" class="btn btn-sm btn-outline-primary m-1">
                                <?= esc($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No tags available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

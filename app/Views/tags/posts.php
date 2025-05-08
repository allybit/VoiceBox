<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Posts tagged with "<?= esc($tag['name']) ?>"</h2>
            <a href="<?= base_url('tags') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-tags me-2"></i> All Tags
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">
                No posts found with this tag.
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
                                    <a href="#" class="vote-btn <?= ($post['user_vote'] == 1) ? 'text-primary' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="upvote" id="upvote-<?= $post['id'] ?>">
                                        <i class="fas fa-arrow-up fa-2x"></i>
                                    </a>
                                    <div class="vote-count" id="vote-count-<?= $post['id'] ?>"><?= $post['vote_count'] ?></div>
                                    <a href="#" class="vote-btn <?= ($post['user_vote'] == -1) ? 'text-danger' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="downvote" id="downvote-<?= $post['id'] ?>">
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
                                <?php foreach ($post['tags'] as $tag): ?>
                                    <a href="<?= base_url('tag/' . $tag['id']) ?>" class="badge bg-primary tag"><?= esc($tag['name']) ?></a>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-comment me-1"></i> Comment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>
                    <?= esc($post['title']) ?>
                    <?php if (isset($post['user_role']) && $post['user_role'] === 'admin'): ?>
                        <span class="badge bg-danger">Admin Post</span>
                    <?php endif; ?>
                </h2>
                <div class="text-muted">
                    Posted by <?= isset($post['user_role']) && $post['user_role'] === 'admin' ? '<strong>Admin</strong>' : 'Student' ?> on <?= date('M d, Y', strtotime($post['created_at'])) ?>
                </div>
            </div>
            <div class="card-body">
                <p><?= nl2br(esc($post['content'])) ?></p>
                
                <div class="mb-3">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <a href="<?= base_url('tag/' . $tag['id']) ?>" class="badge bg-primary tag-badge"><?= esc($tag['name']) ?></a>
                    <?php endforeach; ?>
                </div>
                
                <div class="d-flex align-items-center">
                    <span class="me-3 vote-btn upvote-btn <?= (session()->get('isLoggedIn') && isset($post['user_vote']) && $post['user_vote'] === 'upvote') ? 'active' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="upvote">
                        <i class="fas fa-thumbs-up"></i> <?= $post['upvotes'] ?>
                    </span>
                    <span class="vote-btn downvote-btn <?= (session()->get('isLoggedIn') && isset($post['user_vote']) && $post['user_vote'] === 'downvote') ? 'active' : '' ?>" data-post-id="<?= $post['id'] ?>" data-vote-type="downvote">
                        <i class="fas fa-thumbs-down"></i> <?= $post['downvotes'] ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="<?= base_url('/') ?>" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Related Tags</h5>
            </div>
            <div class="card-body">
                <?php foreach ($post['tags'] as $tag): ?>
                    <a href="<?= base_url('tag/' . $tag['id']) ?>" class="badge bg-primary p-2 mb-2 me-2"><?= esc($tag['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
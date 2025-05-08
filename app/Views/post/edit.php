<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Post</h2>
            <a href="<?= base_url('post/' . $post['id']) ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Post
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('post/update/' . $post['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $post['title']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="6" required><?= old('content', $post['content']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select" id="tags" name="tags[]" multiple required>
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?= $tag['id'] ?>" <?= (in_array($tag['id'], $postTagIds)) ? 'selected' : '' ?>>
                                    <?= esc($tag['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple tags.</div>
                    </div>
                    
                    <?php if ($post['status'] === 'rejected'): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> This post was rejected. Editing it will resubmit it for approval.
                        </div>
                    <?php elseif ($post['status'] === 'pending'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This post is pending approval. Editing it will reset the approval process.
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

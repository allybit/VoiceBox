<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create Feedback Post</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('create-post') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="6" required><?= old('content') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <div class="row">
                            <?php foreach ($tags as $tag): ?>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" id="tag<?= $tag['id'] ?>">
                                        <label class="form-check-label" for="tag<?= $tag['id'] ?>">
                                            <?= esc($tag['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
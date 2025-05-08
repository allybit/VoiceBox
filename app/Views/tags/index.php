<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <h2>All Tags</h2>
        <p>Click on a tag to see all posts with that tag.</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($tags)): ?>
                    <div class="alert alert-info">No tags found.</div>
                <?php else: ?>
                    <div class="d-flex flex-wrap">
                        <?php foreach ($tags as $tag): ?>
                            <a href="<?= base_url('tag/' . $tag['id']) ?>" class="btn btn-outline-primary m-1">
                                <?= esc($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

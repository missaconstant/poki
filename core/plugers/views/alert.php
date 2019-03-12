<div class="alert alert-dismissible fade show alert-<?= $type ?> ">
    <?= $message ?>

    <?php if ($dismiss): ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    <?php endif; ?>
</div>
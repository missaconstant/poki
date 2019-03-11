<?php foreach ($pluglist as $k => $plugin): ?>
    
<div class="col-md-6 col-lg-6 col-xl-4">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="">
                        <img src="<?= THEME ?>assets/images/small/img-2.jpg" class="round">
                    </div>
                </div>
                <div class="col-9" style="border-left:1px solid #ddd;">
                    <div class="m-l-10">
                        <h6 class="mt-0 round-inner"><?= $plugin['label_name'] ?> <i class="mdi mdi-check-circle-outline text-success"></i></h6>
                        <p class="mb-0 text-muted"><?= $plugin['description'] ?></p>
                        <div class="buttons m-t-10">
                            <button class="btn btn-sm btn-outline-danger pull-right" style="border-radius:20px;"><i class="mdi mdi-delete"></i></button>
                            <button class="btn btn-sm btn-outline-info" style="border-radius:20px;">Deactive this</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>
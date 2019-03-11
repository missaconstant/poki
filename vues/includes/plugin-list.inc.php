<?php foreach ($pluglist as $k => $plugin): ?>
    
<div class="col-md-6 col-lg-6 col-xl-4">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <div class="text-center">
                        <img src="<?= THEME ?>assets/images/small/img-2.jpg" width="55" height="55" class="" style="border-radius:100%;">
                    </div>
                </div>
                <div class="col-10" style="border-left:1px solid #ddd;">
                    <div class="m-l-10">
                        <h6 class="mt-0 round-inner"><?= $plugin['label_name'] ?> <i class="mdi mdi-<?= $plugin['active'] ? 'check':'close' ?>-circle text-<?= $plugin['active'] ? 'success':'danger' ?>"></i></h6>
                        <p class="mb-0 text-muted"><?= $plugin['description'] ?></p>
                        <div class="buttons m-t-10" id="pkpg-<?= $k ?>">
                            <button class="btn btn-sm btn-outline-danger pull-right delete-plugin" style="border-radius:20px;"><i class="mdi mdi-delete"></i></button>
                            <button class="btn btn-sm btn-outline-info toggle-plugin" style="border-radius:20px;"><?= $plugin['active']==1 ? 'Deactive' : 'Active' ?> this</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>
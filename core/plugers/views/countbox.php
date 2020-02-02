<<?= $element .' '. $attrs ?>>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="col-4 align-self-center">
                    <div class="round">
                        <i class="mdi mdi-<?= $icon ?>" style="color:<?= $icolor ?>;"></i>
                    </div>
                </div>
                <div class="col-8 align-self-center">
                    <div class="m-l-10">
                        <h5 class="mt-0 round-inner"><?= $count ?></h5>
                        <p class="mb-0 text-muted"><?= $label ?></p>
                    </div>
                </div>
                <!-- <div class="col-3 align-self-end align-self-center">
                    <h6 class="m-0 float-right text-center text-danger"> <i class="mdi mdi-arrow-down"></i> <span>5.26%</span></h6>
                </div> -->
            </div>
        </div>
    </div>
</<?= $element ?>>
<div class="modal fade" id="apimodal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog  pulse  animated" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle-1"><?= $category_name ?> api</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Routes::find('api-set') ?>" id="apimodalform" name="apimodalform" method="post" onsubmit="return false;">
                    <div class="form-group">
                        <label>Category Api key</label>
                        <div class="input-group mt-2">
                            <input class="form-control" id="apikeyvalue" placeholder="Api key" disabled type="text" value="<?= $api->apikey ?>" name="fieldname">
                            <span class="input-group-append">
                                <a href="#" class="btn btn-primary" title="Copy Api key" onclick="copyApiKey(this)"><i class="mdi mdi-clipboard"></i></a>
                            </span>
                            <span class="input-group-append">
                                <a href="#" class="btn btn-outline-primary" title="Delete Api key (set to noset)" onclick="changeApiKey(this, true)"><i class="mdi mdi-delete"></i></a>
                            </span>
                            <span class="input-group-append">
                                <a href="#" class="btn btn-outline-primary" title="Refresh Api key" onclick="changeApiKey(this)"><i class="mdi mdi-refresh"></i></a>
                            </span>
                        </div>
                        <p class="text-muted font-12 mt-1">Let this value to "noset" if no need api key to accès this category.</p>
                    </div>

                    <div class="form-group">
                        <label>Access level <span class="text-muted font-12">(Without api key)</span></label> <br>
                        <select class="api-level-choose form-control" style="display:block; width:100%;" name="allowed[]" multiple="multiple">
                            <?php foreach ($apitypes as $k => $type): ?>
                            <option value="<?= trim($type) ?>" <?= in_array($type, explode(',', $api->allowed)) ? 'selected':'' ?>>/<?= $type ?></option>
                            <?php endforeach ?>
                        </select>
                        <p class="text-muted font-12 mt-1">To access not defined actions, use <b>category api key</b>.</p>
                    </div>

                    <input type="hidden" name="category" value="<?= $category_name ?>" id="categoryname">
                    <?= Posts::getCSRF() ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveApi('<?= $category_name ?>', this)">Save field</button>
            </div>
        </div>
    </div>
</div>
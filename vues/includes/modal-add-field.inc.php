<?php namespace Poki; ?>

<div class="modal fade" id="addfieldmodal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog  pulse  animated" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle-1">Add new field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Routes::find('categories') ?>" id="newfieldmodalform" name="newfieldmodalform" method="post" onsubmit="return false;">
                    <div class="row mainline fieldline">
                        <div class="col-12 form-group">
                            <label>Filed Name</label>
                            <input class="form-control field-name" placeholder="Type something" type="text" name="fieldname">
                        </div>
                        <div class="col-12 form-group">
                            <label>Filed Label</label>
                            <input class="form-control field-label" placeholder="Type something" type="text" name="fieldlabel">
                        </div>
                        <div class="col-12 form-group">
                            <label>Filed Type</label>
                            <select class="form-control field-type" name="fieldtype">
                                <option value="0" selected>Choose field type</option>
                                <?php foreach (Helpers::$types as $type => $label): ?>
                                <option value="<?= $type ?>"><?= $label ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="category" value="<?= $category_name ?>" id="categoryname">
                    <input type="hidden" name="editing" value="0" id="editingfield">
                    <?= Posts::getCSRF() ?>
                </form>
            </div>
            <div class="modal-footer">
            <a href="#" onclick="addModalFieldLine()" class="btn btn-info more-field-btn position-absolute" style="left:15px;"><span class="mdi mdi-plus"></span></a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveNewField()">Save field</button>
            </div>
        </div>
    </div>
</div>
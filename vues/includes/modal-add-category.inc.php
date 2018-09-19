<div class="modal fade" id="addcategorymodal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog  pulse  animated" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle-1">Add new category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Routes::find('categories-create-act') ?>" id="newcategorymodalform" name="newcategorymodalform" method="post" onsubmit="return false;">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input class="form-control" placeholder="Type something" type="text" name="name">
                    </div>
                    <input type="hidden" name="oldname" value="<?= isset($category_name) ? $category_name:'' ?>" id="oldcategoryname">
                    <input type="hidden" name="editing" value="0" id="editingcategory">
                    <?= Posts::getCSRF() ?>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="deleteCategory('<?= isset($category_name) ? $category_name:'' ?>')" class="btn btn-danger position-absolute deletecategorybtn" style="left:15px; display:none;"><span class="mdi mdi-delete"></span></a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="saveCategoryField('<?= Routes::find('category-show') ?>')">Save category</button>
            </div>
        </div>
    </div>
</div>
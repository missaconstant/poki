<div class="card m-b-30">
    <div class="card-body">

        <a href="#" class="btn btn-outline-danger btn-animation float-right" style="margin-left:10px;" onclick="openCategoryEditor('<?= $category_name ?>')">
            <span class="mdi mdi-pencil"></span>
        </a>
        <a href="#" class="btn btn-outline-info btn-animation float-right" data-animation="pulse" data-toggle="modal" data-target="#addfieldmodal">
            <span class="mdi mdi-plus"></span> Add a new field
        </a>

        <h4 class="mt-0 header-title">Category fields</h4>
        <p class="text-muted m-b-30 font-14">Create, delete or update categories fields</p>

        <div class="table-responsive">
            <table class="table category-fields-table" id="my-table" style="display:<?= $category_fields ? 'auto':'none' ?>;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Field name</th>
                        <th>Field type</th>
                        <th>Linked to</th>
                        <th>Created at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($category_fields ? $category_fields : []) as $k => $field): ?>
                    <tr id="field_<?= $k+1 ?>">
                        <td><?= $k+1 ?></td>
                        <td class="field_name"><?= $field['name'] ?></td>
                        <td class="field_type"><?= Helpers::getFieldPseudoType($field['type'], true) ?></td>
                        <td>None</td>
                        <td>Unknown</td>
                        <td style="white-space: nowrap; width: 15%;">
                            <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" onclick="openFieldEditor('<?= $category_name ?>', this)" class="btn btn-sm btn-info" style="float: none; margin: 5px;"><span class="ti-pencil"></span></button>
                                    <button type="button" class="btn btn-sm btn-warning" style="float: none; margin: 5px;"><span class="mdi mdi-link"></span></button>
                                    <button type="button" onclick="removeField('<?= $field['name'] ?>', '<?= $category_name ?>', this)" class="btn btn-sm btn-danger" style="float: none; margin: 5px;"><span class="ti-trash"></span></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<input type="hidden" id="categoryname" value="<?= $category_name ?>">
<div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
    <div class="card bg-white m-b-30">
        <div class="card-body new-user">
            <?php if ($admin->role != 'viewer'): ?>
                <a href="<?= Routes::find('category-form') .'/'. $category_name ?>" class="btn btn-outline-info btn-sm float-right"><i class="mdi mdi-plus"></i> Add new content</a>
            <?php endif ?>
            <h5 class="header-title mb-2 mt-0">Contents list</h5>
            <p class="text-muted font-14 mb-4">List of the content from this category.</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-top-0 text-center" style="width:50px;">Id</th>
                            <?php if ($admin->role != 'viewer'): ?>
                                <th class="border-top-0" style="width:120px;">Actions</th>
                            <?php endif ?>
                            <?php foreach ($category_fields as $k => $field): ?>
                                <th class="border-top-0"><?= $field['name'] ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contents as $k => $content):?>
                        <tr>
                            <td class="text-center"><?= $content['id'] ?></td>
                            <?php if ($admin->role != 'viewer'): ?>
                                <td id="content_<?= $content['id'] ?>">
                                    <div class="btn-group btn-group-sm" style="float:none;">
                                        <a href="#" onclick="" class="btn btn-sm btn-warning"><i class="mdi mdi-eye"></i></a>
                                        <a href="<?= Routes::find('category-form') .'/'. $category_name .'/'. $content['id'] ?>" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        <a href="#" onclick="deleteContent(this, '<?= $category_name ?>')" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></a>
                                    </div>
                                </td>
                            <?php endif ?>
                            <?php
                                foreach ($content as $field => $value):
                                    $value = Helpers::checkLinkedLabel($category_name, $field, $value);
                                    $value = preg_match("#&lt;(.*)&gt;#", $value) ? 'html content' : (preg_match("#([a-zA-Z0-9_]+[.]{1}[jpg|gif|png|bmp]{2})+#i", $value) ? 'Picture(s)':$value);
                                    if (!in_array($field, ['id', 'added_at', 'active'])):
                            ?>
                            <td style="max-width: 130px;">
                                <?= strlen($value)>25 ? substr($value, 0, 30) . '...' : ($value=='html content' ? '<span class="badge badge-primary badge-pill">html content</span>':$value) ?>
                            </td>
                            <?php
                                    endif;
                                endforeach
                            ?>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
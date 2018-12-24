<input type="hidden" id="categoryname" value="<?= $category_name ?>">
<div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
    <div class="card bg-white m-b-30">
        <div class="card-body new-user">
            <?php if ($admin->role != 'viewer'): ?>
                <!--- drop down button -->
                <button class="btn btn-success btn-sm float-right dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-file-outline"></i> CSV file &nbsp;</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <label class="dropdown-item" for="csvloader" style="cursor:pointer;">Load CSV file</label>
                        <a class="dropdown-item" href="#" onclick="getCSV('<?= $category_name ?>')">Download CSV file</a>
                    </div>
                <!-- -->
                <span class="float-right">&nbsp;&nbsp;</span>
                <a href="<?= Routes::find('category-form') .'/'. $category_name ?>" class="btn btn-outline-info btn-sm float-right"><i class="mdi mdi-plus"></i> Add new content</a>
                <input type="file" id="csvloader" style="display:none" onchange="addContentFromCsv(this)">
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
                <!-- --->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php
                            $i = 1;
                            $pages = (int) ($nbrcontents / $maxcontentperpage);
                            $pages += ($nbrcontents % $maxcontentperpage) > 0 ? 1 : 0;
                            if ($pages > 1):
                        ?>
                        <li class="page-item <?= $actualcontentspage==1 ? 'disabled':'' ?>">
                            <a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. ($actualcontentspage-1) ?>" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php endif ?>
                        <!-- paginate -->
                        <?php
                            $nbrpages = $pages;
                            if ($nbrpages > 1):
                                while ($nbrpages > 0):
                        ?>
                            <li class="page-item <?= $actualcontentspage==$i ? 'disabled':'' ?>"><a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. $i ?>"><?= $i ?></a></li>
                        <?php
                                    $i++;
                                    $nbrpages--;
                                endwhile;
                            endif;
                        ?>
                        <!-- /paginate -->
                        <?php if ($pages > 1): ?>
                        <li class="page-item <?= $actualcontentspage==$pages ? 'disabled':'' ?>">
                            <a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. ($actualcontentspage+1) ?>" aria-label="Next">
                                <span aria-hidden="true">»</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                        <?php endif ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
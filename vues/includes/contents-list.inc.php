<?php namespace Poki; ?>

<?php $categoryOfficialFields = []; ?>

<input type="hidden" id="categoryname" value="<?= $category_name ?>">
<div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
    <div class="card bg-white m-b-30">
        <div class="card-body new-user">
            <?php if ($admin->role != 'viewer'): ?>
                <!--- drop down button -->
                <div class="dropdown">
                    <button style="margin-bottom:10px;" class="btn btn-success btn-sm float-right dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-file-outline"></i> CSV file &nbsp;
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <input type="file" id="csvloader" style="display:none" onchange="addContentFromCsv(this)">
                        <!--  -->
                        <label class="dropdown-item" for="csvloader" style="cursor:pointer;">Load CSV file</label>
                        <a class="dropdown-item" href="#" onclick="getCSV('<?= $category_name ?>')">Download CSV file</a>
                    </div>
                </div>
                <!-- -->
                <span class="float-right">&nbsp;&nbsp;</span>
                <!--  -->
                <a href="<?= Routes::find('category-form') .'/'. $category_name ?>" class="btn btn-outline-info btn-sm float-right" style="margin-bottom:10px;">
                    <i class="mdi mdi-plus"></i> New content
                </a>
                <!-- -->
                <span class="float-right">&nbsp;&nbsp;</span>
                <!--  -->
                <div class="dropdown">
                    <button href="#" class="btn btn-warning btn-sm float-right dropdown-toggle d-none" id="dropdownMenuBtn2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-bottom:10px;">
                        <i class="mdi mdi-checkbox-marked"></i> Selected
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuBtn2">
                        <a class="dropdown-item" href="#" onclick="toggleContent(this, '<?= $category_name ?>', 'enable')">
                            <i class="mdi mdi-eye"></i> &nbsp; Enable all
                        </a>
                        <a class="dropdown-item" href="#" onclick="toggleContent(this, '<?= $category_name ?>', 'disable')">
                            <i class="mdi mdi-eye-off"></i> &nbsp; Disable all
                        </a>
                        <a class="dropdown-item" href="#" onclick="deleteContent(this, '<?= $category_name ?>', true)">
                            <i class="mdi mdi-delete"></i> &nbsp; Delete all
                        </a>
                    </div>
                </div>
                <!--  -->
            <?php endif ?>
            <h5 class="header-title mb-2 mt-0">Contents list</h5>
            <p class="text-muted font-14 mb-4">List of the content from this category.</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="select-more border-top-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input content-check-all" id="customCheck" data-parsley-multiple="groups" data-parsley-mincheck="2">
                                    <label class="custom-control-label" for="customCheck"></label>
                                </div>
                            </th>
                            <!--  -->
                            <th class="border-top-0 text-center" style="width:50px;">Id</th>
                            <?php if ($admin->role != 'viewer'): ?>
                                <th class="border-top-0" style="width:120px;">Actions</th>
                            <?php endif ?>
                            <!--  -->
                            <?php foreach ($category_fields as $k => $field): ?>
                                <th class="border-top-0"><?= $field['name'] ?></th>
                                <?php $categoryOfficialFields[]/*declared at top of this file*/ = $field['name']; ?>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contents as $k => $content): ?>
                        <tr>
                            <td width="5">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input content-check-one" id="customCheck_<?= $content['id'] ?>" data-parsley-multiple="groups" data-parsley-mincheck="2">
                                    <label class="custom-control-label" for="customCheck_<?= $content['id'] ?>"></label>
                                </div>
                            </td>
                            <!--  -->
                            <td class="text-center"><?= $content['id'] ?></td>
                            <?php if ($admin->role != 'viewer'): ?>
                                <td id="content_<?= $content['id'] ?>" data-state="<?= $content['active'] ?>">
                                    <div class="btn-group btn-group-sm" style="float:none;">
                                        <a href="#!" onclick="toggleContent(this, '<?= $category_name ?>')" class="btn btn-sm btn-warning toggle-btn"><i class="mdi mdi-eye<?= $content['active'] ? '':'-off' ?>"></i></a>
                                        <a href="<?= Routes::find('category-form') .'/'. $category_name .'/'. $content['id'] ?>" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                                        <a href="#!" onclick="deleteContent(this, '<?= $category_name ?>')" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></a>
                                    </div>
                                </td>
                            <?php endif ?>
                            <?php
                                foreach ($content as $field => $value):
                                    $value = Helpers::checkLinkedLabel($category_name, $field, $value);

                                    $value = preg_match("#&lt;(.*)&gt;#", $value) ? 'html content' : (
                                                                                        preg_match("#([a-zA-Z0-9_]+[.]{1}[jpg|gif|png|bmp]{2})+#i", $value) ? 'Picture(s)' : $value
                                                                                    );

                                    if (in_array($field, $categoryOfficialFields) && !in_array($field, ['id', 'added_at', 'active'])):
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
                            <a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. ($actualcontentspage-1) . ($issearch ? "/$issearch" : "") ?>" aria-label="Previous">
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
                            <li class="page-item <?= $actualcontentspage==$i ? 'disabled':'' ?>"><a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. $i . ($issearch ? "/$issearch" : "") ?>"><?= $i ?></a></li>
                        <?php
                                    $i++;
                                    $nbrpages--;
                                endwhile;
                            endif;
                        ?>
                        <!-- /paginate -->
                        <?php if ($pages > 1): ?>
                        <li class="page-item <?= $actualcontentspage==$pages ? 'disabled':'' ?>">
                            <a class="page-link" href="<?= Routes::find('category-list') .'/'. $category_name .'/'. ($actualcontentspage+1) . ($issearch ? "/$issearch" : "") ?>" aria-label="Next">
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
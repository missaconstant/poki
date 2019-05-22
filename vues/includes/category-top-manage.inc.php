<?php namespace Poki; ?>

<div class="row category-top-manage" style="display:<?= $category_fields && count($category_fields) ? 'auto':'none' ?>">
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <a href="<?= Routes::find('category-form') .'/'. $category_name ?>">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-3 align-self-center">
                            <div class="round">
                                <i class="mdi mdi-folder-plus"></i>
                            </div>
                        </div>
                        <div class="col-9  text-left align-self-left">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">Add content</h5>
                                <p class="mb-0 text-muted">Go to the form</p>                                                                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <a href="<?= Routes::find('category-list') .'/'. $category_name ?>">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-library-books"></i>
                        </div>
                    </div>
                    <div class="col-9 text-left align-self-left">
                        <div class="m-l-10 ">
                            <h5 class="mt-0 round-inner">List contents</h5>
                            <p class="mb-0 text-muted">Contents list</p>
                        </div>
                    </div>                                                    
                </div>
            </div>
        </div>
        </a>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <!-- <a href="<?= Routes::find('category-api') .'/'. $category_name ?>"> -->
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-basket"></i>
                        </div>
                    </div>
                    <div class="col-9 text-left align-self-left">
                        <div class="m-l-10 ">
                            <div class="actions float-right">
                                <a href="#" onclick="toggleApi('<?= $category_name ?>', this)" class="m-0 text-center text-<?= $api->active==1 ? 'success':'danger' ?>" style="font-size:25px;"><i class="mdi mdi-check-circle"></i></a>
                                <a href="#" onclick="openApiManager()" class="m-0 text-center text-info" style="font-size:25px;"><i class="mdi mdi-dots-vertical"></i></a>
                                <!-- <a class="m-0 text-center text-success font-20"><i class="mdi mdi-check-circle"></i></a> -->
                            </div>
                            <h5 class="mt-0 round-inner">Api</h5>
                            <p class="mb-0 text-muted">Manage api access</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </a> -->
    </div>
    <!-- Column -->
</div>
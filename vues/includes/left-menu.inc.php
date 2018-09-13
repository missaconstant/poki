            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="#" class="logo"><i class="mdi mdi-rocket" id="left-logo-icon"></i> Adminizer</a>
                        <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
                    </div>
                </div>

                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">Main</li>

                            <li>
                                <a href="<?= Routes::find('dashboard') ?>" class="waves-effect">
                                    <i class="mdi mdi-airplay"></i>
                                    <span> Dashboard <!-- <span class="badge badge-pill badge-primary float-right">7</span> --></span>
                                </a>
                            </li>

                            <li class="has_sub">
                                <a href="javascript:void(0)" class="waves-effect"><i class="mdi mdi-layers"></i> <span> Categories </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                                <ul class="list-unstyled">
                                    <?php foreach ($categories as $k => $categorie): $field = substr($categorie['field'], 8); ?>
                                        <li><a href="<?= Routes::find('category-show') .'/'. $field ?>"><?= $field ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </li>

                            <li>
                                <a href="#" class="waves-effect" data-animation="pulse" data-toggle="modal" data-target="#addcategorymodal">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span> New categorie <!-- <span class="badge badge-pill badge-primary float-right">7</span> --></span>
                                </a>
                            </li>

                            <li class="menu-title">Admin Settings</li>

                            <li>
                                <a href="<?= Routes::find('users-list') ?>" class="waves-effect">
                                    <i class="mdi mdi-account-multiple"></i>
                                    <span> Users <!-- <span class="badge badge-pill badge-primary float-right">7</span> --></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?= Routes::find('users-account') ?>" class="waves-effect">
                                    <i class="mdi mdi-settings"></i>
                                    <span> Account settings <!-- <span class="badge badge-pill badge-primary float-right">7</span> --></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>
            <!-- Left Sidebar End -->
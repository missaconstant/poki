<!-- Top Bar Start -->
<div class="topbar">

<nav class="navbar-custom">

    <ul class="list-inline float-right mb-0">

        <!-- language-->

        <li class="list-inline-item dropdown notification-list hide-phone">
            <a class="nav-link dropdown-toggle arrow-none waves-effect text-white" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="false" aria-expanded="false">
                English <img src="<?= THEME ?>assets/images/flags/us_flag.jpg" class="ml-2" height="16" alt=""/>
            </a>
            <div class="dropdown-menu dropdown-menu-right language-switch">
                <a class="dropdown-item" href="#"><img src="<?= THEME ?>assets/images/flags/french_flag.jpg" alt="" height="16"/><span> French </span></a>
            </div>
        </li>

        <!-- Messages -->

        <li class="list-inline-item dropdown notification-list">
            <!--
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <i class="ti-email noti-icon"></i>
                <span class="badge badge-danger noti-icon-badge">5</span>
            </a>
            -->
            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5><span class="badge badge-danger float-right">745</span>Messages</h5>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon"><img src="<?= THEME ?>assets/images/users/avatar-2.jpg" alt="user-img" class="img-fluid rounded-circle" /> </div>
                    <p class="notify-details"><b>Charles M. Jones</b><small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                </a>

                <!-- All-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    View All
                </a>

            </div>
        </li>

        <!-- Notification -->

        <li class="list-inline-item dropdown notification-list">
            <!--
            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="pages-blank.html#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <i class="ti-bell noti-icon"></i>
                <span class="badge badge-success noti-icon-badge">23</span>
            </a>
            -->
            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5><span class="badge badge-danger float-right">87</span>Notification</h5>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
                    <p class="notify-details"><b>Your order is placed</b><small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                </a>

                <!-- All-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    View All
                </a>

            </div>
        </li>

        <!-- User -->

        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <img src="<?= THEME ?>assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle">
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5>Hey, <?= explode(' ', $admin->name)[0] ?> !</h5>
                </div>
                <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a> -->
                <a class="dropdown-item" href="<?= Routes::find('users-account') ?>"><i class="mdi mdi-settings m-r-5 text-muted"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= Routes::find('logout-act') ?>"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
            </div>
        </li>

    </ul>

    <!-- Search box -->

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-light waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
        <li class="hide-phone app-search">
            <form role="search" class="pk-search" onsubmit="return false;">
                <input type="text" placeholder="Search..." class="form-control" value="<?= isset($issearch) ? $issearch : '' ?>">
                <a href="#"><i class="fa fa-search"></i></a>
            </form>
        </li>
    </ul>

    <div class="clearfix"></div>

</nav>

</div>
<!-- Top Bar End -->
<?php $user = get_active_user(); ?>
<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">

    <!-- navbar header -->
    <div class="navbar-header" style="height: 60px">
        <button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-box"><span class="hamburger-inner"></span></span>
        </button>

        <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="zmdi zmdi-hc-lg zmdi-more"></span>
        </button>

        <a href="<?php echo base_url(); ?>" class="navbar-brand">
            <span class="brand-icon"><i class="fa fa-database"></i></span>
            <span class="brand-name">SQL <small>Veri Bankası</small></span>
        </a>
    </div><!-- .navbar-header -->

    <div class="navbar-container container-fluid">
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
                <li class="hidden-float hidden-menubar-top">
                    <a href="javascript:void(0)" role="button" id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger">
                        <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                    </a>
                </li>
                <li>
                    <h5 style="font-size: small;" class="text-muted page-title hidden-menubar-top hidden-float">&copy; Tuzla Belediyesi Bilgi İşlem Müdürlüğü | <?php echo date('Y') ?></h5">
                </li>
            </ul>

            <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">

                <li class="dropdown">
                    <a style="padding-right: 10px;" href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-md avatar-circle" style="margin-top: -8px; margin-right: 0px; top: -2px;">
                                <img class="img-responsive"
                                     src="<?php echo base_url("uploads/users_v/$user->img_url"); ?>"
                                     alt="<?php echo base_url("uploads/users_v/$user->user_name"); ?>"
                                     style="display: inline; width: 40px; height: 40px;"
                                />
                        </div>
                        <span style="font-size: larger; margin-left: 0px"><?php echo $user->full_name; ?>
                            <p class="text-muted" style="display: inline; font-size: small">
                                <?php echo $user->title; ?>
                                 &nbsp;<i class="menu-caret zmdi zmdi-hc-lg zmdi-chevron-down"></i>
                            </p>

                    </a>
                    <ul class="dropdown-menu animated flipInY">
                        <li><a style="padding: 10px 16px" href="<?php echo base_url("users/update_form/$user->id"); ?>"><i class="zmdi m-r-md zmdi-hc-lg zmdi-face"></i>Kullanıcı Bilgilerim</a></li>
                        <li><a style="padding: 10px 16px" href="<?php echo base_url("users/update_password_form/$user->id"); ?>"><i class="zmdi m-r-md zmdi-hc-lg zmdi-lock-open"></i>Şifremi Değiştir</a></li>
                        <li><a style="padding: 10px 16px" href="<?php echo base_url("logout"); ?>"><i class="zmdi m-r-md zmdi-hc-lg zmdi-power"></i>Güvenli Çıkış</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div><!-- navbar-container -->
</nav>

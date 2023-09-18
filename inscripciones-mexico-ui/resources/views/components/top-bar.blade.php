
<div class="modal fade modal-language modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="defaultModalLabel"><?php echo __('top-bar.language'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="/?locale=es"><small><strong>Espa&ntilde;ol</strong></small></a>
                        </div>
                    </div>
                </div>
                <div class="list-group-item bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="/?locale=en"><small><strong>English</strong></small></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<nav class="topnav navbar navbar-light">
<button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
    <i class="fe fe-menu navbar-toggler-icon"></i>
</button>
<ul class="nav">
    <?php
        if (session('user') != null) {
            // We have an active session
    ?>
            <li class="nav-item nav-language">
            <a class="nav-link text-muted my-2" href="#" data-toggle="modal" data-target=".modal-language">
                <span class="fe fe-globe fe-16"></span>
            </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted pr-0" href="/logout" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar avatar-sm mt-2">
                        <?php echo session('user')->name; ?>&nbsp;
                        <img src="<?php echo session('user')->avatar; ?>" class="avatar-img rounded-circle">
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/profile?userId=<?php echo session('user')->encoded_id; ?>"><i class="fe fe-user fe-16"></i>&nbsp;&nbsp;<?php echo __('login.profile'); ?></a>
                    <a class="dropdown-item" href="/logout"><i class="fe fe-log-out fe-16"></i>&nbsp;&nbsp;<?php echo __('login.logout'); ?></a>
                    <a class="dropdown-item" href="#" id="modeSwitcher" data-mode="dark"><i class="fe fe-sun fe-16"></i>&nbsp;&nbsp;<?php echo __('menu.mode-switcher'); ?></a>
                </div>
            </li>
    <?php
        } else {
            // No sessions detected
    ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="avatar avatar-sm mt-2">
                        <img src="assets/images/user.jpg" alt="<?php echo __('login.login'); ?>" class="avatar-img rounded-circle">
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/login"><i class="fe fe-log-in fe-16"></i>&nbsp;&nbsp;<?php echo __('login.login'); ?></a>
                    <a class="dropdown-item" href="#" id="modeSwitcher" data-mode="dark"><i class="fe fe-sun fe-16"></i>&nbsp;&nbsp;<?php echo __('menu.mode-switcher'); ?></a>
                </div>
            </li>
    <?php
        }
    ?>
    
</ul>
</nav>
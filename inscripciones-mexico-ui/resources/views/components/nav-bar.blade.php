<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="/">
              <img src="assets/images/logo.png" width="85" height="85">
            </a>
          </div>
          <p class="text-muted nav-heading mt-4 mb-1">
            <span><?php echo __('menu.menu'); ?></span>
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <?php
            if (session('user') != null) {
                // We have an active session
            ?>
              <li class="nav-item dropdown">
                <a href="#ui-elements" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                  <i class="fe fe-lock fe-16"></i>
                  <span class="ml-3 item-text"><?php echo __('menu.session'); ?></span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">

                  <li class="nav-item w-100">
                    <a class="nav-link" href="/profile?userId=<?php echo session('user')->encoded_id; ?>">
                      <i class="fe fe-user fe-16"></i>
                      <span class="ml-3 item-text"><?php echo __('menu.profile'); ?></span>
                    </a>
                  </li>
                  <li class="nav-item w-100">
                    <a class="nav-link" href="/logout">
                      <i class="fe fe-log-out fe-16"></i>
                      <span class="ml-3 item-text"><?php echo __('menu.logout'); ?></span>
                    </a>
                  </li>
                </ul>
              </li>
            <?php
            } else {
              // No active session
            ?>
            <ul class="navbar-nav flex-fill w-100 mb-2">
              <li class="nav-item w-100">
                <a class="nav-link" href="/login">
                  <i class="fe fe-log-in fe-16"></i>
                  <span class="ml-3 item-text"><?php echo __('menu.login'); ?></span>
                </a>
              </li>
            </ul>
            <?php
            }
            ?>
            <?php
            if (session('user') != null && session('user')->hasPermission('session.profile.roles-and-permissions.read')) {
            ?>
              <li class="nav-item dropdown">
                <a href="#settings" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                  <i class="fe fe-settings fe-16"></i>
                  <span class="ml-3 item-text"><?php echo __('menu.settings'); ?></span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="settings">
                  <?php
                  if (session('user')->hasPermission('session.profile.roles-and-permissions.read')) {
                  ?>
                    <li class="nav-item w-100">
                      <a class="nav-link" href="/roles-and-permissions">
                        <i class="fe fe-lock fe-16"></i>
                        <span class="ml-3 item-text"><?php echo __('menu.settings.roles-and-permissions'); ?></span>
                      </a>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </li>
            <?php
            }
            ?>
          </ul>
        </nav>
      </aside>
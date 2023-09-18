<x-header />
<x-top-bar />
<x-nav-bar />

<main role="main" class="main-content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="col-md-12 mb-4">
            <!-- Striped rows -->
            <div class="col-md-12 my-4">
              <div class="card shadow">
                <div class="card-body">
                  <h5 class="card-title"><?php echo __('settings.roles-and-permissions.roles'); ?></h5>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th><?php echo __('settings.roles-and-permissions.name'); ?></th>
                        <th><?php echo __('settings.roles-and-permissions.description'); ?></th>
                        <th><?php echo __('settings.roles-and-permissions.action'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $role)
                      @if ($role->name == 'admin' && !session('user')->isAdmin())
                          @continue
                      @endif
                      <tr>
                        <td>
                          <h5 class="card-title"><span class="badge badge-pill badge-success">{{ $role->name }}</span></h5>
                        </td>
                        <td>{{ $role->description }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle" type="button" id="dr1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="text-muted sr-only"><?php echo __('settings.roles-and-permissions.action'); ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dr1">
                              <a class="dropdown-item" href="/role?roleId={{ $role->encoded_id }}"><i class="fe fe-edit-3 fe-16"></i>&nbsp;&nbsp;<?php echo __('settings.roles-and-permissions.edit'); ?></a>
                              <?php
                              if (session('user') != null && session('user')->hasPermission('session.profile.roles-and-permissions.write')) {
                              ?>
                                <a class="dropdown-item" href="/deleteRole&roleId={{ $role->encoded_id }}"><i class="fe fe-trash-2 fe-16"></i>&nbsp;&nbsp;<?php echo __('settings.roles-and-permissions.delete'); ?></a>
                              <?php
                              }
                              ?>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div> <!-- Striped rows -->
          </div>
        </div>
      </div>
    </div>
  </main>

<x-footer />
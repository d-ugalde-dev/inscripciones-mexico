<x-header />
<x-top-bar />
<x-nav-bar />

<?php
if ($role->name != 'admin' && session('user')->hasPermission('session.profile.roles-and-permissions.write')) {
?>
  <div class="modal fade" id="modalEditRoleName" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="mb-1"><?php echo __('settings.roles-and-permissions.role'); ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="/update-role" method="POST">
          @csrf
          <input type="hidden" id="encodedId" name="encodedId" value="{{ $role->encoded_id }}">
          <div class="modal-body">
              <div class="form-group">
                <label for="name"><strong>&ast;&nbsp;<?php echo __('settings.roles-and-permissions.name'); ?></strong></label>
                <input type="text" id="roleName" name="roleName" class="form-control" placeholder="<?php echo __('profile.name'); ?>" value="{{ $role->name }}" maxlength="120" required />
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn mb-2 btn-outline-secondary" type="button" data-dismiss="modal"><span class="fe fe-x fe-16"></span></button>
            <button class="btn mb-2 btn-outline-primary" type="submit"><span class="fe fe-save fe-16"></span></button>
          </div>
      </form>
      </div>
    </div>
  </div>
<?php
}
?>

<main role="main" class="main-content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="col-md-12 mb-4">
            <div class="col-md-12 my-4">
              <div class="card shadow">
                <div class="card-body">
                  <?php
                  if ($role->name != 'admin' && session('user')->hasPermission('session.profile.roles-and-permissions.write')) {
                  ?>
                    <a data-toggle="modal" data-target="#modalEditRoleName"><h5 class="card-title"><span class="badge badge-pill badge-success">{{ $role->name }}</span></h5></a>
                  <?php
                  } else {
                  ?>
                    <h5 class="card-title"><span class="badge badge-pill badge-success">{{ $role->name }}</span></h5>
                  <?php
                  }
                  ?>
                  <table class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th></th>
                        <th><?php echo __('settings.roles-and-permissions.name'); ?></th>
                        <th><?php echo __('settings.roles-and-permissions.description'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($all_permissions as $permission)

                    @if ($permission->name == 'all-permissions' && !session('user')->isAdmin())
                        @continue
                    @endif
                    
                    <?php
                    $isAssigned = false;
                    $checkedAttribute = '';
                    
                    foreach ($permissions_by_role as $permissionByRole) {
                      if ($permissionByRole->id == $permission->id) {
                        $isAssigned = true;
                        $checkedAttribute = ' checked="checked" ';
                        break;
                      }
                    }
                    ?>

                    <tr>
                      <td>
                        <form id="ajax-form-permission-{{ $permission->encoded_id }}" action="/relate-permission-to-role" method="POST">
                          <input type="hidden" id="permission_encoded_id" name="permission_encoded_id" value="{{ $permission->encoded_id }}" />
                          <input type="hidden" id="role_encoded_id" name="role_encoded_id" value="{{ $role->name }}" />
                          <input type="hidden" id="relate" name="relate" value="true" />
                          <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" <?php echo $checkedAttribute; ?> id="permission-{{ $permission->encoded_id }}" />
                              <label class="custom-control-label" for="permission-{{ $permission->encoded_id }}"></label>
                          </div>
                        </form>
                      </td>
                      <td>
                        <h5 class="card-title"><br/><span class="badge badge-pill badge-primary">{{ $permission->name }}</h5>
                      </td>
                      <td>{{ $permission->description }}</td>
                    </tr>

                    <script>
                      $('#permission-{{ $permission->encoded_id }}').change(function () {
                          
                          var isChecked = $('#permission-{{ $permission->encoded_id }}')[0].checked;

                          $.ajax({

                              url: '/relate-permission-to-role',
                              type: 'POST',
                              data: {
                                body: '{"permission_encoded_id":"{{ $permission->encoded_id }}","role_encoded_id":"{{ $role->encoded_id }}","relate":"' + isChecked + '"}',
                                _token: "{{csrf_token()}}"
                              },
                              
                              success: function (response) {
                              },

                              error: function (xhr, status, error) {
                                console.error(error);
                              }

                          });
                      });
                    </script>

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
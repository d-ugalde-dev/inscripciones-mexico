<x-header />
<x-top-bar />
<x-nav-bar />

<?php
$isEditAllowed = (session('user') != null && (session('user')->encoded_id == $profile_user->encoded_id || session('user')->hasPermission('session.profile.roles-and-permissions.write')));
?>


<?php
if ($isEditAllowed) {
?>

<!-- modal edit profile -->
<div class="modal fade" id="modalEditUserDetails" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="mb-1"><?php echo __('profile.profile'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/update-profile" method="POST">
        @csrf
        <input type="hidden" id="encodedId" name="encodedId" value="{{ $profile_user->encoded_id }}">
        <input type="hidden" id="email" name="email" class="form-control" placeholder="<?php echo __('profile.email'); ?>" value="{{ $profile_user->email }}" disabled />

        <div class="modal-body">
            <div class="form-group">
              <label for="name"><strong>&ast;&nbsp;<?php echo __('profile.name'); ?></strong></label>
              <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo __('profile.name'); ?>" value="{{ $profile_user->name }}" maxlength="120" required />
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
          <h4 class="mb-1"><?php echo __('profile.profile'); ?></h4>
          <div class="card profile shadow">
            <div class="card-body my-4">
              <div class="row align-items-center">
                <div class="col-md-3 text-center mb-5">
                  <a href="#!" class="avatar avatar-xl">
                    <img src="{{ $profile_user->avatar }}" class="avatar-img rounded-circle">
                  </a>
                </div>
                <div class="col">
                  <div class="row align-items-center">
                    <div class="col-md-7">
                      <h4 class="mb-1">{{ $profile_user->name }}</h4>
                      <?php
                      if (session('user') != null && session('user')->hasAtLeastOnePermission(
                        [
                          'session.profile.roles-and-permissions.read',
                          'session.profile.roles-and-permissions.write'
                        ])) {
                      ?>
                        <p class="mb-3">
                          <?php
                          if (session('user') != null && session('user')->hasPermission('session.profile.roles-and-permissions.read')) {
                          ?>
                              @foreach ($profile_user->getAllRoles() as $role)
                                <span class="badge badge-pill badge-success">{{ $role }}</span>
                              @endforeach
                          <?php
                          }
                          ?>
                        </p>
                      <?php
                      }
                      ?>
                      <p class="mb-3">
                        <span class="badge badge-pill badge-primary"><i class="fe fe-mail fe-8"></i>&nbsp;&nbsp;{{ $profile_user->email }}</span>
                      </p>
                    </div>
                  </div>
                  <div class="row align-items-center">
                    <div class="col-md-7 mb-2">
                      <span class="small text-muted mb-0"><?php echo __('profile.member-since'); ?>
                      <script>document.write(getDateStringFormatted('<?php echo $profile_user->created_on; ?>', '<?php echo __("application-language") ?>', true));</script></span>
                    </div>
                    <?php
                    if ($isEditAllowed) {
                    ?>
                      <div class="col mb-2">
                        <button type="button" class="btn mb-2 btn-outline-primary" data-toggle="modal" data-target="#modalEditUserDetails"><span class="fe fe-edit-3 fe-16"></span>&nbsp;<?php echo __("profile.edit") ?></button>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<x-footer />
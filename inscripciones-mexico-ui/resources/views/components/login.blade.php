<x-header-login />

<div class="row align-items-center h-100">
<form class="col-lg-3 col-md-4 col-10 mx-auto text-center">
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="/">
        <h1 class="h6 mb-3"><?php echo __('application-name'); ?></h1>
        <img src="assets/images/logo.png" width="240" />
    </a>
    <h1 class="h6 mb-3"><?php echo __('login.login'); ?></h1>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 mb-12">
                <a href="/login-google">
                    <button type="button" class="btn mb-12 btn-outline-secondary" >
                        <img src="assets/images/google.svg" width="45" height="45">
                        &nbsp;&nbsp;&nbsp;
                        <?php echo __('login.continue-with-google'); ?>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 mb-12">
                <a href="/login-facebook">
                    <button type="button" class="btn mb-12 btn-outline-secondary" >
                        <img src="assets/images/facebook.svg" width="40" height="40">
                        &nbsp;&nbsp;&nbsp;
                        <?php echo __('login.continue-with-facebook'); ?>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <p class="mt-5 mb-3 text-muted"><?php echo __('application-name'); ?> <?php echo __('application-copyright-year'); ?></p>
</form>
</div>

<x-footer-login />
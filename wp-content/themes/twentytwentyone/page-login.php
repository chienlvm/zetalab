<?php

/**
 * The template for display login -page
 *
 *
 * @package ChienLVM
 * @subpackage ChienLVM
 * @since V1
 * @author chienlvm
 */

get_header();
?>
<div class="bg-account-pages py-4 py-sm-0">
  <div class="account-home-btn d-none d-sm-block">
    <a href="/" class="text-white">
      <i class="mdi mdi-home h1"></i>
    </a>
  </div>
  <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
  <input type="hidden" name="action" value="custom_login">
    <section class="height-100vh">
      <div class="display-table">
        <div class="display-table-cell">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card account-card">
                  <div class="card-body">
                    <div class="text-center mt-3">
                      <h3 class="font-weight-bold">
                        <a href="/" class="text-dark text-uppercase account-pages-logo">
                          <i class="mdi mdi-alien"></i>ZetaLab
                        </a>
                      </h3>
                      <p class="text-muted">Sign in to continue to ZetaLab.</p>
                    </div>
                    <div class="p-3">
                      <form>
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control" placeholder="email@example.com" name="email" id="username" value="admin@test.com">
                        </div>

                        <div class="form-group">
                          <label for="userpassword">Password</label>
                          <input type="password" name="password" id="userpassword" placeholder="Enter password" class="form-control" value="test">
                        </div>

                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customControlInline" name="remember"/>
                          <label class="custom-control-label" for="customControlInline">Remember me</label>
                        </div>

                        <div class="mt-3">
                          <button type="submit" class="btn btn-custom btn-block">Log In</button>
                        </div>

                        <div class="mt-4 mb-0 text-center">
                          <a tag="a" to="/password_forgot" class="text-dark">
                            <i class="mdi mdi-lock"></i> Forgot your password?
                          </a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- end card -->
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->
          </div>
        </div>
      </div>
    </section>
  </form>
  <!-- end account-pages  -->
</div>
<?php
get_footer();

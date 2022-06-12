<?php 
error_reporting(0);
ini_set('display_errors', 0);
?>
<div class="container-fluid vh-100" style="margin-top:300px">
    <div class="" style="margin-top:200px">
        <div class="rounded d-flex justify-content-center">
            <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                <div class="text-center">
                    <h3 class="text-primary">Sign In</h3>
                </div>
                <form method="POST" action="config.php">
                    <input type="text" hidden name="login_action" value="login">
                    <?= $msg ?>
                    <div class="p-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary"><i style="color: white;" class="fas fa-user"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary"><i style="color: white;" class="fas fa-lock-open"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Şifre">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Beni Hatırla
                            </label>
                        </div>
                        <button class="btn btn-primary text-center mt-2" type="submit">
                            Giriş Yap
                        </button>
                        <!-- <p class="text-center mt-5">Don't have an account?
                            <span class="text-primary">Sign Up</span>
                        </p>
                        <p class="text-center text-primary">Forgot your password?</p> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
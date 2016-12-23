<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="container register-box">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Register</h1>
            </div>
            <?= form_open() ?>
                <div class="form-group">
                    <label for="username">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                    <p class="help-block">Required</p>
                </div>
                <div class="form-group">
                    <label for="username">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                    <p class="help-block">Required</p>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <p class="help-block">A valid email address</p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password">
                    <p class="help-block">At least 6 characters</p>
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirm password</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password">
                    <p class="help-block">Must match your password</p>
                </div>


                <?php if (validation_errors()) : ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                                <?= validation_errors() ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($error)) : ?>
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                                <?= $error ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Register">
                    <p style="margin-top: 5px;">Already have an account? <a href="login">Login</a></p>
                </div>
            </form>
        </div>
    </div><!-- .row -->
</div><!-- .container -->
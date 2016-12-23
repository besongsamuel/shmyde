<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="container login-box">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Login</h1>
            </div>
            <?= form_open() ?>
                <div class="form-group">
                    <label for="username">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Your email address">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Your password">
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
                    <input type="submit" class="btn btn-default" value="Login">
                    <p style="margin-top: 5px;">Don't have an account? <a href="register">Register</a></p>
                </div>
            </form>
        </div>
    </div><!-- .row -->
</div><!-- .container -->
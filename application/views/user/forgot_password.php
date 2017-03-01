<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container white-background"  ng-controller="UserController as user">
    <h3 class="text-center">Recover Password</h3>
    <div class="row">
        <div class="container forgot-password">
            <p>Please enter your email below and we will send you details on how to reset your password.</p>
            <div class="alert alert-info col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="forgotPasswordForm.email.$pending.isUniqueEmail">
                <span>Checking if email exists...</span>
            </div>
            <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="forgotPasswordForm.email.$valid">
                <span>The email entered doesn't exist in our database!</span>
            </div>
            <?= form_open('user/forgot_password',array('id'=>'forgot_password_form','method'=>'post', 'name' => 'forgotPasswordForm')) ?> 
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input  type="email" name="email" class="form-control" placeholder="Email" ng-model="user.email" required is-unique-email>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Send" style="float : right;">
                </div>
            </form>
        </div>
    </div><!-- .row -->  
</div><!-- .container -->
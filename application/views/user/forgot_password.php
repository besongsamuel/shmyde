<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container white-background" style="margin-top: 10%;"  ng-controller="UserController">
    <h3 class="text-center">Recover Password</h3>
    <p style="margin: auto; text-align: center;">Please enter your email below and we will send you details on how to reset your password.</p>
    <form name="forgotPasswordForm" ng-submit="submit_email()" novalidate>
        <!-- Email control -->
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input  id="email" type="email" name="email" class="form-control" placeholder="Email" ng-model="forgotPasswordObject.email"
                             ng-required='required' email>
                    <span class="form_hint" ng-hide="forgotPasswordForm.email.valid">                                      
                        <p ng-show="forgotPasswordForm.email.$error.required">This field is required. </p>
                        <p ng-show="forgotPasswordForm.email.$error.email">The email entered is invalid. </p>
                    </span>
                </div>
            </div>                           
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="form-group">
                    <input type="submit" class="btn btn-default pull-right" value="Send">
                </div>
            </div>
        </div>
    </form>
</div><!-- .container -->
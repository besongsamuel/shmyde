<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <div class="row">
        <div class="container choose-password">
            <form name="setPasswordForm" class="shmyde-form  col-lg-12 text-center"  novalidate ng-submit="setPassword()">
                <!-- Password  -->
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                            <input id="password"  type="password" name="password" class="form-control" placeholder="Password" ng-required='required' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" ng-model="password">
                            <span class="form_hint" ng-hide="setPasswordForm.password.valid">                                      
                                <p ng-show="setPasswordForm.password.$error.required">A password is required to register. </p>
                                <p ng-show="setPasswordForm.password.$error.pattern">The password must contain 8 or more characters that are of at least one number, and one uppercase and lowercase letter. </p>
                            </span>
                        </div>
                    </div>
                </div>
                        
                <!-- Confirm Password -->
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                            <input  type="password" id="confirm_password" name="confirm_password" class="form-control" ng-required='required' placeholder="Confirm Password" ng-model="confirm_password" pw-check='password'>
                            <span class="form_hint" ng-hide="setPasswordForm.confirm_password.valid">     
                                <p ng-show="setPasswordForm.confirm_password.$error.required">A password is required to register. </p>
                                <p ng-show="setPasswordForm.confirm_password.$error.pwmatch">The passwords entered don't match. </p>
                            </span>                                
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Reset">
                </div>
            </form>
        </div>
    </div><!-- .row -->  
</div><!-- .container -->

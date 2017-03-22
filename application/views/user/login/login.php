<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container white-background" style="margin-top: 50px;" ng-controller="UserController" id="login-registration-container">
  <h3 class="text-center">Login or Register</h3>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#login">Login</a></li>
        <li><a data-toggle="tab" href="#register">Register</a></li>
    </ul>  
    <div class="tab-content">
        <div id="login" class="tab-pane fade in active">
            <div class="row">
                <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="loginError">
                    <span><strong>Login Error: </strong>{{login_error_message}}</span>
                </div>
                <form name="loginForm" class="shmyde-form col-lg-12 text-center" ng-submit='login()' novalidate>
                    
                        <!-- Email control -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input  id="email" type="email" name="email" class="form-control" placeholder="Email" ng-model="loginObject.email"
                                             ng-required='required' email>
                                    <span class="form_hint" ng-hide="loginForm.email.valid">                                      
                                        <p ng-show="loginForm.email.$error.required">This field is required. </p>
                                        <p ng-show="loginForm.email.$error.email">The email entered is invalid. </p>
                                    </span>
                                </div>
                            </div>                           
                        </div>
                    
                        <!-- Password  -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" ng-required='required' ng-minlength='8' ng-model="loginObject.password">
                                    <span class="form_hint" ng-hide="loginForm.password.valid">                                      
                                        <p ng-show="loginForm.password.$error.required">A password is required to register. </p>
                                        <p ng-show="loginForm.password.$error.minlength">The password entered is too short. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <label for="remember_me">Remember me</label>
                                    <input type="checkbox" class="form-control" id="remember_me" name="remember_me" value="on" ng-model='loginObject.remember_me'>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <a href="<?php echo site_url('user/forgot_password'); ?>">Forgot Password? </a>
                            </div>
                        </div>                    
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <button type="submit" class="btn btn-default pull-right">Login</button>
                            </div>
                        </div>                   
                </form>
            </div>
        </div>
        
        <div id="register" class="tab-pane fade">
            <div class="tab-pane fade in active">
                
                <div class="row">
                    <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;"  ng-show="register_error">
                        <span><strong>Registration Error: </strong>{{registration_error_message}}</span>
                    </div>
                    <form name="registerForm" class="shmyde-form  col-lg-12 text-center"  novalidate ng-submit="register()">
                                                
                        <!-- Email control -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input id="r_email"  type="email" name="email" class="form-control" placeholder="Email" ng-model="registrationObject.email"
                                             ng-required='required' is-unique-email>
                                    <span class="form_hint" ng-hide="registerForm.email.valid">                                      
                                        <p ng-show="registerForm.email.$error.required">This field is required. </p>
                                        <p ng-show="registerForm.email.$error.email">The email entered is invalid. </p>
                                        <div class="loader" ng-show="registerForm.email.$pending"></div>
                                        <p ng-show="registerForm.email.$error.isUniqueEmail">This email address is already taken. </p>
                                    </span>
                                </div>
                            </div>                           
                        </div>
                        
                        <!-- Last name and First name input controls -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input  id="r_lastname"  type="text" name="lastname" ng-model="registrationObject.last_name" class="form-control" placeholder="Last Name" ng-required='required'>
                                    <span class="form_hint" ng-hide="registerForm.lastname.valid">                                      
                                        <p ng-show="registerForm.lastname.$error.required">At least a last name is required. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input  type="text" name="firstname" ng-model="registrationObject.first_name" class="form-control" placeholder="First Name">                                    
                                </div>                                
                            </div>
                        </div>
                        
                        <!-- Phone number controls -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <!-- Phone number control -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                                    <input id="r_phonenumber"  type="text" name="phonenumber" ng-model="registrationObject.phone_number" class="input-group" international-phone-number ng-focus="phone_number_focus()" ng-blur="phone_number_blur()">
                                    <span class="phone_number_hint" ng-show="phone_number_has_focus && registerForm.phonenumber.$error.internationalPhoneNumber">                                      
                                        <p ng-show="registerForm.phonenumber.$error.internationalPhoneNumber">The phone number entered is invalid. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Country -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
                                    <select  class="form-control"  ng-model="registrationObject.country" name="country" pvp-country-picker>

                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- City -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input  type="text" class="form-control" name="city"   ng-model="registrationObject.city" placeholder="City">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Line 01 -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <!-- Address controls -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                                    <input  type="text" class="form-control" placeholder="Address Line 1" name="address_line_1" ng-model="registrationObject.address_line_1">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Line 02 -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                                    <input  type="text" class="form-control" placeholder="Address Line 2" name="address_line_2" ng-model="registrationObject.address_line_2">
                                </div>
                            </div>
                        </div>
                                              
                        <!-- Password  -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                                    <input id="r_password"  type="password" name="password" id="password" class="form-control" placeholder="Password" ng-required='required' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" ng-model="registrationObject.password">
                                    <span class="form_hint" ng-hide="registerForm.password.valid">                                      
                                        <p ng-show="registerForm.password.$error.required">A password is required to register. </p>
                                        <p ng-show="registerForm.password.$error.pattern">The password must contain 8 or more characters that are of at least one number, and one uppercase and lowercase letter. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                                    <input  type="password" id="r_confirm_password" name="confirm_password" class="form-control" ng-required='required' placeholder="Confirm Password" ng-model="registrationObject.confirm_password" pw-check='r_password'>
                                    <span class="form_hint" ng-hide="registerForm.confirm_password.valid">     
                                        <p ng-show="registerForm.confirm_password.$error.required">A password is required to register. </p>
                                        <p ng-show="registerForm.confirm_password.$error.pwmatch">The passwords entered don't match. </p>
                                    </span>                                
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <button type="submit" class="btn btn-default pull-right">Register</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

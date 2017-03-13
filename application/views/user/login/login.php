<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
    
    
    $(document).ready(function(){
        
        var userScope = angular.element("#login-registration-container").scope();
        
        userScope.$apply(function() 
        {
            userScope.login_error = JSON.parse('<?php echo $login_error;  ?>').toString() === 'true';
        });
               
    });
    
</script>

<div class="container white-background" style="margin-top: 50px;" ng-controller="UserController" id="login-registration-container">
  <h3 class="text-center">Login or Register</h3>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#login">Login</a></li>
        <li><a data-toggle="tab" href="#register">Register</a></li>
    </ul>  
    <div class="tab-content">
        <div id="login" class="tab-pane fade in active">
            <div class="row">
                <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="login_error">
                    <span><strong>Login Error: </strong>Incorrect email or password. </span>
                </div>
                <form action="<?php echo site_url('user/login'); ?>" method="POST" class="shmyde-form col-lg-6 col-lg-offset-3 text-center" novalidate>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input  type="email" name="email" class="form-control" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
                            <span class="form_hint">Proper format "name@something.com"</span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                        <input  type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <label for="remember_me">Remember me</label>
                        <input type="checkbox" class="form-control" id="remember_me" name="remember_me" value="on" style="height: 22px; width: 22px; margin: 5px;">
                    </div>
                    <div class="input-group">
                        <a href="<?php echo site_url('user/forgot_password'); ?>">Forgot Password? </a>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-block">Login</button>
                    </div>                   
                </form>
            </div>
        </div>
        
        <div id="register" class="tab-pane fade">
            <div class="tab-pane fade in active">
                
                <div class="row">
                    <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;">
                        <span>There are errors in the form!</span>
                        <pre>{{userObject.user | json}}</pre>
                    </div>
                    <form name="registerForm" class="shmyde-form  col-lg-12 text-center"  novalidate ng-submit="registerForm.$valid && register()">
                                                
                        <!-- Email control -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input  type="email" name="email" class="form-control" placeholder="Email" ng-model="userObject.user.email"
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
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input  type="text" name="lastname" ng-model="userObject.user.last_name" class="form-control" placeholder="Last Name" ng-required='required'>
                                    <span class="form_hint" ng-hide="registerForm.lastname.valid">                                      
                                        <p ng-show="registerForm.lastname.$error.required">At least a last name is required. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input  type="text" name="firstname" ng-model="userObject.user.first_name" class="form-control" placeholder="First Name">                                    
                                </div>                                
                            </div>
                        </div>
                        
                        <!-- Phone number controls -->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Phone number control -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                                    <input  type="text" name="phonenumber" ng-model="userObject.user.phone_number" class="input-group" international-phone-number ng-focus="phone_number_focus()" ng-blur="phone_number_blur()">
                                    <span class="phone_number_hint" ng-show="phone_number_has_focus && registerForm.phonenumber.$error.internationalPhoneNumber">                                      
                                        <p ng-show="registerForm.phonenumber.$error.internationalPhoneNumber">The phone number entered is invalid. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Country -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
                                    <select  class="form-control"  ng-model="userObject.user.country" name="country" pvp-country-picker>

                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- City -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input  type="text" class="form-control" name="city"   ng-model="userObject.user.city" placeholder="City">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Line 01 -->
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Address controls -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                                    <input  type="text" class="form-control" placeholder="Address Line 1" name="address_line_1" ng-model="userObject.user.address_line_1">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Line 02 -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                                    <input  type="text" class="form-control" placeholder="Address Line 2" name="address_line_2" ng-model="userObject.user.address_line_2">
                                </div>
                            </div>
                        </div>
                                              
                        <!-- Password  -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                                    <input  type="password" name="password" id="password" class="form-control" placeholder="Password" ng-required='required' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" ng-model="userObject.user.password">
                                    <span class="form_hint" ng-hide="registerForm.password.valid">                                      
                                        <p ng-show="registerForm.password.$error.required">A password is required to register. </p>
                                        <p ng-show="registerForm.password.$error.pattern">The password must contain 8 or more characters that are of at least one number, and one uppercase and lowercase letter. </p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                                    <input  type="password" id="confirm_password" name="confirm_password" class="form-control" ng-required='required' placeholder="Confirm Password" ng-model="confirm_password" pw-check='password'>
                                    <span class="form_hint" ng-hide="registerForm.confirm_password.valid">     
                                        <p ng-show="registerForm.confirm_password.$error.required">A password is required to register. </p>
                                        <p ng-show="registerForm.confirm_password.$error.pwmatch">The passwords entered don't match. </p>
                                    </span>                                
                                </div>
                            </div>
                        </div>
                                                                                          
                        <div class="input-group">
                            <button type="submit" class="btn btn-block">Register</button>
                        </div>                   
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
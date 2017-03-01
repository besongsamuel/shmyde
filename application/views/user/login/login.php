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

<div class="container white-background" style="margin-top: 50px;" ng-controller="UserController as user" id="login-registration-container">
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
                    <div class="alert alert-info col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="registerForm.email.$pending.isUniqueEmail">
                        <span>Checking if this name is available...</span>
                    </div>
                    <div class="alert alert-danger col-lg-6 col-lg-offset-3" style="margin-top: 10px;" ng-show="registerForm.email.$invalid">
                        <span>This username is already taken!</span>
                    </div>
                    <form  action="<?php echo site_url('user/register'); ?>" method="POST" name="registerForm" class="shmyde-form  col-lg-6 col-lg-offset-3 text-center" novalidate>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            <input  type="email" name="email" class="form-control" placeholder="Email" ng-model="user.email" required is-unique-email>
                            
                            <span class="form_hint">Proper format "name@something.com"</span>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input  type="text" name="lastname" class="form-control" placeholder="Last name">
                            <input  type="text" name="firstname" class="form-control" placeholder="First name" required>
                            <span class="form_hint">At least a last name is required</span>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                            <input  type="text" name="phonenumber" class="form-control" placeholder="Mobile Phone Number" pattern="[+3]?[0-9]*" required>
                            <span class="form_hint">Please enter a phone number in the format +Country Code + Phone Number</span>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
                            <select  class="form-control"  ng-model="user.country" pvp-country-picker>

                            </select>
                            <input  type="text" class="form-control" placeholder="City">
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                            <input  type="text" class="form-control" placeholder="Address Line 1">
                            <input  type="text" class="form-control" placeholder="Address Line 2">
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
                            <input  type="password" name="password" class="form-control" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                            <span class="form_hint">The password must contain 8 or more characters, at least one number, and one uppercase and lowercase letter</span>
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
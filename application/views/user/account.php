<!DOCTYPE html>

<div style="background-color: white" ng-controller="AccountController" id="account-container" class="container">
    
    <!-- Profile Header -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<script>
    $(document).ready(function()
    {
        var userScope = angular.element("#account-container").scope();
        
        userScope.$apply(function()
        {
            userScope.user_orders = JSON.parse('<?php echo $shmyde_orders; ?>');
            userScope.first_name = userScope.userObject.user.first_name;
            userScope.last_name = userScope.userObject.user.last_name;
            userScope.gender = userScope.userObject.user.gender;
            var dob = new Date(userScope.userObject.user.dob.toString().replace("-","/"));
            userScope.dob = dob;
            userScope.address_line_1 = userScope.userObject.user.address_line_1;
            userScope.address_line_2 = userScope.userObject.user.address_line_2;
            userScope.country = userScope.userObject.user.country;
            userScope.city = userScope.userObject.user.city;
            
        });
    });
</script>

<div class="container">
    <div class="shmyde-profile">
        <img align="left" class="shmyde-image-profile thumbnail" ng-src="<?php echo ASSETS_PATH; ?>images/account/{{userObject.user.avatar}}" alt="Profile image example"/>
        <div class="shmyde-profile-text">
            <h1>{{userObject.user.last_name + ", " + userObject.user.first_name}}</h1>
            <p>{{userObject.user.email}}.</p>
        </div>
    </div>
</div> 

<!-- Edit User Details Section -->
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <form name="userDetailsForm" class="form-horizontal" role="form" novalidate ng-submit="saveUserDetails()">
        <fieldset>

          <!-- Form Name -->
          <legend>User Details</legend>
          
            <div class="row">
                <div class="shmyde-loading" ng-show="user_detail_loading">
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                </div>
            </div>
            <div class="row" ng-show="!user_detail_loading" style="text-align: center; font-style: italic;">
                <p>{{user_detail_message}}</p>
            </div>
          
            <!-- Last name and First name input-->
            <div class="form-group">
                <label class="col-sm-2 control-label" for="last_name">Last Name</label>
                <div class="col-sm-4">
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control" ng-model="last_name">
                </div>

                <label class="col-sm-2 control-label" for="first_name">First Name</label>
                <div class="col-sm-4">
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control" ng-model="first_name">
                </div>
            </div>
            
              <!-- Gender input-->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="gender">Gender</label>
                <div class="col-sm-10">
                    <select name="gender" id="gender" ng-model="gender" class="form-control">
                        <option value='none'>-- Select Gender --</option>
                        <option value='male'>Male</option>
                        <option value='female'>Female</option>
                        <option value='other'>Other</option>
                    </select>
                </div>
              </div>
            
          <!-- DoB input-->
          <div class="form-group">
                <label class="col-sm-2 control-label" for="dob">Date of Birth</label>
                <div class="col-sm-10">
                    <input type="date" name="dob" id="dob" ng-model="dob" class="form-control">
                </div>
          </div>   

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <button class="btn btn-default" ng-click="cancelUserDetails()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<!-- Change Password -->
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <form name="changePasswordForm" class="form-horizontal" role="form" novalidate ng-submit="changeUserPassword()">
        <fieldset>

          <!-- Form Name -->
          <legend>Change Password</legend>
          
            <div class="row" ng-show="change_password_loading">
                <div class="shmyde-loading">
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                </div>
            </div>
            <div class="row" ng-show="!change_password_loading" style="text-align: center; font-style: italic;">
                <p>{{change_password_message}}</p>
            </div>

          <!-- Old Password-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Enter Old Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="Old Password"  ng-model="old_password" name="old_password" id='old_password' class="form-control">
            </div>
          </div>
          
          <!-- New Password -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">New Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="New Password" ng-model="new_password" name="new_password" id='new_password' class="form-control">
            </div>
          </div> 
            
          <!-- Confirm New Password -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Confirm New Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="Confirm New Password" ng-model="confirm_new_password" name="confirm_new-password" id='confirm_new_password' class="form-control">
            </div>
          </div>   

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <button class="btn btn-default" ng-click="cancelUserPassword()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
    
    

<!-- Edit Address Section -->
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <form name="userAddressForm" class="form-horizontal" role="form" novalidate ng-submit="saveUserAddress()">
        <fieldset>

          <!-- Form Name -->
          <legend>Address Details</legend>
          
            <div class="row" ng-show="user_address_loading">
                <div class="shmyde-loading">
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                    <div class="loading-bar"></div>
                </div>
            </div>
            <div class="row" ng-show="!user_address_loading" style="text-align: center; font-style: italic;">
                <p>{{user_address_message}}</p>
            </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Line 1</label>
            <div class="col-sm-10">
                <input type="text" placeholder="Address Line 1" ng-model="address_line_1" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Line 2</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Address Line 2" ng-model="address_line_2" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">City</label>
            <div class="col-sm-10">
                <input type="text" placeholder="City" ng-model="city" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">State</label>
            <div class="col-sm-4">
                <input type="text" placeholder="State" ng-model="state" class="form-control">
            </div>

            <label class="col-sm-2 control-label" for="textinput">Postcode</label>
            <div class="col-sm-4">
                <input type="text" placeholder="Post Code" ng-model="postcode" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Country</label>
            <div class="col-sm-10">
                <select  class="form-control"  ng-model="country" name="country" pvp-country-picker>

                </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <button class="btn btn-default" ng-click="cancelUserAddress()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<!-- User Orders -->
<div class="row" id="orders">
    <div class="col-md-12">
        <form class="form-horizontal" role="form">
            <fieldset>
                <!-- Form Name -->
                <legend>Orders</legend>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product Design</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="design in user_orders">
                            <td>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <img width="180" height="300" ng-src="<?php echo ASSETS_PATH?>images/orders/order_{{design.id}}_{{design.user_id}}_front.png"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <img width="180" height="300"  ng-src="<?php echo ASSETS_PATH?>images/orders/order_{{design.id}}_{{design.user_id}}_back.png"/>
                                    </div>
                                </div>
                            </td>
                            <td>{{design.type}}</td>
                            <td>{{design.price}} FCFA</td>
                            <td>{{get_design_status(design.status)}}</td>
                            <td>
                                <span ng-show="can_complete(design.status)"><a href="<?php echo site_url('user/complete_checkout/{{design.id}}') ?>">Complete</a> | </span>
                                <span ng-show="can_edit(design.status)"><a  href="<?php echo site_url('design/edit/{{design.id}}') ?>">Edit</a> | </span>
                                <span ng-show="can_edit(design.status)" ng-click="showDeleteDesignConfirmationBox(design.id)"><a href="#orders">Delete</a></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
            </fieldset>
        </form>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->


</div>



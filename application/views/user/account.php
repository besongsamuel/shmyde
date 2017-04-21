
<div style="background-color: white" ng-controller="AccountController" id="account-container">
    
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
            userScope.dob = userScope.userObject.user.dob;
            userScope.address_line_1 = userScope.userObject.user.address_line_1;
            userScope.address_line_2 = userScope.userObject.user.address_line_2;
            userScope.country = userScope.userObject.user.country;
            userScope.city = userScope.userObject.user.city;
            userScope.loading = false;
        });
    });
</script>

<div class="container">
    <div class="shmyde-profile">
        <img align="left" class="shmyde-image-lg" ng-src="<?php echo ASSETS_PATH; ?>images/account/background.png" alt="Profile image"/>
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
      <form class="form-horizontal" role="form" novalidate ng-submit="saveUserDetails()">
        <fieldset>

          <!-- Form Name -->
          <legend>User Details</legend>
            
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

          <!-- Old Password-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Enter Old Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="Old Password"  ng-model="old-password" name="old-password" id='old-password' class="form-control">
            </div>
          </div>
          
          <!-- New Password -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">New Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="New Password" ng-model="new-password" name="new-password" id='new-password' class="form-control">
            </div>
          </div> 
            
          <!-- Confirm New Password -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Confirm New Password</label>
            <div class="col-sm-10">
              <input type="password" placeholder="Confirm New Password" ng-model="confirm-new-password" name="confirm-new-password" id='confirm-new-password' class="form-control">
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
      <form class="form-horizontal" role="form" novalidate ng-submit="changeUserAddress()">
        <fieldset>

          <!-- Form Name -->
          <legend>Address Details</legend>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Line 1</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Address Line 1" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Line 2</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Address Line 2" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">City</label>
            <div class="col-sm-10">
              <input type="text" placeholder="City" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">State</label>
            <div class="col-sm-4">
              <input type="text" placeholder="State" class="form-control">
            </div>

            <label class="col-sm-2 control-label" for="textinput">Postcode</label>
            <div class="col-sm-4">
              <input type="text" placeholder="Post Code" class="form-control">
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
    <div class="col-md-8 col-md-offset-2">
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
                                        <img width="80" height="100" ng-src="<?php echo ASSETS_PATH?>images/orders/order_{{design.id}}_{{design.user_id}}_front.png"/>
                                    </div>
                                    <div class="col-sm-4">
                                        <img width="80" height="100"  ng-src="<?php echo ASSETS_PATH?>images/orders/order_{{design.id}}_{{design.user_id}}_back.png"/>
                                    </div>
                                </div>
                            </td>
                            <td>{{design.type}}</td>
                            <td>{{design.price}} FCFA</td>
                            <td>{{get_design_status(design.status)}}</td>
                            <td>
                                <span ng-show="can_complete(design.status)"><a href="<?php echo site_url('user/complete/{{design.id}}') ?>">Complete</a> | </span>
                                <span ng-show="can_edit(design.status)"><a  href="<?php echo site_url('design/edit/{{design.id}}') ?>">Edit</a> | </span>
                                <span ng-show="can_edit(design.status)" ng-click="delete_design(design.id)"><a href="#orders">Delete</a></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            
            </fieldset>
        </form>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->


</div>



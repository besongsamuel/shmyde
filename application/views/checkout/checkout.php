
    <script src="<?php echo ASSETS_PATH; ?>/js/designer.js" type="text/javascript"></script>       
    <link href="<?php echo ASSETS_PATH; ?>/css/product-checkout.css" rel="stylesheet" type="text/css"/>
    <script>
            
        $(document).ready(function()
        {
            var checkoutScope = angular.element("#checkout-container").scope();
            
            checkoutScope.$apply(function()
            {                              
                checkoutScope.product = JSON.parse('<?php echo $productManager; ?>');        
                checkoutScope.productManager = new Product(checkoutScope.product);
                checkoutScope.productManager.base_url = '<?= site_url("/"); ?>';
                checkoutScope.productManager.setProductDetails();  
                checkoutScope.designImage = localStorage.getItem("designImage");
            });
                                     
        });

        $('#agreeButton, #disagreeButton').on('click', function() 
        {
            var whichButton = $(this).attr('id');

            $('#registrationForm')
                .find('[name="agree"]')
                    .val(whichButton === 'agreeButton' ? 'yes' : 'no')
                    .end();
        });
        
        
    </script>    
    <div id="checkout-container" class="container" ng-controller="CheckoutController">
        
        <!-- Product Category -->
        <div class="row">
            <div class="col-sm-12 container  checkout-container">
                <div class="section-header">
                    <h3 class="text-center">Product Type</h3>
                </div>
                <div class="section-body">
                    <div class="form-group">
                    <label for="design-types">Design Type:</label>
                    <select class="form-control" id="design-types">
                        <option ng-repeat="type in designTypes">{{type}}</option>
                    </select>
                  </div>                  
                </div>
                
            </div>          
        </div>
        
        <!-- Order Details -->
        <div class="row">           
            <div class="col-sm-12 container checkout-container">
                <div class="section-header">
                    <h3 class="text-center">Order Details</h3>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th ng-repeat="tableHeader in orderDetailsTableHeader">{{tableHeader}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                          <div id='design-preview' style="width: 230px; height: 300px">
                              <img id="design-image" ng-src="{{designImage}}" />
                          </div>
                      </td>
                      <td>
                          <p ng-bind-html="product_name"></p>
                          <p ng-repeat="detail in productManager.product_details">{{detail}}</p>
                          <a href="#"  data-toggle="modal" data-target="#myMeasurementModal">Measurements</a>
                      </td>
                      <td><input type="number" ng-model="quantity" style="width: 50px;" /></td>
                      <td>{{price * quantity }} FCFA</td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        
        <!-- User Details -->
        <div class="row">
            <div class="col-sm-12 container checkout-container">
                <div class="section-header">
                    <h3 class="text-center">User Details</h3>
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input  type="email" class="form-control" placeholder="Email"ng-model="userObject.user.email">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input  type="text" class="form-control" placeholder="Lastname"ng-model="userObject.user.last_name">
                    <input  type="text" class="form-control" placeholder="Lastname"ng-model="userObject.user.first_name">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                    <input  type="text" class="form-control" placeholder="Mobile Phone Number"ng-model="userObject.user.phone_number">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input  type="text" class="form-control" placeholder="Address Line 1"ng-model="userObject.user.address_line_1">
                    <input  type="text" class="form-control" placeholder="Address Line 2"ng-model="userObject.user.address_line_2">
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
                    <select  class="form-control"  ng-model="userObject.user.country" pvp-country-picker>

                    </select>
                </div>
                                
            </div>
        </div>
        
        <!-- Checkout Section -->
        <div>
            
            <div class="form-group  checkout-button">
                <div class="col-xs-9 col-xs-offset-3">
                    <button type="button" class="btn btn-default" ng-click="checkout()" ng-disabled="agree_to_terms === false">Checkout</button>
                </div>
            </div>
            
            <div class="form-group  checkout-button">
                <div class="col-xs-6 col-xs-offset-3">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#termsModal">Agree with the terms and conditions</button>
                    <input type="hidden" name="agree" value="no" />
                </div>
            </div>
        </div>
        
        <!-- Terms and conditions modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="Terms and conditions" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Terms and conditions</h3>
                    </div>

                    <div class="modal-body">
                        <p>Lorem ipsum dolor sit amet, veniam numquam has te. No suas nonumes recusabo mea, est ut graeci definitiones. His ne melius vituperata scriptorem, cum paulo copiosae conclusionemque at. Facer inermis ius in, ad brute nominati referrentur vis. Dicat erant sit ex. Phaedrum imperdiet scribentur vix no, ad latine similique forensibus vel.</p>
                        <p>Dolore populo vivendum vis eu, mei quaestio liberavisse ex. Electram necessitatibus ut vel, quo at probatus oportere, molestie conclusionemque pri cu. Brute augue tincidunt vim id, ne munere fierent rationibus mei. Ut pro volutpat praesent qualisque, an iisque scripta intellegebat eam.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="agreeButton" data-dismiss="modal" ng-click="agree_to_terms = true">Agree</button>
                        <button type="button" class="btn btn-default" id="disagreeButton" data-dismiss="modal" ng-click="agree_to_terms = false">Disagree</button>
                    </div>
                </div>
            </div>
        </div>
         
    </div>
    
        <!-- Measurements Modal -->
    <div id="myMeasurementModal" class="modal fade" role="dialog" ng-controller="CheckoutController">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Your Measurements</h4>
          </div>
          <div class="modal-body">
                <table class="table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Value</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="measurement in productManager.product.measurements">
                        <td>{{measurement.name}}</td>
                        <td>{{measurement.description}}</td>
                        <td style="width: 90px;"><input type="number" ng-model="measurement.default_value" style="width: 50px; text-align: center;" /> cm</td>
                      </tr>
                    </tbody>
                  </table>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


    
        

    




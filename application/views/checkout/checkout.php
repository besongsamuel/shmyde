<html lang="en">
    <head>
        <script src="<?php echo ASSETS_PATH; ?>/js/designer.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_PATH; ?>/js/angular.min.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_PATH; ?>/js/country-picker.js" type="text/javascript"></script>
        <link href="<?php echo ASSETS_PATH; ?>/css/product-checkout.css" rel="stylesheet" type="text/css"/>
        <script>
            var productManager;
            var userManager;
            var product = JSON.parse('<?php echo $productManager; ?>');
            var user = JSON.parse('<?php echo $user;  ?>');
            productManager = new Product(product);
            productManager.setProductDetails();
            userManager = new User(user);
            userManager.base_url = '<?= site_url("/"); ?>';
        </script>
        <script src="<?php echo ASSETS_PATH; ?>/js/checkout.js" type="text/javascript"></script>
        <script src="<?php echo ASSETS_PATH; ?>/js/html2canvas.js" type="text/javascript"></script>
        <script>
        
        
        
        $(document).ready(function(){
            
            
            productManager.draw("design-preview", 'front');  
            
            setPorductToScope();
            
        });
        
        function setPorductToScope() {
            
            var appElement = document.querySelector('[ng-app=checkout]');
            var $scope = angular.element(appElement).scope();
            $scope.$apply(function() {
                $scope.product = productManager;
            });
        }
        
        $('#agreeButton, #disagreeButton').on('click', function() {
            var whichButton = $(this).attr('id');

            $('#registrationForm')
                .find('[name="agree"]')
                    .val(whichButton === 'agreeButton' ? 'yes' : 'no')
                    .end();
        });
        
        </script>
    </head>
    
    <div id="checkout-container" class="container" ng-app="checkout" ng-controller="CheckoutController as checkout">
        
        <!-- Product Category -->
        <div class="row" id="table">
            <div class="col-sm-12 section">
                <div class="section-header">
                    <h2 class="section-title">Design Type</h2>
                </div>
                <div class="section-body">
                    <div class="form-group">
                    <label for="design-types">Design Type:</label>
                    <select class="form-control" id="design-types">
                        <option ng-repeat="type in checkout.designTypes">{{type}}</option>
                    </select>
                  </div>                  
                </div>
                
            </div>          
        </div>
        
        <!-- Order Details -->
        <div class="row">
            
            <div class="col-sm-12 section">
                <div class="section-header">
                    <h2 class="section-title">Order Details</h2>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th ng-repeat="tableHeader in checkout.orderDetailsTableHeader">{{tableHeader}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                          <div id='design-preview' style="width: 230px; height: 300px">

                          </div>
                      </td>
                      <td>
                          <p ng-bind-html="checkout.product_name"></p>
                          <p ng-repeat="detail in checkout.productManager.product_details">{{detail}}</p>
                      </td>
                      <td><input type="number" ng-model="checkout.quantity" style="width: 50px;" /></td>
                      <td>{{checkout.price * checkout.quantity }} FCFA</td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        
        <!-- User Details -->
        <div class="row">
            <div class="col-sm-12 section">
                <div class="section-header">
                    <h2 class="section-title">User Details</h2>
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input  type="email" class="form-control" placeholder="Email"ng-model="checkout.user.email">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input  type="text" class="form-control" placeholder="Lastname"ng-model="checkout.user.last_name">
                    <input  type="text" class="form-control" placeholder="Lastname"ng-model="checkout.user.first_name">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                    <input  type="text" class="form-control" placeholder="Mobile Phone Number"ng-model="checkout.user.phone_number">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input  type="text" class="form-control" placeholder="Address Line 1"ng-model="checkout.user.address_line_1">
                    <input  type="text" class="form-control" placeholder="Address Line 2"ng-model="checkout.user.address_line_2">
                </div>
                
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-flag"></i></span>
                    <select  class="form-control"  ng-model="checkout.user.country" pvp-country-picker>

                    </select>
              </div>
                                
            </div>
        </div>
        
        <!-- Checkout Section -->
        <div>
            
            <div class="form-group  checkout-button">
                <div class="col-xs-9 col-xs-offset-3">
                    <button type="button" class="btn btn-default" ng-disabled="checkout.agree_to_terms === false">Checkout</button>
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
                        <button type="button" class="btn btn-primary" id="agreeButton" data-dismiss="modal" ng-click="checkout.agree_to_terms = true">Agree</button>
                        <button type="button" class="btn btn-default" id="disagreeButton" data-dismiss="modal" ng-click="checkout.agree_to_terms = false">Disagree</button>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
    </div>
    
</html>




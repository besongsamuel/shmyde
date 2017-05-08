

    <script src="<?php echo ASSETS_PATH; ?>/js/designer.js" type="text/javascript"></script>

    <script>
        
        // Show loading animation
        $('#loading').show();
        
        var designObject;
        var productManager;
        var userManager;
                
        $(document).ready(function()
        { 
            
            var applicationScope = angular.element("#shmyde-application").scope();
            var designScope = angular.element("#design-page").scope();

            var productManager;
            applicationScope.$apply(function()
            {                              
                applicationScope.product = JSON.parse('<?php echo $product; ?>');  
                applicationScope.order_id = parseInt(JSON.parse('<?php echo $order_id; ?>'));
                applicationScope.order_status = parseInt(JSON.parse('<?php echo $order_status; ?>'));
                if(applicationScope.order_id === -1)
                {
                    if(sessionStorage.getItem("order_id")!== null && sessionStorage.getItem("order_id")!== 'undefined')
                    {
                        applicationScope.order_id = parseInt(sessionStorage.getItem("order_id"));
                    }
                }
                if(applicationScope.order_status === -1)
                {
                    if(sessionStorage.getItem("order_status")!== null && sessionStorage.getItem("order_status")!== 'undefined')
                    {
                        applicationScope.order_status = parseInt(sessionStorage.getItem("order_status"));
                    }
                }
                applicationScope.productManager = new Product(applicationScope.product);
                applicationScope.productManager.InitOptionsContainer($('#design_options'));
                applicationScope.productManager.InitThreadsContainer($('#button-design-threads'));
                applicationScope.productManager.LoadThreadsToSly();
                applicationScope.productManager.setContainers("design-preview", "design-preview-back");
                applicationScope.productManager.setImageCount();
                applicationScope.productManager.draw(false);
                
                productManager = applicationScope.productManager;
            });
            
            designScope.$apply(function()
            {
                designScope.categories = JSON.parse('<?php echo $ordered_categories; ?>');
                
            });
            
            var measurementDesignScope = angular.element("#myMeasurementModal").scope();
            
            measurementDesignScope.$apply(function()
            {
                measurementDesignScope.measurements = applicationScope.productManager.product.measurements;
            });
            
            
            var user = JSON.parse('<?php echo $user;  ?>');
            userManager = new User(user);
            userManager.base_url = '<?= site_url("/"); ?>';
            
            $('#buttonsModal').on('shown.bs.modal', function() 
            {      
                productManager.LoadThreadsToSly();
            });
            
            $('#myMeasurementModal').on('shown.bs.modal', function() 
            {
                productManager.product.request_tailor = false;
            });
        
            $('#userDataModal').on('shown.bs.modal', function() 
            {
                productManager.product.request_tailor = true;
            });
                        
        });
        
        $("apply-thread").click(function()
        {            
            ApplyThread();
        });
        
        function ApplyThread()
        {
            productManager.applySelectedThread();
        }
               
        function updateUserData()
        {
            userManager.updateUser();
        }
        
    </script>

    <!-- Design Page -->
    <div class='design-page' id="design-page"  ng-controller="DesignController">
        <div id='' class='container'>

            <div id='' class="row">

                <!-- MAIN MENUS  -->
                <div id='' class='col-sm-3'>
                    <div id='main_menu_list' class="list-group">
                        <a ng-repeat="category in categories" ng-click="DesignCategorySelected(category.id)"  href="#" class="list-group-item">{{category.name}}</a>
                    </div>
                </div>

                <!-- SUB MENUS  -->
                <div id='sub_menu_list' class="col-sm-3">
                </div>

                <div id='design-preview' class='design-preview col-sm-3' style="width: 230px; height: 300px;">
                </div>
                
                <div id='design-preview-back' class='design-preview col-sm-3' style="width: 230px; height: 300px;">
                </div>

            </div>
            
            <div class='row'>
                    <button class="btn pull-right" ng-click="checkout()">Checkout</button>
            </div>
            <!-- END MAIN MENUS  -->
            <div class="row">
                <div class="wrap">  
                    <div class="scrollbar">
                        <div class="handle">
                                <div class="mousearea"></div>
                        </div>
                    </div>

                    <div class="frame" id="design_options">
                        <ul class="clearfix" id="option-list">

                        </ul>
                    </div>
                </div>
            </div>
            
            
            
        </div>
    </div>

    <!-- Measurement Modal -->
    <div id="myMeasurementModal" class="modal fade" role="dialog"  ng-controller="DesignController">
        <user-measurements measurements='measurements'></user-measurements>
    </div>

    <div id="userDataModal" class="modal fade" role="dialog">
        <user-details user='userObject.user'></user-details>
    </div>
    
    <div id="buttonsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="selected-button-name">Button Selected Name Here</h4>
                </div>

                <div class="modal-body">

                    <div id="button_selected">
                        <img id="design_button_image" class="button-design-image"/>
                    </div>

                    <div class="wrap">  

                        <div class="scrollbar">
                            <div class="handle">
                                <div class="mousearea">

                                </div>
                            </div>
                        </div>

                        <div class="frame" id="button-design-threads">
                            <ul class="clearfix" id="button-design-threads-list">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="apply-thread" class="btn btn-default" data-dismiss="modal">Apply</button>
                </div>

            </div>
        </div>
    </div>

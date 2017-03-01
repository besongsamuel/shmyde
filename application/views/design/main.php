

    <script src="<?php echo ASSETS_PATH; ?>/js/designer.js" type="text/javascript"></script>

    <script>
        
        var designObject;
        var productManager;
        var userManager;
                
        $(document).ready(function()
        {   
            var user = JSON.parse('<?php echo $user;  ?>');
            userManager = new User(user);
            userManager.base_url = '<?= site_url("/"); ?>';
            
            var my_product = JSON.parse('<?php echo $product;  ?>');
            productManager = new Product(my_product);
            productManager.InitOptionsContainer($('#design_options'));
            productManager.InitThreadsContainer($('#button-design-threads'));
            productManager.LoadThreadsToSly();
            productManager.draw("design-preview", "front");
            
            $('#buttonsModal').on('shown.bs.modal', function() 
            {            
                productManager.LoadThreadsToSly();
            });
            
            $('#myMeasurementModal').on('shown.bs.modal', function() {
            
                productManager.LoadMeasurementsIntoModal();
            });
        
            $('#userDataModal').on('shown.bs.modal', function() {

                LoadUserDataIntoModal();
            });
                        
        });
        
        function OnDesignCategorySelected(category_selected)
        {
            $('#option-list').empty();
            
            productManager.category_selected = category_selected;
            
            if(parseInt(category_selected) === 1 || parseInt(category_selected) === 2)
            {
                productManager.loadMenus("sub_menu_list");
            }
            
            if(parseInt(category_selected) === 3)
            {
                productManager.loadMixMenus("sub_menu_list");
            }
            
            if(parseInt(category_selected) === 4)
            {
                productManager.loadMeasurementMenus();
            }
            
            if(parseInt(category_selected) === 5)
            {
                productManager.LoadButtonOptions();
            }
                        
            if(parseInt(category_selected) === 6)
            {
                productManager.invertFabric();
            }            
        }
        
        function ApplyThread()
        {
            productManager.applySelectedThread();
        }
        
        function LoadUserDataIntoModal()
        {
            userManager.setUserToModal();   
        }
        
        function updateUserData()
        {
            userManager.updateUser();
        }
        
        function CheckOut()
        {
            userManager.CheckOut(productManager);
        }
        
        
    </script>

    <!-- Design Page -->
    <div class='design-page'>
        <div id='' class='container'>

            <div id='' class="row">

                <!-- MAIN MENUS  -->

                <div id='' class=' design-menu col-sm-2'>
                    <div id='main_menu' class='design-menu-header'>Design Menu</div>
                    <div id='main_menu_list' class="list-group">
                        <?php foreach ($categories->result() as $category) {?>
                        <?php if($category->id != 4){ ?>
                        <a  value="<?php echo $category->id; ?>" onclick="OnDesignCategorySelected(<?php echo $category->id; ?>)" href="#" class="list-group-item"><?php echo $category->name; ?></a>
                        <?php } else { ?>
                        <a value="<?php echo $category->id; ?>" onclick="OnDesignCategorySelected(<?php echo $category->id; ?>)" href="#" class="list-group-item"><?php echo $category->name; ?></a>                    
                        <?php } ?>
                        <?php }?>				
                    </div>
                </div>

                <!-- SUB MENUS  -->
                <div id='sub_menu_list' class="col-sm-3">

                </div>

                <div id='design-preview' class='col-sm-4 design-preview' style="width: 230px; height: 300px;">

                </div>

                <div id='design-preview-sim' class='col-sm-4 design-preview'>

                </div>

                <div class='col-sm-4' style="float:right;">
                    <button class="btn pull-right" onclick="CheckOut()">Checkout</button>
                </div>

            </div>

            <!-- END MAIN MENUS  -->

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

    <!-- Modal -->
    <div id="myMeasurementModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Measurements</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                  <!-- Contains a Scrollable list of all measurements  -->
                  <div class="col-sm-4" style="height: 400px; overflow-y: auto;">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Set Measurement</th>
                          </tr>
                        </thead>
                        <tbody id="my_measurements">

                        </tbody>
                      </table>    
                  </div>
                  <div class="col-sm-8">
                      <iframe id="youtube_frame" src="" value="-1"  style="width: 100%; height: 300px; padding: 2px; background-color: gray;">

                      </iframe>
                      <div>
                          <p id="measurement_description">

                          </p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <div id="userDataModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirm User Details</h4>
          </div>
          <div class="modal-body">
              <div style="width: 100%">
                  <div class="form-group">
                      <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name">
                  </div>
                  <div class="form-group">
                    <label for="contact_phone">Phone Number:</label>
                    <input type="tel" class="form-control" id="contact_phone">
                  </div>
                  <div class="form-group">
                    <label for="user_email">Email:</label>
                    <input type="user_email" class="form-control" id="user_email">
                  </div>
                  <div class="form-group">
                    <label for="address_line_01">Address:</label>
                    <input type="text" class="form-control" id="address_line_01">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="address_line_02">
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="City">
                  </div>
                  <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" name="country" placeholder="Country">
                  </div>
                  <div class="form-group">
                    <label for="postal_code">Postal Code:</label>
                    <input type="text" class="form-control" id="postal_code">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" onclick="updateUserData()">Update</button>
          </div>
        </div>

      </div>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="ApplyThread()">Apply</button>
                </div>

            </div>
        </div>
    </div>

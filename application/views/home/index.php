<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
          <img src="<?php echo ASSETS_PATH ?>/images/home/1.jpg" alt="New York">
        <div class="carousel-caption">
          <h3>Your Design at Your Door</h3>
          <p>We deliver your design right where you are.</p>
        </div>      
      </div>

      <div class="item">
        <img src="<?php echo ASSETS_PATH ?>/images/home/2.jpg" alt="Chicago">
        <div class="carousel-caption">
          <h3>Think it, Wear it</h3>
          <p>Think it, Design it, We deliver it.</p>
        </div>      
      </div>
    
      <div class="item">
        <img src="<?php echo ASSETS_PATH ?>/images/home/3.jpg" alt="LA">
        <div class="carousel-caption">
          <h3>Choose your tailor</h3>
          <p>Your design! Your product! Your Tailor!</p>
        </div>      
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<!-- Container (About Us Section) -->
<div id="about-us" class="container text-center">
  <h3>SHMYDE</h3>
  <p><em>Wear what you design | Design what you wear</em></p>
  <p>We have created a fictional band website. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
  <br>
  <div class="row">
    <div class="col-sm-6">
      <p class="text-center"><strong>Jarvis, CEO</strong></p><br>
      <a href="#demo" data-toggle="collapse">
        <img src="<?php echo ASSETS_PATH ?>/images/home/jarvis.jpg" class="img-circle person" alt="Random Name" width="255" height="255">
      </a>
      <div id="demo" class="collapse">
        <p>Guitarist and Lead Vocalist</p>
        <p>Loves long walks on the beach</p>
        <p>Member since 1988</p>
      </div>
    </div>

    <div class="col-sm-6">
      <p class="text-center"><strong>Sam, CIO</strong></p><br>
      <a href="#demo3" data-toggle="collapse">
        <img src="<?php echo ASSETS_PATH ?>/images/home/sam.jpg" class="img-circle person" alt="Random Name" width="255" height="255">
      </a>
      <div id="demo3" class="collapse">
        <p>Bass player</p>
        <p>Loves math</p>
        <p>Member since 2005</p>
      </div>
    </div>
  </div>
</div>

<!-- Container (Design Section) -->
<div id="design-section" class="bg-1" ng-controller="DesignSectionController">
  <div class="container bg-1">
    <h3 class="text-center">Design</h3>
    <p class="text-center">For a quick design<br> Select one of our popular templates!</p>
    
    <div class="row text-center">
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="<?php echo ASSETS_PATH ?>/images/home/african_model_02.jpg" alt="Yellow Line" style="width : 300px; height : 400px;">
          <p><strong>Female Designs</strong></p>
          <p>From FCFA 10000</p>
          <button class="btn" data-toggle="modal" data-target="#myModal" ng-click="get_products(1)">Begin</button>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="<?php echo ASSETS_PATH ?>/images/home/african_model_04.jpg" alt="Black Rose" style="width : 300px; height : 400px;">
          <p><strong>Unisex Designs</strong></p>
          <p>From FCFA 10000</p>
          <button class="btn" data-toggle="modal" data-target="#myModal" ng-click="get_products(2)">Begin</button>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="<?php echo ASSETS_PATH ?>/images/home/african_model_05.jpg" alt="Dark Night" style="width : 300px; height : 400px;">
          <p><strong>Male Designs</strong></p>
          <p>From FCFA 10000</p>
          <button class="btn" data-toggle="modal" data-target="#myModal" ng-click="get_products(0)">Begin</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4>Select Product</h4>
        </div>
        <div class="modal-body">
            <form action="<?php echo site_url('design/product/'); ?>" role="form" method="get">
            <div class="form-group">
              <label for="product">Product</label>
              <select class="form-control" id="product" name="product_id" ng-options="product.name for product in selected_products track by product.id" ng-model="selected_product">
                  <option value="">-- choose product --</option>
              </select>
            </div>
            <button type="submit" class="btn btn-block">Design 
              <span class="glyphicon glyphicon-ok"></span>
            </button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        
        var designSectionScope = angular.element("#design-section").scope();
        
        designSectionScope.products = JSON.parse('<?php echo $products;  ?>');
        
    });
</script>
        
        



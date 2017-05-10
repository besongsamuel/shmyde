<!DOCTYPE html>
<html lang="en"ng-app="shmyde" id="shmyde-application">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>
    
    <!-- Bootstrap -->
    <link href="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/main.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/header.css" rel="stylesheet"> 
    <link href="<?php echo ASSETS_PATH; ?>/css/uploader.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>color-picker/css/bootstrap-colorpicker.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Calligraffitti" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="<?php echo ASSETS_PATH; ?>css/intlTelInput.css" rel="stylesheet">
    
    <!-- additional CSS files -->   
    <?php
    //Just to permit dynamic load of other css files to this header
    //$cssLinks is a array of css file names to load parsed by the controller to this view
    if(isset($cssLinks))
    {
    	foreach($cssLinks as $link)
	{
            echo "<link href='".ASSETS_PATH."css/{$link}.css' rel='stylesheet' type='text/css'>";
	}
    }
    ?>   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo ASSETS_PATH; ?>frameworks/jquery/jquery-1.11.3.min.js"></script>
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
    <script src="<?php echo ASSETS_PATH; ?>js/jquery.validate.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>/js/intlTelInput.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_PATH; ?>/js/utils.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/bootstrap.min.js"></script> 
    <script src="<?php echo ASSETS_PATH; ?>/js/angular.min.js" type="text/javascript"></script> 
    <script src="<?php echo ASSETS_PATH; ?>/js/ui-bootstrap-tpls-2.5.0.min.js" type="text/javascript"></script> 
    <script src="<?php echo ASSETS_PATH; ?>/js/country-picker.js" type="text/javascript"></script> 
    <script src="<?php echo ASSETS_PATH; ?>/js/angular-messages.js" type="text/javascript"></script>  
    <script src="<?php echo ASSETS_PATH; ?>/js/international-phone-number.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/modernizr.js"></script>    
    <script src="<?php echo ASSETS_PATH; ?>sly-master/dist/sly.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>color-picker/js/bootstrap-colorpicker.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/dom-to-image.min.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/user-object.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/shmyde-angular.js" type="text/javascript"></script>

    <script>

        $(document).ready(function()
        {
            // Always hide loading by default
            $('#loading').hide();
            
            var contactusScope = angular.element("#contact").scope();
		
            contactusScope.$apply(function()
            {
                contactusScope.shmyde_contact_email = '<?php echo SHMYDE_CONTACT; ?>';
                contactusScope.shmyde_address_line_2 = '<?php echo SHMYDE_ADDRESS_LINE_1; ?>';
                contactusScope.shmyde_address_line_1 = '<?php echo SHMYDE_ADDRESS_LINE_2; ?>';
                contactusScope.shmyde_country = '<?php echo SHMYDE_COUNTRY; ?>';
                contactusScope.shmyde_contact_phone = '<?php echo SHMYDE_CONTACT_PHONE; ?>';
            });

            var rootScope = angular.element("#shmyde-application").scope();
            
           
                       
            /* Apply these values to the root scope */
            rootScope.$apply(function()
            {
                rootScope.is_initialized = false;
                
                rootScope.user = JSON.parse('<?php echo $user;  ?>');

                rootScope.userObject = new User(rootScope.user);
                
                rootScope.userObject.base_url = '<?= site_url("/"); ?>';

                rootScope.controller = '<?= $ci_class; ?>';

                rootScope.method = '<?= $ci_method; ?>';

                rootScope.home_url = '<?= $home_url; ?>';

                rootScope.site_url = '<?= site_url("/"); ?>';
                
                rootScope.user_email = rootScope.user.email;
                
                rootScope.is_initialized = true;

            });
            
            // Initialize Tooltip
            $('[data-toggle="tooltip"]').tooltip(); 
  
            // Add smooth scrolling to all links in navbar + footer link
            $(".navbar a, footer a[href='#myPage']").on('click', function(event) 
            {   
                // Make sure this.hash has a value before overriding default behavior
                if (this.hash !== "") 
                {
                    // Prevent default anchor click behavior
                    event.preventDefault();

                    // Store hash
                    var hash = this.hash;

                    // Using jQuery's animate() method to add smooth page scroll
                    // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 900, function(){
                      // Add hash (#) to URL when done scrolling (default click behavior)
                      window.location.hash = hash;
                    });
                  } // End if
            });
        });
        
    </script>

  </head>
	
  <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">
      
    <!-- Preloading Section -->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                    <div class="object" id="object_one"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_three"></div>
            </div>
        </div>
    </div>
    <!-- Preloading Section End -->
      
    <div class="shmyde-main-container">
        
        <!- Header Section-->
        <nav class="navbar navbar-default navbar-fixed-top" ng-controller="HeaderController">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
          </button>
            <span>                
                <img src="<?php echo ASSETS_PATH ?>/images/logo_shmyde_old.png" alt="Shmyde Corp." class="site-logo" > 
                <a class="navbar-brand site-name" href="#myPage" ng-click="gotoHome()">Shmyde</a>
            </span>
          
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
              <li><a href="#" ng-show="saveDesignVisible()" ng-click="showUpdateDesignConfirmationBox()">SAVE DESIGN</a></li>
            <li><a href="#" ng-show="updateDesignVisible()" ng-click="showUpdateDesignConfirmationBox()">UPDATE DESIGN</a></li>
            <li><a href="#myPage" ng-show="homeMenuVisible()">HOME</a></li>
            <li><a href="#design-section" ng-show="designMenuVisible()">DESIGN</a></li>
            <li><a href="#about-us" ng-show="aboutUsVisible()">ABOUT US</a></li>
            <li><a href="#contact" ng-show="contactUsVisible()">CONTACT</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" ng-show="user_logged()">{{user_email}}
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="#" ng-click="goto_account()">Account Page</a></li>
                <li><a href="#" ng-click="logout()">Logout</a></li>
              </ul>
            </li>
            <li ng-hide="user_logged()" ng-click="login()"><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
          </ul>
        </div>
      </div>
    </nav>  
    
        <?php if($this->router->class == 'admin') :  ?>
                <div class="container" style="margin-top: 10px;">
                    <span>
                        <a href="<?php echo site_url('admin/view/product'); ?>">PRODUCTS</a> |
                        <a href="<?php echo site_url('admin/view/menu'); ?>">MENUS</a> |
                        <a href="<?php echo site_url('admin/view/measurement'); ?>">MEASUREMENTS</a> |
                        <a href="<?php echo site_url('admin/view/product_fabric'); ?>">PRODUCT FABRICS</a> |
                        <a href="<?php echo site_url('admin/view/option'); ?>">OPTIONS</a> |
                        <a href="<?php echo site_url('admin/view/thread'); ?>">THREADS</a> |
                        <a href="<?php echo site_url('admin/view/button'); ?>">BUTTONS</a>
                    </span>
                </div>
            <?php endif; ?>  
        
        <!-- Body Section -->
        <div class="wrapper">

            <?php echo $body; ?>
            
            <div id="messageboxModal" class="modal fade" role="dialog"  ng-controller="DesignController">
                <message-box value='message_box.value' hasvalue='message_box.hasValue' hascancel='message_box.hasCancel' placeholder='message_box.placeholder' valuelabel='message_box.valueLabel' title='message_box.title' message='message_box.message' on-update='message_box.onUpdate(value)'>

                </message-box>
            </div>
            
            <!-- Container (Contact Section) -->
            <div id="contact" class="container" ng-controller="ContactUsController" ng-show="contactUsVisible()">
                <h3 class="text-center">Contact</h3>
                <div class="row" ng-show="contact_us_loading">
                    <div class="shmyde-loading">
                        <div class="loading-bar"></div>
                        <div class="loading-bar"></div>
                        <div class="loading-bar"></div>
                        <div class="loading-bar"></div>
                    </div>
                </div>
                <p ng-class="{'error-text text-center' : error, 'text-center' : !error }"><em>{{Message}}</em></p>
                <div class="row">
                    <div class="col-md-4">
                        <p>Tell us your feeling.</p>
                            <p><span class="glyphicon glyphicon-map-marker"></span>{{shmyde_address_line_1}}</p>
                            <p><span class="glyphicon glyphicon-map-marker"></span>{{shmyde_address_line_2}}</p>
                            <p><span class="glyphicon glyphicon-phone"></span>{{shmyde_contact_phone}}</p>
                            <p><span class="glyphicon glyphicon-envelope"></span>{{shmyde_contact_email}}</p>
                    </div>
                    <form name='contactUsForm' novalidate="" ng-submit="submitComment()">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <input class="form-control" id="contactName" name="name" placeholder="Name" type="text" ng-model="contactName" required>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <input class="form-control" id="contactEmail" name="email" placeholder="Email" type="email" ng-model="contactEmail" required>
                                </div>
                            </div>
                            <textarea class="form-control" id="contactComments" name="comments" placeholder="Comment" ng-model="contactComment" rows="5" required minlength="10">

                            </textarea>
                            <br>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn pull-right">Send</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
            </div>

        </div>
        
        <!-- Footer Section -->
        <footer class="text-center">
            <a class="up-arrow" href="#myPage" data-toggle="tooltip" title="TO TOP">
            <span class="glyphicon glyphicon-chevron-up"></span>
            </a>
            <br>
            <p>Powered by Shmyde Copyright <?= date('Y'); ?> <a href='#'>Shmyde.com</a>. All Rights Reserved</p> 
        </footer> 
    </div>
    
</body>
  

</html>

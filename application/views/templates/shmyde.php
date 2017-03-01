<!DOCTYPE html>
<html lang="en"ng-app="shmyde">

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
    <script src="<?php echo ASSETS_PATH; ?>js/bootstrap.min.js"></script> 
    <script src="<?php echo ASSETS_PATH; ?>/js/angular.min.js" type="text/javascript"></script>     
    <script src="<?php echo ASSETS_PATH; ?>/js/country-picker.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/modernizr.js"></script>    
    <script src="<?php echo ASSETS_PATH; ?>sly-master/dist/sly.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>color-picker/js/bootstrap-colorpicker.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/html2canvas.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/user-object.js"></script>
    <script>
        
        // Initialize global controller variables
        var user = JSON.parse('<?php echo $user;  ?>');
        userObject = new User(user);
        userObject.base_url = '<?= site_url("/"); ?>';
        var controller = '<?= $ci_class; ?>';
        var method = '<?= $ci_method; ?>';
        var home_url = '<?= $home_url; ?>';
        var site_url = '<?= site_url("/"); ?>';
        
        $(document).ready(function()
        {
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
    <script src="<?php echo ASSETS_PATH; ?>js/shmyde-angular.js" type="text/javascript"></script>

  </head>
      
    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

    <nav class="navbar navbar-default navbar-fixed-top" ng-controller="HeaderController as header">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
          </button>
            <span>                
                <img src="<?php echo ASSETS_PATH ?>/images/logo_shmyde_old.png" alt="Shmyde Corp." class="site-logo" > 
                <a class="navbar-brand site-name" href="#myPage" ng-click="header.gotoHome()">Shmyde</a>
            </span>
          
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#myPage" ng-show="header.homeMenuVisible()">HOME</a></li>
            <li><a href="#design-section" ng-show="header.designMenuVisible()">DESIGN</a></li>
            <li><a href="#about-us" ng-show="header.aboutUsVisible()">ABOUT US</a></li>
            <li><a href="#contact" ng-show="header.contactUsVisible()">CONTACT</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" ng-show="header.user_logged()">{{header.user_email}}
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="#">Account Page</a></li>
                <li><a href="#" ng-click="header.logout()">Logout</a></li> 
              </ul>
            </li>
            <li ng-hide="header.user_logged()" ng-click="header.login()"><a href="#"><span class="glyphicon glyphicon-user"></span></a></li>
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
    
    <!-- Login Modal Dialog -->
    <div id="loginModal" class="modal fade" role="dialog" style="margin-top : 30px;">
        <div class="container login-box modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Login</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= form_open('user/login',array('id'=>'login_form','method'=>'post')) ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Your email address">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Your password">
                            </div>
                            <div class="form-group">
                                <label for="remember_me">Remember me</label>
                                <input type="checkbox" class="form-control" id="remember_me" name="remember_me" value="on" style="height: 22px; width: 22px;">
                            </div>

                            <div class="col-md-12">
                                <div id="login_alert_heading" class="alert alert-danger" role="alert" hidden="true">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <input type="submit" class="btn btn-default" value="Login">
                                <p style="margin-top: 5px;">Don't have an account? <a href="#" onclick="switch_to_register()">Register</a></p>
                            </div>
                            <div class="form-group">
                                <a href="#" onclick="forgot_password_clicked();">Forgot your password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>    
    </div>
    
    <!-- Register Modal Dialog -->
    <div id="registerModal" class="modal fade" role="dialog" style="margin-top : 30px;">
        <div class="container register-box modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Register</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= form_open('user/register',array('id'=>'register_form','method'=>'post')) ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                                <p class="help-block">A valid email address</p>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="register_password" name="password" placeholder="Enter a password">
                                <p class="help-block">At least 6 characters</p>
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Confirm password</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password">
                                <p class="help-block">Must match your password</p>
                            </div>

                            <div class="col-md-12">
                                <div id="register_alert_heading" class="alert alert-danger" role="alert"  hidden="true">

                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-default" value="Register">
                                <p style="margin-top: 5px;">Already have an account? <a href="#" onclick="switch_to_login();">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div><!-- .row -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>  
    </div>

    <div class="wrapper">
             
        <?php echo $body; ?>
             
    </div>
    
</body>

<!-- Container (Contact Section) -->
<div id="contact" class="container" ng-controller="HeaderController as header" ng-show="header.aboutUsVisible()">
    <h3 class="text-center">Contact</h3>
    <p class="text-center"><em>We love our fans!</em></p>
    <div class="row">
        <div class="col-md-4">
            <p>Tell us your feeling.</p>
            <p><span class="glyphicon glyphicon-map-marker"></span>Cameroon, YDE</p>
            <p><span class="glyphicon glyphicon-phone"></span>Phone: +237 1515151515</p>
            <p><span class="glyphicon glyphicon-envelope"></span>Email: admin@shmyde.com</p>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
                </div>
                <div class="col-sm-6 form-group">
                    <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
                </div>
            </div>
            <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5">
            
            </textarea>
            <br>
            <div class="row">
                <div class="col-md-12 form-group">
                    <button class="btn pull-right" type="submit">Send</button>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>

<!-- Footer -->
<footer class="text-center">
    <a class="up-arrow" href="#myPage" data-toggle="tooltip" title="TO TOP">
    <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <br>
    <br>
    <p>Powered by Shmyde Copyright <?= date('Y'); ?> <a href='#'>Shmyde.com</a>. All Rights Reserved</p> 
</footer>   

</html>
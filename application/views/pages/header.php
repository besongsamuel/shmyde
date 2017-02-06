<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>
    
    <!-- Bootstrap -->
    <link href="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/header.css" rel="stylesheet"> 
    <link href="<?php echo ASSETS_PATH; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>/css/uploader.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>color-picker/css/bootstrap-colorpicker.css" rel="stylesheet">
    
    <!-- additional css files -->
    
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
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></scr‌​ipt>
    <script src="<?php echo ASSETS_PATH; ?>js/modernizr.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/jquery.validate.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>sly-master/dist/sly.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>color-picker/js/bootstrap-colorpicker.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/html2canvas.js"></script>
    <script>
        
        var login_callbacks = $.Callbacks();
        
        var user_logged = false;
        var user_confirmed = false;
        
        function logout()
        {
            var logout_confirmed = confirm("Confirm Logout?");
            
            if (logout_confirmed === true) 
            {
                var posting = $.post( "<?php echo site_url('user/logout'); ?>" );
                
                posting.done(function( ) 
                {
                    setup_links();
                    window.location.href = "<?php echo site_url('pages/view/home'); ?>";
                });
                
                
            } 
        }
        
        function set_header_message(message)
        {
            $("#header_message").text(message);
        }
        
        function setup_validation()
        {
            // validate signup form on keyup and submit
            $("#login_form").validate({
                rules: {
                    
                    email: {
                        required: true,
                        email : true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    
                    email: {
                        required: "Please enter an email for a username",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    }
                },
                submitHandler: function(form) 
                {                   
                    login(form);
                }
            });
            
            $("#forgot_password_form").validate({
                rules: {
                    
                    email: {
                        required: true,
                        email : true
                    }
                },
                messages: {
                    
                    email: {
                        required: "Please enter an email for a username",
                        email: "Please enter a valid email address"
                    }
                },
                submitHandler: function(form) 
                {                   
                    forgot_password(form);
                }
            });
            
            $("#register_form").validate({
                rules: {
                    
                    email: {
                        required: true,
                        email : true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirm: {
                        required: true,
                        minlength: 6,
                        equalTo: "#register_password"
                    }
                },
                messages: {
                    email: {
                        required: "Please enter an email for a username",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    password_confirm: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long",
                        equalTo: "Please enter the same password as above"
                    }
                },
                submitHandler: function(form) 
                {                   
                    register(form);
                }
            });
            
            $("#choose_password").validate({
                rules: {
                    
                    new_password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_new_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    
                    new_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    confirm_new_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long",
                        equalTo: "Please enter the same password as above"
                    }
                },
                submitHandler: function(form) 
                {                   
                    reset_password(form);
                }
            });
        }
        
        function reset_password(form)
        {
            var $form = $( form );

            var  url = $form.attr( "action" );

            // Send the data using post
            var posting = $.post( url, $form.serialize());
            
            // Put the results in a div
            posting.done(function( data ) 
            {
                var result = JSON.parse(data);

                if(Boolean(result['success']))
                {
                    
                }

            });
        }

        function login(form)
        {
            
            var $form = $( form );

            var  url = $form.attr( "action" );

            // Send the data using post
            var posting = $.post( url, $form.serialize());
            
            // Put the results in a div
            posting.done(function( data ) 
            {
                var result = JSON.parse(data);

                if(Boolean(result['success']))
                {
                    //deactivate modal dialog popups
                    clear_links();
                    $('#login_alert_heading').attr("hidden", true);
                    $('#user_link').html(result['email']);
                    $('#logout_register_link').html('Logout');
                    $('#logout_register_link').attr("onclick", "logout()");
                    $('#loginModal').modal('toggle');
                    user_logged = Boolean(result['logged_in']);
                    user_confirmed = Boolean(result['is_confirmed']);
                    
                    if(!user_confirmed)
                    {
                        set_header_message("Please activate your account to place orders. ");
                    }
                        
                    
                    login_callbacks.fire();
                }
                else
                {
                    $('#login_alert_heading').attr("hidden", false);
                    $('#login_alert_heading').html(result['error_heading']);
                }


            });

        }
        
        function register(form)
        {

            var $form = $( form );

            var  url = $form.attr( "action" );

            // Send the data using post
            var posting = $.post( url, $form.serialize() );

            // Put the results in a div
            posting.done(function( data ) 
            {
                var result = JSON.parse(data);

                if(Boolean(result['success']))
                {
                    //deactivate modal dialog popups
                    clear_links();
                    $('#login_alert_heading').attr("hidden", true);
                    $('#user_link').html(result['email']);
                    $('#logout_register_link').html('Logout');
                    $('#logout_register_link').attr("onclick", "logout()");
                    $('#registerModal').modal('toggle');
                    
                    user_logged = Boolean(result['logged_in']);
                    user_confirmed = Boolean(result['is_confirmed']);
                    
                    set_header_message("Please activate your account to place orders. ");
                }
                else
                {
                    $('#register_alert_heading').attr("hidden", false);
                    $('#register_alert_heading').html(result['error_heading']);
                }
            });

        }
        
        function forgot_password(form)
        {

            var $form = $( form );

            var  url = $form.attr( "action" );

            // Send the data using post
            var posting = $.post( url, $form.serialize() );

            // Put the results in a div
            posting.done(function( data ) 
            {
                var result = JSON.parse(data);

                if(Boolean(result['success']))
                {
                    //deactivate modal dialog popups
                    clear_links(); 
                    $('#reset_password_alert_heading').attr("hidden", true);
                    $('#reset_password_alert_heading').html('');
                    $('#email').val('');
                    set_header_message("An email has be sent to your account with details on how to reset your password. . ");
                }
                else
                {
                    $('#reset_password_alert_heading').attr("hidden", false);
                    $('#reset_password_alert_heading').html(result['error_heading']);
                }
            });

        }
        
        function initial_setup()
        {
            user_logged = Boolean(<?php echo $this->session->userdata('logged_in') !== null && $this->session->userdata('logged_in'); ?>);
            user_confirmed = Boolean(<?php echo $this->session->userdata('is_confirmed') !== null && $this->session->userdata('is_confirmed'); ?>);
                                    
            if(user_logged)
            {
                clear_links();
                
                if(!user_confirmed)
                {
                    set_header_message("Please activate your account to place orders. ");
                }
                
                $('#login_alert_heading').attr("hidden", true);
                $('#register_alert_heading').attr("hidden", true);
                $('#user_link').html(<?php echo $this->session->userdata('email') != null ? json_encode($this->session->userdata('email')) : ''; ?>);
                $('#logout_register_link').html('Logout');
                $('#logout_register_link').attr("onclick", "logout()");
            }
            
        }
        
        function setup_links()
        {
            set_header_message("");
            $("#user_link").html('<?php echo $this->lang->line('shmyde_login'); ?>');
            $("#user_link").attr("data-toggle", "modal");
            $("#user_link").attr("data-target", "#loginModal");
            $("#logout_register_link").html('<?php echo $this->lang->line('shmyde_register'); ?>');
            $("#logout_register_link").attr("data-toggle", "modal");
            $("#logout_register_link").attr("data-target", "#registerModal");
            $("#logout_register_link").attr("onclick", "");
        }
        
        function clear_links()
        {
            $("#user_link").html('');
            $("#user_link").attr("data-toggle", "");
            $("#user_link").attr("data-target", "");
            $("#logout_register_link").html('');
            $("#logout_register_link").attr("data-toggle", "");
            $("#logout_register_link").attr("data-target", ""); 
            
        }
        
        function switch_to_login()
        {
            $('#registerModal').modal('hide');
            $('#loginModal').modal('show');
            return false;
        }
        
        function switch_to_register()
        {
            $('#loginModal').modal('hide');
            $('#registerModal').modal('show');
            return false;
        }
        
        function open_login()
        {
            $('#loginModal').modal('show');
        }
        
        function forgot_password_clicked()
        {
            $('#loginModal').modal('hide');
            window.location.href = "<?php echo site_url('user/forgot_password'); ?>";
        }
        
        $(document).ready(function()
        {
            
            $('#registerModal').on('shown.bs.modal', function() 
            {
                $('#register_alert_heading').attr("hidden", true);
                $('#register_alert_heading').html('');
            });
            
            $('#loginModal').on('shown.bs.modal', function() 
            {
                $('#login_alert_heading').attr("hidden", true);
                $('#login_alert_heading').html('');
            });
            
            setup_validation();
            
            setup_links();
            
            initial_setup();          
        });
        
        
    </script>

  </head>
  
<div id="messages" class="header-messages">
    <p id="header_message"></p>
</div>

<div class="container top-menu">

    <div class="row">
        <!-- Left Side of Header -->
        <div class="col-sm-4">

        </div>
        <!-- Middle of Header -->
        <div class='col-sm-4'>
            <i><?php echo $this->lang->line('shmyde_left_logo_text'); ?></i>
                <a href="<?php echo site_url('pages/view/home'); ?>">
                    <img src="<?php echo ASSETS_PATH; ?>images/logo_shmyde_old.png" class="logo-image">
                </a>
            <i><?php echo $this->lang->line('shmyde_right_logo_text'); ?></i>
        </div>
        <!-- Right Side of Header -->
        <div class="col-sm-4">
            <div class='language-select'>
                <a href='#'><img src="<?php echo ASSETS_PATH; ?>images/en-flag.jpg"></a> | 
                <a href='#'><img src="<?php echo ASSETS_PATH; ?>images/fr-flag.png"></a>
            </div>

            <?php if($this->router->fetch_method() != 'login') : ?>
            <div class='login-link'>
                <i class='glyphicon glyphicon-user'></i>
                <a id="user_link" href='#' data-toggle="modal" data-target="#loginModal"><?php echo $this->lang->line('shmyde_login'); ?></a>
                |
                <a id="logout_register_link" href='#' data-toggle="modal" data-target="#registerModal"><?php echo $this->lang->line('shmyde_register'); ?></a>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div> 

<div class="top-menu-link container">
    <ul>
        <li><a href='<?php echo site_url('pages/view/about-us'); ?>'><?php echo $this->lang->line('shmyde_about_us'); ?></a></li>
        <li><a href='<?php echo site_url('pages/view/contact-us'); ?>'><?php echo $this->lang->line('shmyde_contact_us'); ?></a></li>
        <li><a href='<?php echo site_url('pages/view/review'); ?>'><?php echo $this->lang->line('shmyde_review'); ?></a></li>
        <li><a href='<?php echo site_url('pages/view/faq'); ?>'><?php echo $this->lang->line('shmyde_faq'); ?></a></li>
    </ul> 
</div>
  
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

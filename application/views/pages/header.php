<!DOCTYPE html>
<html lang="en">

	<?php 
		
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();
		}
	?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>
    
    <!-- Bootstrap -->
    <link href="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/set1.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/style.css" rel="stylesheet"> 
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
    <script src="<?php echo ASSETS_PATH; ?>sly-master/dist/sly.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        $('a').click(function(e)
        {
            // Special stuff to do when this link is clicked...

            // Cancel the default action
            e.preventDefault();
        });
    </script>
  </head>

	<div class='top-menu'>
            
            <div class="logo-section row">
                <div class="col-sm-3"></div>
                <div class='col-sm-6'>
                    <i><?php echo $this->lang->line('shmyde_left_logo_text'); ?></i>
                    <a href="<?php echo site_url('pages/view/home'); ?>">
                        <img src="<?php echo ASSETS_PATH; ?>images/logo_shmyde.png" class="logo-image">
                    </a>
                    <i><?php echo $this->lang->line('shmyde_right_logo_text'); ?></i>
                </div>
                <div class="col-sm-3">
                    <div class='language-select'>
                        <a href='#'><img src="<?php echo ASSETS_PATH; ?>images/en-flag.jpg"></a> | 
                        <a href='#'><img src="<?php echo ASSETS_PATH; ?>images/fr-flag.png"></a>
                    </div>

                    <?php 

                        $redirect = site_url('auth/login?redirect=').urlencode( $this->uri->uri_string());

                        $account_login_url = !isset($user) ?  $redirect :  site_url('examples');

                        $account_login_name = !isset($user) ?  $this->lang->line('shmyde_login') :  $user->email;

                    ?>

                    <div class='login-link'>
                        <i class='glyphicon glyphicon-user'></i> <a href='<?php echo $account_login_url; ?>'><?php echo $account_login_name; ?></a>
                        <?php
                            if(isset($user)){

                                echo '| <a href=\''.site_url('auth/logout').'\'>'.$this->lang->line('shmyde_logout').'</a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div>
                <ul>
                    <li><a href='<?php echo site_url('pages/view/about-us'); ?>'><?php echo $this->lang->line('shmyde_about_us'); ?></a></li>
                    <li><a href='<?php echo site_url('pages/view/contact-us'); ?>'><?php echo $this->lang->line('shmyde_contact_us'); ?></a></li>
                    <li><a href='<?php echo site_url('pages/view/review'); ?>'><?php echo $this->lang->line('shmyde_review'); ?></a></li>
                    <li><a href='<?php echo site_url('pages/view/faq'); ?>'><?php echo $this->lang->line('shmyde_faq'); ?></a></li>
                </ul> 
            </div>
            
	</div> 

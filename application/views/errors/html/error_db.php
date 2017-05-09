<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Database Error</title>
    
    <!-- Bootstrap -->
    <link href="<?php echo ASSETS_PATH; ?>frameworks/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_PATH; ?>css/main.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Calligraffitti" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
       
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }


a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

body 
{
    height: 100%;
}

#container 
{
    margin-top: 60px;
    border: 1px solid #D0D0D0;
    box-shadow: 0 0 8px #D0D0D0;
    background-color: white;
    height: 200px;
    text-align: center;
}

p 
{
    margin: 12px 15px 12px 15px;
}
</style>
  </head>
	
      
    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          
            <span>                
                <img src="<?php echo ASSETS_PATH ?>/images/logo_shmyde_old.png" alt="Shmyde Corp." class="site-logo" > 
                <a class="navbar-brand site-name" href="<?php echo site_url();?>">Shmyde</a>
            </span>
          
        </div>
      </div>
    </nav>  
    
    
        <div id="container" class="container">
        <h1><?php echo $heading; ?></h1>
        <?php echo $message; ?>
    </div>
        
    
</body>

</html>

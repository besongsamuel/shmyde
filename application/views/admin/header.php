<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SHMYDE ADMIN</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href="<?php echo ASSETS_PATH; ?>color-picker/css/bootstrap-colorpicker.css" rel="stylesheet">
        <link href="<?php echo ASSETS_PATH; ?>/css/uploader.css" rel="stylesheet">
        <link href="<?php echo ASSETS_PATH; ?>/css/design-shirt.css" rel="stylesheet">
        <script src="<?php echo ASSETS_PATH; ?>color-picker/js/bootstrap-colorpicker.js"></script>
    </head>
    
    <body>
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

        <div class="container">
            <a href='<?php echo $this->session->userdata('username') != null ? site_url('user/account') : site_url('user/login?redirect=').urlencode( $this->uri->uri_string()); ?>'>
                <?php echo $this->session->userdata('username') != null ? $this->session->userdata('username') : $this->lang->line('shmyde_login'); ?>
            </a>
            <?php echo $this->session->userdata('username') != null ? " | " : ""; ?>
            <a href='<?php echo $this->session->userdata('username') != null ? site_url('user/logout') : site_url('user/login?redirect=').urlencode( $this->uri->uri_string()); ?>'>
                <?php echo $this->session->userdata('username') != null ? $this->lang->line('shmyde_logout') : ""; ?>
            </a>
        </div>
    </body>
</html>
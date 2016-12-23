<!DOCTYPE html>
<html lang="en">
    
    <script>
        
        /**
         * Login was succesfull, redirect to admin page
         * @returns {void}
         */
        function redirectToAdmin()
        {
            window.location.href = "<?php echo site_url('admin/view/product'); ?>";
        }
        
        login_callbacks.add(redirectToAdmin);
        
    </script>
    
    <body>

        <?php if(!$this->session->userdata("is_admin") && $this->session->userdata("logged_in")) :?>
            <div class="container invalid-container">
                <p>This user is not an administrator. Please Login as an administrator. </p>
            </div>
        <?php endif; ?>
        
        <?php if(!$this->session->userdata("logged_in")) :?>
            <div class="container invalid-container">
                <p>Please Login as an administrator. </p>
            </div>
        <?php endif; ?>

    </body>

</html>
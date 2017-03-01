
<script>
    
    var userManager;
    var user = JSON.parse('<?php echo $user;  ?>');
    userManager = new User(user);
    userManager.base_url = '<?= site_url("/"); ?>';
    
    messages = JSON.parse('<?php echo $message; ?>');
    message_header = JSON.parse('<?php echo $message_title; ?>');
</script>

<div ng-controller="MessageController as messageController" class="container checkout-message">

     <div class="row">
        <div class="container">
            <div class="section-header">
                <h3 class="text-center">{{messageController.message_header}}</h3>
            </div> 
            <div class="section-body">
                <p ng-repeat="message in messageController.messages">{{message}}</p>
            </div>
            <div class="center-button">
                <button type="button" class="btn btn-default" ng-click="messageController.gotoHome()">Home</button>
            </div>
        </div>
        
     </div>
        
</div>

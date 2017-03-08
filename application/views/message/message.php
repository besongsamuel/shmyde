
<script>
    
    $(document).ready(function(){
        
        var messageScope = angular.element("#messageContainer").scope();
        
        messageScope.$apply(function(){
            
            messageScope.messages = JSON.parse('<?php echo $message; ?>');
            messageScope.message_header = JSON.parse('<?php echo $message_title; ?>');;
        });
        
    });
    
    
</script>

<div ng-controller="MessageController" class="container checkout-message" id="messageContainer">

     <div class="row">
        <div class="container">
            <div class="section-header">
                <h3 class="text-center">{{message_header}}</h3>
            </div> 
            <div class="section-body">
                <p ng-repeat="message in messages">{{message}}</p>
            </div>
            <div class="center-button">
                <button type="button" class="btn btn-default" ng-click="gotoHome()">Home</button>
            </div>
        </div>        
     </div>        
</div>

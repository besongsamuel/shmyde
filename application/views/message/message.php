
<script>
    
    $(document).ready(function(){
        
        var messageScope = angular.element("#messageContainer").scope();
        
        messageScope.$apply(function(){
            
            messageScope.messages = JSON.parse('<?php echo $message; ?>');
            messageScope.message_header = JSON.parse('<?php echo $message_title; ?>');
            messageScope.from_design = Boolean(JSON.parse('<?php if(isset($isDesignPresent)) { echo 'true';} else {echo 'false';} ?>'));
        });
        
    });
    
    
</script>

<div ng-controller="MessageController" class="container checkout-message" id="messageContainer">

     <div class="row">
        <div class="col-lg-12">
            <div class="section-header">
                <h3 class="text-center">{{message_header}}</h3>
            </div> 
            <div class="section-body" style="margin-bottom: 30px;">
                <p style="text-align: center;" ng-repeat="message in messages">{{message}}</p>
            </div>
            <div class="center-button">
                <button type="button" class="btn btn-default" ng-click="gotoHome()">Home</button>
                <button type="button" class="btn btn-default" ng-show="from_design" ng-click="gotoDesign()">Back To Design</button>
            </div>
        </div>        
     </div>        
</div>

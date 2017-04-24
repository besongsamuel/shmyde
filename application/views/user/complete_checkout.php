<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container white-background" style="margin-top: 10%;"  ng-controller="UserController">
    <h3 class="text-center">Complete Checkout</h3>
    <p style="margin: auto; text-align: center;">Please enter your payment code below to complete checkout.</p>
    <form name="completeCheckoutForm" ng-submit="complete_checkout()" novalidate>
        <!-- Email control -->
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                    <input  id="code" type="text" name="code" class="form-control" placeholder="Payment Code" ng-model="code"
                             ng-required='required'>
                    <span class="form_hint" ng-hide="completeCheckoutForm.code.valid">                                      
                        <p ng-show="forgotPasswordForm.code.$error.required">Enter a payment code. </p>
                    </span>
                </div>
            </div>                           
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="form-group">
                    <input type="submit" class="btn btn-default pull-right" value="Send">
                </div>
            </div>
        </div>
    </form>
</div><!-- .container -->
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <div class="row">
        <div class="container forgot-password">
            <p>Please enter your email below and we will send you details on how to reset your password.</p>
            <?= form_open('user/forgot_password',array('id'=>'forgot_password_form','method'=>'post')) ?> 
                <label>Email</label>               
                <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Your email address">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Send" style="float : right;">
                </div>
                <div class="col-md-12">
                    <div id="reset_password_alert_heading" class="alert alert-danger" role="alert" hidden="true">
                    </div>
                </div>
            </form>
        </div>
    </div><!-- .row -->  
</div><!-- .container -->
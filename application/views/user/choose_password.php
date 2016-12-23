<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <div class="row">
        <div class="container choose-password">
            <?= form_open('user/choose_password',array('id'=>'choose_password','method'=>'post')) ?> 
                <div class="form-group">
                    <label for="new_password">Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter a new password">
                    <p class="help-block">At least 6 characters</p>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm password</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm your password">
                    <p class="help-block">Must match your password</p>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="Reset">
                </div>
            </form>
        </div>
    </div><!-- .row -->  
</div><!-- .container -->
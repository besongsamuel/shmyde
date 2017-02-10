/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function(){
    
    var app = angular.module('checkout', ['puigcerber.countryPicker']);
    
    app.controller('CheckoutController', function($sce){
        
        this.designTypes = design_types;
        
        this.orderDetailsTableHeader = ['Product', 'Description', 'Quantity', 'Price'];
        
        this.quantity = 1;
        
        this.productManager = productManager;
        
        this.user = userManager.user;
        
        this.detail_header = "<h1>" + this.productManager.product.name + " : " + this.productManager.product.price + " FCFA </h1>";
        
        this.product_name = $sce.trustAsHtml(this.detail_header);
        
        this.price = this.productManager.total_price;
        
        this.agree_to_terms = false;
                
        
    });
    
    var design_types = ['Casual', 'Professional', 'Party'];
        
})();



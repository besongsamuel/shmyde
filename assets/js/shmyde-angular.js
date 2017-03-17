/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function(){
    
    var app = angular.module('shmyde', ['ngMessages', 'internationalPhoneNumber', 'puigcerber.countryPicker']);
    
    app.directive('isUniqueEmail', function($http, $q) 
    {
        return {
            require: 'ngModel',
            restrict : 'A',
            transclude : true,
            link: function(scope, elm, attrs, ctrl) 
            {  
                
                ctrl.$asyncValidators.isUniqueEmail = function(modelValue, viewValue) 
                {
                    var defer = $q.defer();
                    if (ctrl.$isEmpty(modelValue)) 
                    {
                        // consider empty models to be valid
                        defer.resolve();
                        return defer.promise;
                    }
                    
                
                    return $http.get(scope.site_url.concat('user/checkemail/').concat(viewValue)).then(function(response) 
                    {
                        if(JSON.parse(response.data))
                        {
                            defer.reject();
                        }
                        else
                        {
                            defer.resolve();
                        }
                        
                        return defer.promise;
                    });
                };
            }
        };
    });
    
    app.directive('pwCheck', [function () {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function (scope, elem, attrs, ctrl) {
          var firstPassword = '#' + attrs.pwCheck;
          elem.add(firstPassword).on('keyup', function () {
            scope.$apply(function () {
              var v = elem.val()===$(firstPassword).val();
              ctrl.$setValidity('pwmatch', v);
            });
          });
        }
      };
    }]);

    var EMAIL_REGEXP = /^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/;
    var PHONE_REGEXP = /^\+(?:[0-9] ?){6,14}[0-9]$/;
        
    app.directive('email', function() {
      return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, elm, attrs, ctrl) {
          ctrl.$validators.email = function(modelValue, viewValue) {
            if (ctrl.$isEmpty(modelValue)) {
              // consider empty models to be valid
              return true;
            }

            if (EMAIL_REGEXP.test(viewValue)) {
              // it is valid
              return true;
            }

            // it is invalid
            return false;
          };
        }
      };
    });
    
    app.directive('phone', function() {
      return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, elm, attrs, ctrl) {
          ctrl.$validators.phone = function(modelValue, viewValue) {
            if (ctrl.$isEmpty(modelValue)) {
              // consider empty models to be valid
              return true;
            }

            if (PHONE_REGEXP.test(viewValue)) {
              // it is valid
              return true;
            }

            // it is invalid
            return false;
          };
        }
      };
    });

    
        
    app.controller('CheckoutController', ['$scope', '$sce', '$http', function($scope, $sce, $http){
        
        $scope.designTypes = ['Casual', 'Professional', 'Party'];
        
        $scope.orderDetailsTableHeader = ['Product', 'Description', 'Quantity', 'Price'];
        
        $scope.quantity = 1;
        
        $scope.productManager = null;
        
        $scope.user = null;
                
        $scope.detail_header = $scope.productManager === null ? "" : "<h1>" + $scope.productManager.product.name + " : " + $scope.productManager.product.price + " FCFA </h1>";
        
        $scope.product_name = $sce.trustAsHtml($scope.detail_header);
        
        $scope.price = $scope.productManager === null ? 0 : $scope.productManager.total_price;
        
        $scope.agree_to_terms = false;
        
        $scope.checkout = function()
        {
            var xsrf = $.param({
                    design_data : JSON.stringify($scope.productManager.getDesignParameters()), 
                    quantity : $scope.quantity,
                    price : $scope.price
                });
            
            $http({
                method: 'POST',
                url: $scope.productManager.base_url.concat('checkout/checkout'),
                data : xsrf,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) 
            {
                
                if(Boolean(JSON.parse(response.data)))
                {
                    window.location = $scope.productManager.base_url.concat('checkout/message/0');
                }
                else
                {
                    window.location = $scope.productManager.base_url.concat('checkout/message/1');
                }
                
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                window.location = $scope.productManager.base_url.concat('checkout/message/1');
              });
            
        };

    }]);
    
    app.controller('UserController', ['$scope', '$http', function($scope, $http)
    {
        $scope.emailpattern = '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$'; 
        
        $scope.phonepattern = '[+3]?[0-9]*';
        
        $scope.required = true;
        
        $scope.country = 'CM';
        
        $scope.phone_number_has_focus = false;
        
        $scope.phone_number_focus = function(){
            
            $scope.phone_number_has_focus = true;
        };
        
        $scope.phone_number_blur = function(){
            
            $scope.phone_number_has_focus = false;
        };
        
        $scope.email = '';
        
        $scope.login_error = false;
        
        $scope.register = function()
        {
            
            var xsrf = $.param({
                });
                
            $http({
            method: 'POST',
            url: $scope.site_url.concat('user/register'),
            data : xsrf,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) {
              // this callback will be called asynchronously
              // when the response is available
              console.log(response);
            }, function errorCallback(response) {
              // called asynchronously if an error occurs
              // or server returns response with an error status.
              console.log(response);
            });
        };
        
       
    }]);
    
    app.controller('MessageController', ['$scope', function($scope)
    {
        $scope.messages = [];
        
        $scope.message_header = '';
        
        $scope.gotoHome = function()
        {
            window.location = $scope.home_url;
        };
        
    }]);
    
    app.controller('HeaderController', ['$scope', '$rootScope', function($scope, $rootScope)
    {
        /**
         * Checks if the root scope is initialized
         * @returns {undefined}
         */
        $rootScope.initialized = function()
        {          
            if($scope.is_initialized === null || $scope.is_initialized === 'undefined')
            {
                return false;
                
            }
            
            return $scope.is_initialized;
            
        };    
            
        $scope.user_logged = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if(parseInt($scope.user.id) === -1)
            {
                return false;
            }
            else
            {
                return true;
            }
        };
        
        $scope.homeMenuVisible = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'home')
            {
                return true;
            }
                        
            if($scope.controller.toString() === 'checkout' && $scope.method.toString() === 'message')
            {
                return true;
            }
            
            return false;
        };
        
        $scope.designMenuVisible = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'home')
            {
                return true;
            }
                        
            return false;
        };
        
        $scope.aboutUsVisible = function()
        {
            
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'home')
            {
                return true;
            }
                        
            return false;
        };
        
        $scope.contactUsVisible = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'home')
            {
                return true;
            }
                        
            return false;
        };
        
        $scope.gotoHome = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() !== 'home')
            {
                window.location = $scope.site_url;
            }
                       
        };
        
        $scope.logout = function()
        {
            window.location = $scope.site_url.concat("user/logout");           
        };
        
        $scope.login = function()
        {
            window.location = $scope.site_url.concat("user");           
        };
               
    }]);

    app.controller('DesignSectionController', ['$scope', function($scope)
    {
        $scope.products = null;
        
        $scope.selected_products = null; 
        
        $scope.selected_product = 0;
        
        $scope.get_products = function(target)
        {
             $scope.selected_products = [];
            
            if($scope.products !== null)
            {
                for(var key in $scope.products)
                {
                    var product = $scope.products[key];
                    
                    if(parseInt(product.target) === parseInt(target))
                    {
                         $scope.selected_products.push(product);
                    }
                }
            }
            
        };
        
        $scope.$watch('selected_products', function() 
        {
            $scope.selected_product = 0;
        });
    }]);

        
})();



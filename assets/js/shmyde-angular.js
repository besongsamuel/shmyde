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
        
        $scope.$watch('productManager', function(newValue, oldValue) 
        {
            $scope.detail_header = $scope.productManager === null ? "" : "<h3>" + $scope.productManager.product.name + " : " + $scope.productManager.product.price + " FCFA </h3>";
            $scope.product_name = $sce.trustAsHtml($scope.detail_header);
        });
        
        $scope.product_name = $sce.trustAsHtml($scope.detail_header);
        
        $scope.price = $scope.productManager === null ? 0 : $scope.productManager.total_price;
        
        $scope.agree_to_terms = false;
        
        $scope.checkout = function()
        {
            var xsrf = $.param({
                    design_data : JSON.stringify($scope.productManager.getDesignParameters()), 
                    quantity : $scope.quantity,
                    price : $scope.price,
                    frontDesignImage : $scope.frontDesignImage,
                    backDesignImage :  $scope.backDesignImage,
                    type : $scope.type,
                    order_id : $scope.order_id
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
        $scope.registrationObject = 
        {
            country : 'CM',
            email : '',
            last_name : '',
            first_name : '',
            phone_number : '',
            city : '',
            address_line_1 : '',
            address_line_2 : '',
            password : ''
        };
        
        $scope.loginObject = 
        {
            email : '',
            password : '',
            remember_me : false
            
        };
        
        
        $scope.required = true;
        
        $scope.registration_error_message = '';
        
        $scope.login_error_message = '';
                
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
            if($scope.registerForm.$invalid)
            {
                if($scope.registerForm.confirm_password.$invalid)
                {
                    $("#r_confirm_password").focus();
                }
                
                if($scope.registerForm.password.$invalid)
                {
                    $("#r_password").focus();
                }
                
                if($scope.registerForm.phonenumber.$invalid)
                {
                    $("#r_phonenumber").focus();
                }
                
                if($scope.registerForm.lastname.$invalid)
                {
                    $("#r_lastname").focus();
                }
                                
                if($scope.registerForm.email.$invalid)
                {
                    $("#r_email").focus();
                }
            }
            else
            {
                $http({
                method  : 'POST',
                url     : $scope.site_url.concat('user/register'),
                data    : $.param($scope.registrationObject),  
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
                }).then(function successCallback(response) 
                {
                    $scope.register_error = response.data.invalid;
                    
                    if($scope.register_error)
                    {
                        window.location =  $scope.site_url.concat("user/registration_complete/".concat(response.data.type.toString()));
                    }
                    else
                    {
                        window.location =  $scope.site_url.concat("user/registration_complete/".concat(response.data.type.toString()));
                    }
                     
                }, function errorCallback(response) 
                {
                    window.location =  $scope.site_url.concat("user/registration_complete/".concat(response.data.type.toString())); 
                });
            }
            
            
        };
     
        $scope.login = function()
        {
            
            if($scope.loginForm.$invalid)
            {
                if($scope.loginForm.password.$invalid)
                {
                    $("#password").focus();
                }
                                
                if($scope.loginForm.email.$invalid)
                {
                    $("#email").focus();
                }
            }
            else
            {
                $http({
                  method  : 'POST',
                  url     : $scope.site_url.concat('user/login'),
                  data    : $.param($scope.loginObject),  
                  headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
                }).then(function successCallback(response) 
                {
                                        
                    $scope.loginError = response.data.invalid;
                    
                    if($scope.loginError)
                    {
                        $scope.login_error_message = response.data.message;
                    }
                    else
                    {
                        window.location = response.data.redirect_url;
                    }

                }, function errorCallback(response) 
                {
                    $scope.loginError = true;
                    $scope.login_error_message = 'An unexpected error occured. Please try again. ';
                });
            }
        };
        
        $scope.submit_email = function()
        {
            if($scope.forgotPasswordForm.$valid)
            {
                $http({
                  method  : 'POST',
                  url     : $scope.site_url.concat('user/send_forgot_password_email'),
                  data    : $.param($scope.forgotPasswordObject),  
                  headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
                }).then(function successCallback(response) 
                {
                    
                    if(response.data.valid)
                    {
                        window.location =  $scope.site_url.concat("user/forgot_password_complete/0");
                    }
                    else
                    {
                        window.location =  $scope.site_url.concat("user/forgot_password_complete/1");
                    }

                }, function errorCallback(response) 
                {
                    window.location =  $scope.site_url.concat("user/forgot_password_complete/1");
                });
            }
            
        };
        
        $scope.get_design_status = function(status_code)
        {
            if(parseInt(status_code) === 0)
            {
                return "Pending Payment";
            }
            
            if(parseInt(status_code) === 1)
            {
                return "Pending Dilivery";
            }
            
            if(parseInt(status_code) === 2)
            {
                return "Incomplete Design";
            }
            
            if(parseInt(status_code) === 20)
            {
                return "Saved Design";
            }
        };
        
        $scope.can_complete = function(status_code)
        {
            if(parseInt(status_code) === 0)
            {
                return true;
            }
            
            return false;
        };
        
        $scope.can_edit = function(status_code)
        {
            if(parseInt(status_code) === 0 || parseInt(status_code) === 20)
            {
                return true;
            }
            
            return false;
        };
        
        $scope.delete_design = function(design_id)
        {
            if (confirm('Are you sure you want to parmanently delete this design?')) 
            {
                $.ajax({
                    url : $scope.site_url.concat('Design/DeleteUserDesign'),
                    data : {
                        order_id :  design_id},
                    async : true,
                    type : 'POST',
                    success : function(response)
                    {
                        $scope.$apply(function()
                        {
                            $scope.user_orders = JSON.parse(response);
                        });
                    }
                });
            }
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
    
    app.controller('AccountController', ['$scope', function($scope)
    {
        
        $scope.saveUserDetails = function()
        {
            if($scope.userDetailsForm.$valid)
            {
                $scope.loading = true;
                $.ajax(
                    {
                        url : $scope.site_url.concat("user/saveUserDetails"),
                        type : 'POST',
                        async : true,
                        data :  
                        {
                            first_name : $scope.first_name,
                            last_name : $scope.last_name,
                            gender : $scope.gender,
                            dob : $scope.dob
                        },
                        success : function(response)
                         {
                            $scope.loading = false;
                             
                            $scope.userObject.user.first_name = $scope.first_name;
                            $scope.userObject.user.last_name = $scope.last_name;
                            $scope.userObject.user.gender = $scope.gender;
                            $scope.userObject.user.dob = $scope.dob;
                         },
                        error   : function(){}
                    });
            }
        };
        
        $scope.cancelUserDetails = function()
        {
            $scope.first_name = $scope.userObject.user.first_name;
            $scope.last_name = $scope.userObject.user.last_name;
            $scope.gender = $scope.userObject.user.gender;
            $scope.dob = $scope.userObject.user.dob;
        };
                                         
        $scope.changeUserPassword = function()
        {
            if($scope.changePasswordForm.$valid)
            {
                $scope.loading = true;
                $.ajax(
                    {
                        url : $scope.site_url.concat("user/changeUserPassword"),
                        type : 'POST',
                        async : true,
                        data :  
                        {
                            new_password : $scope.new_password,
                            old_password : $scope.old_password
                        },
                        success : function(response)
                         {
                            $scope.loading = false;
                            $scope.new_password = "";
                            $scope.confirm_new_password = "";
                         },
                        error   : function(){}
                    });
            }
        };
        
        $scope.cancelUserPassword = function()
        {
            $scope.new_password = "";
            $scope.confirm_new_password = "";
        };
                                         
        $scope.saveUserAddress = function()
        {
            if($scope.userAddressForm.$valid)
            {
                $scope.loading = true;
                $.ajax(
                    {
                        url : $scope.site_url.concat("user/saveUserAddress"),
                        type : 'POST',
                        async : true,
                        data :  
                        {
                            address_line_1 : $scope.address_line_1,
                            address_line_2 : $scope.address_line_2,
                            country : $scope.country,
                            city : $scope.city
                        },
                        success : function(response)
                         {
                            $scope.loading = false;
                            $scope.userObject.user.address_line_1 = $scope.address_line_1;
                            $scope.userObject.user.address_line_2 = $scope.address_line_2;
                            $scope.userObject.user.country = $scope.country;
                            $scope.userObject.user.city = $scope.city;                             
                            
                         },
                        error   : function(){}
                    });
            }
        };
        
        $scope.cancelUserAddress = function()
        {
            $scope.address_line_1 = $scope.userObject.user.address_line_1;
            $scope.address_line_2 = $scope.userObject.user.address_line_2;
            $scope.country = $scope.userObject.user.country;
            $scope.city = $scope.userObject.user.city;
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
        
        $scope.goto_account = function()
        {
            window.location = $scope.site_url.concat("user/account");
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
        
        $rootScope.homeMenuVisible = function()
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
        
        $rootScope.designMenuVisible = function()
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
        
        $rootScope.aboutUsVisible = function()
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
        
        $rootScope.contactUsVisible = function()
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
        
        $rootScope.gotoHome = function()
        {
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() !== 'home')
            {
                window.location = $scope.home_url;
            }
                       
        };
        
        $scope.logout = function()
        {
            window.location = $scope.site_url.concat("user/logout");           
        };
        
        $scope.login = function()
        {
            // Save Design
            if($scope.controller.toString() === 'design')
            {
                var designParameters = $scope.productManager.getDesignParameters();
                
                $.ajax({
                url : $scope.site_url.concat('Design/SaveTmpUserDesign'),
                data : {designParameters : JSON.stringify(designParameters)},
                async : true,
                type : 'POST',
                success : function()
                {
                    window.location.href = $scope.site_url.concat("user");
                }
            });
            }
            else
            {
                window.location = $scope.site_url.concat("user"); 
            }
            
                      
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
    
    app.controller('ContactUsController', ['$scope', function($scope)
    {
        $scope.contactName = "";
        $scope.contactEmail = "";
        $scope.contactComment = "";
        
        $scope.Message = "We love our fans";
        
        $scope.submitComment = function()
        {
            $.ajax({
                url : $scope.site_url.concat('Home/ContactUs'),
                data : {
                        contactName : JSON.stringify($scope.contactName),
                        contactEmail : JSON.stringify($scope.contactEmail),
                        contactComment : JSON.stringify($scope.contactComment),
                    },
                async : true,
                type : 'POST',
                success : function()
                {
                    $scope.contactName = "";
                    $scope.contactEmail = "";
                    $scope.contactComment = "";
                    $scope.Message = "We have recieved your comments. Thank you for your interest.";
                },
                error : function()
                {
                    $scope.Message = "An unexpected error occured. Please contact us later. ";
                }
            });
        };
        
    }]);
    
    app.controller('ProductController', ['$scope', function($scope)
    {
        // This is the product managed by the product controller. 
        $scope.products = [];
        
        $scope.selected_product_id = -1;
        
        $scope.delete_product = function(product_id)
        {
        
        };
        
        $scope.add_product = function()
        {
            
        };
        
    }]);

    app.controller('DesignController', ['$scope', '$rootScope', function($scope, $rootScope)
    {
        $scope.selected_category = 0;
        
        $scope.DesignCategorySelected = function(category_selected)
        {
            $scope.selected_category = category_selected;
            
            $('#option-list').empty();
            
            $scope.productManager.category_selected = category_selected;
            
            if(parseInt(category_selected) === 1 || parseInt(category_selected) === 2)
            {
                $scope.productManager.loadMenus("sub_menu_list");
            }
            
            if(parseInt(category_selected) === 3)
            {
                $scope.productManager.loadMixMenus("sub_menu_list");
            }
            
            if(parseInt(category_selected) === 4)
            {
                $scope.productManager.loadMeasurementMenus();
            }
            
            if(parseInt(category_selected) === 5)
            {
                $scope.productManager.LoadButtonOptions();
            }
                        
            if(parseInt(category_selected) === 6)
            {
                $scope.productManager.invertFabric();
            }            
        };
        
        $scope.checkout = function()
        {
            $scope.userObject.CheckOut($scope.productManager, $scope.order_id, $scope.order_Status);
        };
        
        $rootScope.saveDesignVisible = function()
        {
            
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'design' && $scope.method.toString() === 'product' && $rootScope.order_id === -1)
            {
                return true;
            }
            
            return false;
        };
        
        $rootScope.updateDesignVisible = function()
        {
            
            if(!$rootScope.initialized())
            {
                return;
            }
            
            if($scope.controller.toString() === 'design' && $scope.method.toString() === 'product' && $rootScope.order_status === 20)
            {
                return true;
            }
            
            return false;
        };
        
        $rootScope.savedesign = function()
        {
            if (confirm('Are you sure you want to save the current design?')) 
            {
                var designParameters = $rootScope.productManager.getDesignParameters();
                
                $rootScope.productManager.setProductDetails();
                
                Instance = this;
                
                var node = document.getElementById($rootScope.productManager.designDomElementID);
            
                domtoimage.toPng(node)
                .then(function (dataUrlFront) 
                {    
                    var backNode = document.getElementById($rootScope.productManager.designBackDomElementID);

                    domtoimage.toPng(backNode)
                    .then(function (dataUrlBack) 
                    {
                        // Save current Design. Shall be reloaded after login
                        $.ajax({
                            url : $rootScope.site_url.concat('Design/SaveUserDesign'),
                            data :  {
                                        designParameters : JSON.stringify(designParameters), 
                                        order_id : $rootScope.order_id, 
                                        price : $rootScope.productManager.total_price,
                                        frontDesignImage : dataUrlFront,
                                        backDesignImage :  dataUrlBack
                                    },
                            async : true,
                            type : 'POST',
                            success : function(response)
                            {
                                $rootScope.order_id = parseInt(JSON.parse(response));
                                
                                if($rootScope.order_id !== -1)
                                {
                                    $rootScope.order_status = 20;
                                }
                                
                                alert("Current design has been saved to your user account. ");
                            }
                        });
                    });
                })
                .catch(function (error) {
                    console.error('oops, something went wrong!', error);
                });
            } 
        };
        
    }]);

        
})();



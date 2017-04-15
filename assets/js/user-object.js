/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function User(user_object)
{
    this.user = user_object;
    
    this.checking_out = false;
    
    this.base_url = "";
    
    this.updateUser = function()
    {
        this.user.first_name = $("#userDataModal #first_name").val();
        
        this.user.last_name = $("#userDataModal #last_name").val();
        
        this.user.phone_number = $("#userDataModal #contact_phone").val();
        
        this.user.address_line_1 = $("#userDataModal #address_line_01").val();
        
        this.user.address_line_2 = $("#userDataModal #address_line_02").val();
        
        this.user.city = $("#userDataModal #city").val();
        
        this.user.country = $("#userDataModal #country").val();
        
        this.user.postcode = $("#userDataModal #postal_code").val();
        
        this.user.email = $("#userDataModal #user_email").val();
        
    };
    
    this.setUserToModal = function()
    {
        $("#userDataModal #last_name").val(this.user.last_name);

        $("#userDataModal #first_name").val(this.user.first_name);

        $("#userDataModal #contact_phone").val(this.user.phone_number);

        $("#userDataModal #address_line_01").val(this.user.address_line_1);

        $("#userDataModal #address_line_02").val(this.user.address_line_2);
        
        $("#userDataModal #city").val(this.user.city);
        
        $("#userDataModal #country").val(this.user.country);

        $("#userDataModal #postal_code").val(this.user.postcode);

        $("#userDataModal #user_email").val(this.user.email);
        
        
    };
    
    this.CheckOut = function(productManager, order_id, order_status)
    {
        var designParameters = productManager.getDesignParameters();
        
        Instance = this;
        
        // User is not logged in
        // Redirect to login page
        if(parseInt(this.user.id) === -1)
        {
            sessionStorage.setItem("order_id", order_id);
            sessionStorage.setItem("order_status", order_status);
            
            // Save current Design. Shall be reloaded after login
            $.ajax({
                url : Instance.base_url.concat('Design/SaveTmpUserDesign'),
                data : {designParameters : JSON.stringify(designParameters)},
                async : true,
                type : 'POST',
                success : function()
                {
                    window.location.href = Instance.base_url.concat('user');
                }
            });
        }
        else
        {
            var node = document.getElementById(productManager.designDomElementID);
            
            domtoimage.toPng(node)
            .then(function (dataUrlFront) 
            {    
                var backNode = document.getElementById(productManager.designBackDomElementID);
                
                domtoimage.toPng(backNode)
                .then(function (dataUrlBack) 
                {
                    $.ajax({
                        url : Instance.base_url.concat('Design/SaveTmpUserDesign'),
                        data : {designParameters : JSON.stringify(designParameters)},
                        async : true,
                        type : 'POST',
                        success : function()
                        {
                            
                            sessionStorage.setItem("frontDesignImage", dataUrlFront);
                            
                            sessionStorage.setItem("backDesignImage", dataUrlBack);
                    
                            window.location.href = Instance.base_url.concat('checkout');
                        }
                    });              
                });
                
                
            })
            .catch(function (error) {
                console.error('oops, something went wrong!', error);
            });
     
        }

    };
}

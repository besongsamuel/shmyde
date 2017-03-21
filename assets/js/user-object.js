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
    
    this.CheckOut = function(productManager)
    {
        if(parseInt(this.user) > 0)
        {
            login_callbacks = $.Callbacks();
            login_callbacks.add(this.CheckOut);
            open_login();
        }
        else
        {
            Instance = this;
            
            var designParameters = productManager.getDesignParameters();
            
            var node = document.getElementById('design-preview');
            
            // Save the current design as an immage
            domtoimage.toPng(node)
            .then(function (dataUrl) 
            {
                designParameters.designImage = dataUrl;
            })
            .catch(function (error) {
                console.error('oops, something went wrong!', error);
            });
                        
            window.sessionStorage.setItem("designParameters", JSON.stringify(designParameters));
            
            $.ajax({
                url : this.base_url.concat('Design/SaveTmpUserDesign'),
                data : {designParameters : JSON.stringify(designParameters)},
                async : true,
                type : 'POST',
                success : function()
                {
                    window.location.href = Instance.base_url.concat('checkout');
                }
            });
                        
            
        }

    };
}
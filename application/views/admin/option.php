<!DOCTYPE html>
<html lang="en">

<script type="text/javascript">
    
    function create_table_body(options_array){
    			
    	var table = document.getElementById("options_table");
    	   	
    	while(table.rows.length > 1){
    		
            table.deleteRow(1);
    	}
    	
    	
    	var tablebody = document.getElementById("tablebody");
					
    	for (var key in options_array) {
  		   		                                                                
            var tr = document.createElement('TR');

            var name = document.createElement('TD');
            name.appendChild(document.createTextNode(options_array[key]['name']));
            tr.appendChild(name);

            var price = document.createElement('TD');
            price.appendChild(document.createTextNode(options_array[key]['price']));
            tr.appendChild(price);

            var description = document.createElement('TD');
            description.appendChild(document.createTextNode(options_array[key]['description']));
            tr.appendChild(description);
            
            var is_default = document.createElement('TD');
            is_default.appendChild(document.createTextNode(parseInt(options_array[key]['is_default']) === 1 ? 'Yes' : 'No' ));
            tr.appendChild(is_default);
            
            var thumbnail = document.createElement('TD');
            var thumbnail_image = document.createElement('IMG');
            thumbnail_image.src = "<?php echo ASSETS_PATH;  ?>".concat("images/design/thumbnail/") + options_array[key]['thumbnail'];
            thumbnail_image.style.cssText = "width:50px, height:50px;";
            thumbnail.appendChild(thumbnail_image);
            tr.appendChild(thumbnail);

            var links = document.createElement('TD');

            var edit = document.createElement("a");  
            var edit_text = document.createTextNode("Edit");
            edit.href = "javascript:void(0);"; 
            edit.setAttribute("onclick", "edit_option(" + options_array[key]['id'] + ")");
            edit.appendChild(edit_text); 

            var delete_link = document.createElement("a");  
            var delete_text = document.createTextNode("Delete");
            delete_link.href = "<?php echo site_url('admin/delete/option/'); ?>/".concat(options_array[key]['id']); 
            delete_link.appendChild(delete_text); 
            
            var button_link = document.createElement("a");  
            var button_text = document.createTextNode("Edit Buttons");
            button_link.href = "<?php echo site_url('admin/button_editor/style/'); ?>/".concat(options_array[key]['id']); 
            button_link.appendChild(button_text); 


            links.appendChild(edit);
            links.appendChild(document.createTextNode(' | '));
            links.appendChild(delete_link);
            links.appendChild(document.createTextNode(' | '));
            links.appendChild(button_link);
            tr.appendChild(links);

            tablebody.appendChild(tr);	
    				
        }
    	
    }
      
    function MenuChanged(){

            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {

                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                    var json_array =  JSON.parse(xmlhttp.responseText);

                    create_table_body(json_array);

                }
            };

            var site_url = "<?php echo site_url('admin/get_options') ?>";

            site_url = site_url.concat("/").concat($( "#menu" ).val());

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();
    } 

    function ProductChanged(){

        var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {

                if (parseInt(xmlhttp.readyState) === 4 && parseInt(xmlhttp.status) === 200) {

                    var json_array =  JSON.parse(xmlhttp.responseText);

                    $("#menu").empty();

                    for (var key in json_array) {

                        $('#menu').append($("<option/>", {
                            value: json_array[key]['id'],
                            text:  json_array[key]['name']
                        }));

                    }

                    $("#menu").val($("#menu option:first").val());

                    MenuChanged();

                }
            };

            var site_url = "<?php echo site_url('admin/get_menus') ?>";

            site_url = site_url.concat("/").concat($( "#product" ).val());

            xmlhttp.open("GET", site_url, true);

            xmlhttp.send();

    }




    $(document).ready(function() {

        $("#target option[value='<?php if(isset($option)) echo $option->target; else echo 0; ?>']").prop('selected', true);

        $("#product option[value='<?php if($default_product_id > -1) echo $default_product_id; else echo 0; ?>']").prop('selected', true);

        $("#menu option[value='<?php if($default_menu_id > -1) echo $default_menu_id; else echo 0; ?>']").prop('selected', true);

        MenuChanged();


    });

    function create_new_option(){

        var site_url = '<?php echo site_url('admin/create/option'); ?>';

        site_url += '/' + $("#product").val() + '/' +  $("#menu").val();

        window.location.href = site_url;

    }
    
    function edit_option(option_id){

        var site_url = '<?php echo site_url('admin/edit/option'); ?>';

        site_url += '/' + option_id + '/' + $("#product").val() + '/' +  $("#menu").val();

        window.location.href = site_url;

    }
        
</script>

<body>



<div style="margin-left:5%; margin-top:5%;">
    <span><a href="javascript:void(0);" onclick="create_new_option()">Create New</a> </span>
</div>

<div class="container">
	<h2>SHMYDE MENU OPTIONS</h2>
	<div class="form-group">
            <label for="product">Product:</label>
            <select class="form-control" id="product" name="product" onchange="ProductChanged();">
            <?php foreach ($products->result() as $row) {?>
            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                <?php } ?>
            </select>
	</div>
	
	<div class="form-group">
            <label for="menu">Menu:</label>
            <select class="form-control" id="menu" name="menu" onchange="MenuChanged();">
            <?php foreach ($menus->result() as $row) {?>
            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                <?php } ?>
            </select>
	</div>
	
	
</div>

<div class="container">
  
  <table class="table table-hover" id="options_table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
	<th>Is Default</th>
        <th>Image</th>
        <th>Links</th>
      </tr>
    </thead>
    <tbody id="tablebody">
	    

    </tbody>
  </table>
</div>

</body>
</html>
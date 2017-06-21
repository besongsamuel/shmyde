<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Admin_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    //============================================================ PRODUCT ====================================================================
    

    /**
     * This function gets the list of all products and returns a query result of all those products
     * @return type
     */
    public function get_all_products(){
	
        $query = $this->db->query("SELECT *  from shmyde_product");

        return $query;
	
    }
    
    public function get_product_style_images($option_id){
        
        $style_images = array();
        
        $images = $this->get_images($option_id, 'shmyde_images');
        
        foreach ($images as $image) {
                        
            if(!$image['is_inner']){
                
                array_push($style_images, $image);
            }

        }
                               
        return $style_images;
    }


    /**
     * This function is used to get the product id given its target name (men, women, child etc)
     * and the url to that product
     * @param type $target_name
     * @param type $product_url_name
     * @return type
     */
    public function get_product_id($target_name, $product_url_name){
		
        $target_id = -1;

        switch($target_name){

                case 'men':
                        $target_id = 0;
                        break;
                case 'women':
                        $target_id = 1;
                        break;
                case 'both':
                        $target_id = 2;
                        break;

        }

        $sql = "SELECT * from shmyde_product where target = ".$target_id." and url_name = '".$product_url_name."'";

        $query = $this->db->query($sql);

        if($query->num_rows() > 0){

                return $query->row()->id;
        }
		
	}
     
    /**
     * Gets the product item from its ID
     * @param type $id The id of the product
     * @return type
     */    
    public function get_product($id){

        $product = $this->db->query('SELECT * from shmyde_product where id = '.$id);

        return $product->row();
    } 
    
    /**
     * This function gets the gets the images associated to a product
     * i.e. the back base images and the front base images that make
     * up a product
     * @param type $id The id of the product
     * @return type
     */
    public function get_product_images($id){

        $image_query = $this->db->query('SELECT * from shmyde_product_image where item_id = '.$id);

        $images = Array();

        foreach ($image_query->result() as $image)
        {
            if($image->view_type == 0){

                    $images['back_view_image'] = $image->name;
            }

            if($image->view_type == 1){

                    $images['front_view_image'] = $image->name;
            }

        }

        return $images;
    }
    
    /**
     * This function deletes a product and its image files
     * @param type $id
     */
    public function delete_product($id){
        
        $front_image_sql = "SELECT name FROM shmyde_product_front_image where item_id = ".$id;
        
        $front_query = $this->db->query($front_image_sql);
        
        foreach ($front_query->result() as $row){
            
            $image_path = ASSETS_DIR_PATH.'images/product/front/'.$row->name;
            
            if(file_exists($image_path)){
                
                unlink($image_path);
            }
        }
        
        $back_image_sql = "SELECT name FROM shmyde_product_back_image where item_id = ".$id;
        
        $back_query = $this->db->query($back_image_sql);
        
        foreach ($back_query->result() as $row){
            
            $image_path = ASSETS_DIR_PATH.'images/product/back/'.$row->name;
            
            if(file_exists($image_path)){
                
                unlink($image_path);
            }
        }
        
        
        $this->db->query('DELETE from shmyde_product where id = '.$id);
    }
    
   


    /**
     * This function creates a product
     * @param type $name The name of the product
     * @param type $url_name The url name of the product
     * @param type $target the target of the product
     * @param type $price the base price of the product
     * @return boolean returns true if the product is created
     */
    public function create_product($name, $url_name, $target, $price){

        $insert_id = $this->get_table_next_id("shmyde_product");
		
        $sql = "INSERT INTO shmyde_product (id, name, url_name, target, base_price) 

        VALUES (".$insert_id." , ".$this->db->escape($name).", ".$this->db->escape($url_name).", ".$this->db->escape($target).", ".$this->db->escape($price).")";

        $this->db->query($sql);
				
        return true;
    	
    }
    
    /**
     * This function edits a product
     * @param type $id The id of the product
     * @param type $name The name of the product
     * @param type $url_name The url of the product
     * @param type $target The target the product
     * @param type $price the base price of the product
     * @return boolean return true if the product is edited
     */
    public function edit_product($id, $name, $url_name, $target, $price){
    	        
    	$sql = "UPDATE shmyde_product SET name = ".$this->db->escape($name).", url_name = ".$this->db->escape($url_name).", target = ".$this->db->escape($target).", base_price = ".$this->db->escape($price)." WHERE id = ".$id;

        $this->db->query($sql);
	                
        return true;
    }
    
    
    //============================================================ MENU ====================================================================
        
    /**
     * This function gets all the menus from the database
     * @return type
     */
    public function get_all_menus(){
	
        $query = $this->db->query("SELECT shmyde_design_main_menu.*, shmyde_product.name as product_name from shmyde_design_main_menu, shmyde_product where shmyde_design_main_menu.shmyde_product_id = shmyde_product.id");

        return $query;
                
    }
    
    /**
     * Get all menus related to only design
     * @return type
     */
    public function get_design_menus(){
	
        $query = $this->db->query("SELECT shmyde_design_main_menu.*, shmyde_product.name as product_name from shmyde_design_main_menu, shmyde_product where shmyde_design_main_menu.shmyde_product_id = shmyde_product.id AND shmyde_design_main_menu.shmyde_design_category_id = 2");

        return $query;
                
    }
    
    public function get_design_dependent_menus(){
	
        $query = $this->db->query("SELECT shmyde_design_main_menu.*, shmyde_product.name as product_name from shmyde_design_main_menu, shmyde_product where shmyde_design_main_menu.shmyde_product_id = shmyde_product.id AND shmyde_design_main_menu.shmyde_design_category_id = 2 AND is_independent = 0");

        return $query;
                
    }
    
    /**
     * This function gets a given menu given the menu id
     * @param type $id
     * @return type
     */
    public function get_menu($id){

        $menu = $this->db->query('SELECT * from shmyde_design_main_menu where id = '.$id);

        return $menu->row();
    }
    
    public function get_menu_options($menu_id){
        
        return $this->db->query('SELECT shmyde_design_option.*, shmyde_option_thumbnail.name as thumbnail FROM '
                . 'shmyde_design_option LEFT OUTER JOIN shmyde_option_thumbnail ON '
                . 'shmyde_option_thumbnail.item_id = shmyde_design_option.id '
                . 'WHERE shmyde_design_main_menu_id = '.$menu_id);
               
    }


    /**
     * This function gets all the menus associated with a given product id provided
     * @param type $product_id
     * @return type
     */
    public function get_product_menus($product_id){
			
        $sql = "SELECT * from shmyde_design_main_menu where shmyde_product_id=".$product_id;

        $query = $this->db->query($sql);

        return $query;
    }
    
    public function get_product_category_menus($product_id, $category_id){
			
        $sql = "SELECT * from shmyde_design_main_menu where shmyde_product_id= ".$product_id." AND shmyde_design_category_id = ".$category_id;

        $query = $this->db->query($sql);

        return $query;
    }
    
    /**
     * This function gets the design menus based on the options
     * that are currently selected
     * @param type $product_id
     * @param type $selected_option_list
     * @return type
     */
    public function get_product_design_menus($product_id, $selected_option_list){
        
        $result = array();
        
        $sql = "SELECT * from shmyde_design_main_menu where shmyde_product_id = ".$product_id." AND shmyde_design_category_id = 2 AND is_independent = 1";
        
        $independent_menus = $this->db->query($sql);
        
        foreach ($independent_menus->result() as $menu) {
            
            array_push($result, $menu);
        }
        
        foreach ($selected_option_list as $option_id) {
            
            $option_menus = $this->db->query("SELECT * FROM shmyde_option_dependent_menu WHERE shmyde_design_option_id = ".$option_id);
            
            foreach ($option_menus->result() as $option_dependent_menu) {
                
                $menu = $this->db->query("SELECT * FROM shmyde_design_main_menu WHERE shmyde_design_category_id = 2 AND id = ".$option_dependent_menu->shmyde_design_main_menu_id)->row();
                
                array_push($result, $menu);
            }
            
        }
                
        return $result;
    }


    /**
     * This function deletes a menu given the menu id
     * @param type $id The id of the menu being deleted
     */
    public function delete_menu($id){

        $this->db->query('DELETE from shmyde_design_main_menu where id = '.$id);
    }
    
    /**
     * This function creates a menu given its name and the associated
     * product id
     * @param type $name The name of the menu
     * @param type $product_id The product id to which this menu belongs
     * @return boolean
     */
    public function create_menu($name, $product_id, $category_id, $mixed_fabric_support, $support_inner_contrast, $is_back_menu, $is_independent){
    	
    	$insert_id = $this->get_table_next_id("shmyde_design_main_menu");
		
	$sql = "INSERT 
            INTO 
            shmyde_design_main_menu 
            (id, name, shmyde_product_id, shmyde_design_category_id, mixed_fabric_support, inner_contrast_support, is_back_menu, is_independent) 
            VALUES (
            ".$insert_id." , "
                . "".$this->db->escape($name).", "
                . "".$product_id.", "
                . "".$category_id.", "
                . "".$this->db->escape($mixed_fabric_support).", "
                . "".$this->db->escape($support_inner_contrast).", "
                . "".$is_back_menu.", ".$is_independent.")";

        $this->db->query($sql);

        return true;
    }
    
    /**
     * This function edits a menu and changes its name and product
     * @param type $id
     * @param type $name
     * @param type $product_id
     * @return boolean
     */
    public function edit_menu($id, 
            $name, 
            $product_id, 
            $category_id, 
            $mixed_fabric_support, 
            $support_inner_contrast, 
            $is_back_menu,
            $is_independent){
    	
        $sql = "UPDATE "
                . "shmyde_design_main_menu "
                . "SET "
                . "is_back_menu = ".$is_back_menu.", "
                . "name = ".$this->db->escape($name).", "
                . "shmyde_product_id = ".$product_id.", "
                . "shmyde_design_category_id = ".$category_id.", "
                . "mixed_fabric_support = ".$this->db->escape($mixed_fabric_support).", "
                . "inner_contrast_support = ".$support_inner_contrast.", is_independent = ".$is_independent." "
                . "WHERE id = ".$id;

        $this->db->query($sql);
        
        if($is_independent == 1)
        {           
            $this->db->query("DELETE from shmyde_option_dependent_menu WHERE shmyde_design_main_menu_id = ".$id);
        }

        return true;

    }
   
    //============================================================ OPTION ====================================================================
    
    /**
     * This funtion gets an option as a json string and sends it to the front end
     * @param type $id
     * @return type
     */
    public function get_json_option($id){
		 
        $sql = "SELECT * from shmyde_design_option where id = ".$id;

        $query = $this->db->query($sql);

        $row = $query->row();
        
        $option_data = new stdClass();
        
        $option_data->id = $row->id;
        
        $option_data->name = $row->name;
        
        $option_data->description = $row->description;
        
        $option_data->images = array();
                               
        $option['image_data'] = $this->get_option_image_data($row->id);
        
        foreach ($option['image_data']['images'] as $image) {
            
            $image_data = new stdClass();
            
            $image_data->fabric_id = -1;
            
            $image_data->id = $image['id'];
            
            $image_data->item_id = $image['item_id'];
            
            $image_data->name = $image['name'];
            
            $image_data->is_inner = $image['is_inner'];
            
            $image_data->is_back_image = $image['is_back_image'];
            
            $image_data->depth = $image['depth'];
            
            $image_data->pos_x = $image['pos_x'];
            
            $image_data->pos_y = $image['pos_y'];
            
            $image_data->buttons = array();
                        
            $option_data->images[$image['id']] = $image_data;
                       
            $image_buttons = $this->get_style_image_button($image['id']);
                        
            foreach ($image_buttons->result() as $button) {
                
                $button_data = new stdClass();
                
                $button_data->id = $button->id;
                
                $button_data->image_name = $button->image_name;
                
                $button_data->pos_x = $button->pos_x;
                
                $button_data->pos_y = $button->pos_y;
                
                $button_data->item_id = $image['item_id'];
                
                $button_data->depth = $button->depth;

                $image_data->buttons[$button->id] = $button_data;
            }
            
            
        }
        
        return json_encode($option_data);
    }
    
    /**
     * This function gets the options that are associated with the given submenu
     * @param type $menu_id 
     * @return type
     */
    public function get_json_menu_options($menu_id){

        $query = $this->get_menu_options($menu_id);

        $options_array = Array();

        foreach($query->result() as $row){

            $options_array[$row->id]['id'] = $row->id;

            $options_array[$row->id]['name'] = $row->name;

            $options_array[$row->id]['price'] = $row->price;

            $options_array[$row->id]['description'] = $row->description;

            $options_array[$row->id]['is_default'] = $row->is_default;
            
            $options_array[$row->id]['thumbnail'] = $row->thumbnail;

            $image_data = $this->get_option_image_data($row->id);

            $options_array[$row->id]['image_data'] = $image_data;


        }

        return json_encode($options_array);
    }
    
    /**
     * This function gets all options with thier product, menu and submenu properties
     * @return type
     */
    public function get_all_options_extended()
    {

        $sql = "SELECT 
        shmyde_design_option.*, 
        shmyde_product.id as product_id, shmyde_product.name as product_name,
        shmyde_design_main_menu.id as menu_id, shmyde_design_main_menu.name as menu_name 
        FROM
        shmyde_design_option, shmyde_product, shmyde_design_main_menu
        WHERE
        shmyde_design_option.shmyde_design_main_menu_id = shmyde_design_main_menu.id AND 
        shmyde_design_main_menu.shmyde_product_id = shmyde_product.id";

        $query = $this->db->query($sql);

        return $query;
    }
    
    /**
     * This function returns an array of all the dependent
     * menu ID's of an option
     * @param type $option_id
     * @return type
     */
    public function get_option_dependent_menus($option_id){
        
        $result = $this->db->query("SELECT * from shmyde_option_dependent_menu WHERE shmyde_design_option_id = ".$option_id);
        
        $result_array = array();
        
        foreach ($result->result() as $menu) {
            
            $result_array[$menu->shmyde_design_main_menu_id] = $menu->shmyde_design_main_menu_id;
            
        }
        
        return $result_array;
    }
    
    /**
     * This function deletes all dependent menus associated with an option
     * @param type $option_id
     */
    public function delete_option_dependent_menus($option_id){
        
        $this->db->query("DELETE from shmyde_option_dependent_menu WHERE shmyde_design_option_id = ".$option_id);

    }
    
    /**
     * THis function adds a new option dependent menu
     * @param type $option_id
     * @param type $menu_id
     */
    public function add_option_dependent_menu($option_id, $menu_id)
    {
        
        $insert_id = $this->get_table_next_id("shmyde_option_dependent_menu");
        
        $this->db->query("INSERT INTO "
                . "shmyde_option_dependent_menu(id, shmyde_design_main_menu_id, shmyde_design_option_id)"
                . " VALUES(".$insert_id.", ".$menu_id.", ".$option_id.")");
    }
    
    


    /**
     * This funtion gets a specific option based on an id
     * @param type $id The option id
     * @return type
     */
    public function get_option($id){

        $sql = "SELECT 
        shmyde_design_option.*, 
        shmyde_product.id as product_id, shmyde_product.name as product_name,
        shmyde_design_main_menu.id as menu_id, shmyde_design_main_menu.name as menu_name 
        FROM
        shmyde_design_option, shmyde_product, shmyde_design_main_menu
        WHERE
        shmyde_design_option.shmyde_design_main_menu_id = shmyde_design_main_menu.id AND 
        shmyde_design_main_menu.shmyde_product_id = shmyde_product.id AND shmyde_design_option.id = ".$id;

        $query = $this->db->query($sql);

        return $query->row();
    }
    
    /**
     * This function deletes an option
     * @param type $id The id of the option to be deleted
     */
    public function delete_option($id){
        
        $design_image_sql = "SELECT name FROM shmyde_images where item_id = ".$id;
        
        $design_query = $this->db->query($design_image_sql);
        
        foreach ($design_query->result() as $row){
            
            $image_path = ASSETS_DIR_PATH.'images/design/'.$row->name;
            
            if(file_exists($image_path)){
                
                unlink($image_path);
            }
        }
        
        $thumbnail_image_sql = "SELECT name FROM shmyde_option_thumbnail where item_id = ".$id;
        
        $thumbnail_query = $this->db->query($thumbnail_image_sql);
        
        foreach ($thumbnail_query->result() as $row){
            
            $image_path = ASSETS_DIR_PATH.'images/design/thumbnail/'.$row->name;
            
            if(file_exists($image_path)){
                
                unlink($image_path);
            }
        }
        
	$this->delete('shmyde_design_option', $id);        
    }

    public function reset_option_defaults()
    {
    	$this->db->update('shmyde_design_option', { 'is_default' => 0 });
    }
	
    
    /**
     * This function gets the images associated with an option
     * @param type $option_id The id of the option
     * @return type
     */
    private function get_option_image_data($option_id){
        
        $result = Array();
        
        $image_data = Array();
        
        $thumbnail_data = Array();
        
        $sql = "SELECT * from shmyde_images where item_id = ".$option_id;
        
        $option_images = $this->db->query($sql);
                    
        foreach ($option_images->result() as $option_image){

            $image_data[$option_image->id]['id'] = $option_image->id;
            $image_data[$option_image->id]['item_id'] = $option_image->item_id;
            $image_data[$option_image->id]['name'] = $option_image->name;
            $image_data[$option_image->id]['depth'] = $option_image->depth;
            $image_data[$option_image->id]['pos_x'] = $option_image->pos_x;
            $image_data[$option_image->id]['pos_y'] = $option_image->pos_y;
            $image_data[$option_image->id]['is_inner'] = $option_image->is_inner;
            $image_data[$option_image->id]['is_back_image'] = $option_image->is_back_image;

        }
        
        $thumbnail_sql = "SELECT * from shmyde_option_thumbnail where item_id = ".$option_id;
        
        $thumbnail_images = $this->db->query($thumbnail_sql);
                    
        foreach ($thumbnail_images->result() as $thumbnail_image){

            $thumbnail_data['name'] = $thumbnail_image->name;
            $thumbnail_data['depth'] = $thumbnail_image->depth;

        }
        
        $result['images'] = $image_data;
        
        $result['thumbnail'] = $thumbnail_data;
        
        return $result;
        
    }
       
    
    //============================================================ PRODUCT FABRIC ====================================================================
    
    /**
     * This function gets all fabric menus 
     */
    public function get_fabric_menus(){
        
        $fabric_menus = 
                $this->db->query('SELECT shmyde_design_main_menu.* from shmyde_design_main_menu, shmyde_design_category '
                        . 'WHERE shmyde_design_main_menu.shmyde_design_category_id = shmyde_design_category.id AND shmyde_design_category.id = 1');
        
        return $fabric_menus;
    }
    
    /**
     * This function returns all fabrics associated with the different fabric menus
     * @param type $product_id
     * @return type
     */
    public function get_product_submenu_fabrics(){
        
        $product_fabric_submenus = $this->db->query("SELECT * FROM shmyde_product_submenu_fabric");
        
        return $product_fabric_submenus;
    }
    
    /**
     * Gets all the fabrics related to a specific product
     * @return array
     */
    public function get_all_design_product_fabrics($product_id){
        
        $sql = 'SELECT DISTINCT shmyde_fabrics.id AS fabric_id, shmyde_fabrics.name AS fabric_name, shmyde_fabric_images.* FROM shmyde_fabrics LEFT OUTER JOIN shmyde_fabric_images ON '
                . 'shmyde_fabrics.id = shmyde_fabric_images.item_id INNER JOIN shmyde_product_submenu_fabric ON '
                . 'shmyde_fabrics.id = shmyde_product_submenu_fabric.shmyde_fabric_id INNER JOIN shmyde_product ON '
                . 'shmyde_product.id = shmyde_product_submenu_fabric.shmyde_product_id AND '
                . 'shmyde_product.id = '.$product_id;
        
        $query = $this->db->query($sql);
        
        $result = array();
        
        foreach ($query->result() as $value) {
            
            array_push($result, $value);
            
        }
        
        return $result;
    }
    
    public function get_product_submenu_fabric_images($product_id, $submenu_id){
        
        $sql = 'SELECT DISTINCT shmyde_fabrics.id AS fabric_id, shmyde_fabrics.name AS fabric_name, shmyde_fabric_images.* FROM shmyde_fabrics LEFT OUTER JOIN shmyde_fabric_images ON '
                . 'shmyde_fabrics.id = shmyde_fabric_images.item_id INNER JOIN shmyde_product_submenu_fabric ON '
                . 'shmyde_fabrics.id = shmyde_product_submenu_fabric.shmyde_fabric_id INNER JOIN shmyde_product ON '
                . 'shmyde_product.id = shmyde_product_submenu_fabric.shmyde_product_id INNER JOIN shmyde_design_main_menu ON '
                . 'shmyde_design_main_menu.id = shmyde_product_submenu_fabric.shmyde_submenu_id AND '
                . 'shmyde_product.id = '.$product_id.' AND shmyde_design_main_menu.id = '.$submenu_id;
        
        $query = $this->db->query($sql);
        
        $result = array();
        
        foreach ($query->result() as $value) {
            
            array_push($result, $value);
            
        }
        
        return $result;
    }


    public function save_product_submenu_fabric($fabric_id, $product_id, $fabric_submenu_id){
        
        $id = $this->get_table_next_id("shmyde_product_submenu_fabric");
        
        $this->db->query('INSERT INTO shmyde_product_submenu_fabric(id, shmyde_product_id, shmyde_fabric_id, shmyde_submenu_id)'
                . 'VALUES('.$id.', '.$product_id.', '.$fabric_id.', '.$fabric_submenu_id.' )');
    }
    
    public function remove_product_submenu_fabric($fabric_id){
        
        $this->db->query("DELETE FROM shmyde_product_submenu_fabric WHERE shmyde_fabric_id = ".$fabric_id);
    }


    public function save_fabric($id, $name){
        
        $this->db->query("INSERT INTO shmyde_fabrics(id, name) VALUES (".$id.", ".$this->db->escape($name).")");
    }

    public function get_all_product_fabrics_with_submenus(){
        
        $sql = "SELECT "
                . "shmyde_product.name AS product_name, shmyde_product.id AS product_id, shmyde_design_main_menu.name as mainmenu_name, shmyde_design_main_menu.id as mainmenu_id, shmyde_fabrics.name as fabric_name, shmyde_fabrics.id as fabric_id, shmyde_fabric_images.name as fabric_image_name "
                . "FROM "
                . "shmyde_fabrics LEFT OUTER JOIN shmyde_product_submenu_fabric ON shmyde_product_submenu_fabric.shmyde_fabric_id = shmyde_fabrics.id "
                . "LEFT OUTER JOIN shmyde_product ON shmyde_product_submenu_fabric.shmyde_product_id = shmyde_product.id "
                . "LEFT OUTER JOIN shmyde_design_main_menu ON shmyde_design_main_menu.id = shmyde_product_submenu_fabric.shmyde_submenu_id "
                . "INNER JOIN shmyde_fabric_images ON shmyde_fabrics.id = shmyde_fabric_images.item_id "
                . "ORDER BY shmyde_fabrics.id DESC, shmyde_product.id DESC, shmyde_design_main_menu.id DESC";
        
        
        $result = array();
        
        $fabrics = $this->db->query($sql);
        
        foreach ($fabrics->result() as $value) {
            
            if(!isset($result[$value->fabric_id])){
                
                $result[$value->fabric_id] = array();
            }
            
            array_push($result[$value->fabric_id], $value);
            
        }
        
        return $result;
        
    }
    
    public function get_product_default_fabric($product_id){
        
        $sql = "SELECT shmyde_fabric_images.* FROM shmyde_product LEFT OUTER JOIN shmyde_fabric_images "
                . "ON shmyde_product.default_fabric_id = shmyde_fabric_images.item_id WHERE shmyde_product.id = ".$product_id;
        
        return $this->db->query($sql)->row();
    }


    public function get_product_fabric($fabric_id){
        
        $sql = 'SELECT shmyde_fabrics.id AS fabric_id, shmyde_fabrics.name AS fabric_name, shmyde_fabric_images.* FROM shmyde_fabrics LEFT OUTER JOIN shmyde_fabric_images ON '
                . 'shmyde_fabrics.id = shmyde_fabric_images.item_id WHERE shmyde_fabrics.id = '.$fabric_id;
        
        return $this->db->query($sql)->row();
    }
    
     public function delete_product_fabric($fabric_id){
        
        //Get the fabric to get the image name
        $fabric = $this->get_product_fabric($fabric_id);
        
        $image_path = ASSETS_DIR_PATH.'images/product/fabric/'.$fabric->name;
            
        if(file_exists($image_path)){
            
            // Delete the image from directory
            unlink($image_path);
        }
        
        // Delete the fabric
        $this->db->query("DELETE FROM shmyde_fabrics WHERE id = ".$fabric_id);
        
        // Delete the fabric images
        $this->db->query("DELETE FROM shmyde_fabric_images WHERE item_id = ".$fabric_id);
        
        // Delete the fabric from relations table
        $this->remove_product_submenu_fabric($fabric_id);
        
        
    }
    
    /**
     * Returns an array of all buttons in the database
     * @return array
     */
    public function get_buttons(){
        
        $result = array();
        
        $buttons = $this->db->query('SELECT * from shmyde_buttons');
        
        foreach ($buttons->result() as $button) {
            
            $button_data = new stdClass();
            
            $button_data->id = $button->id;
            $button_data->name = $button->name;            
            $button_data->image_name = $button->image_name;
            $button_data->design_image_name = $button->design_image_name;
            
            array_push($result, $button_data);
        }
        
        return $result;

    }
    
    /**
     * Returns an array of all threads in the database
     * @return array
     */
    public function get_threads(){
        
        $result = array();
        
        $threads = $this->db->query('SELECT * from shmyde_threads');
        
        foreach ($threads->result() as $thread) {
            
            $thread_data = new stdClass();
            
            $thread_data->id = $thread->id;
            $thread_data->image_name = $thread->image_name;            
            $thread_data->color = $thread->color;
            
            array_push($result, $thread_data);
        }
        
        return $result;

    }
    
    /**
     * THis function gets a specific thread
     * @param type $thread_id
     * @return type
     */
    public function get_thread($thread_id){
        
        return $this->db->query('SELECT * from shmyde_threads WHERE id = '.$thread_id)->row();
    }
    
    public function get_button($button_id){
        
        return $this->db->query('SELECT * from shmyde_buttons WHERE id = '.$button_id)->row();
    }


    /**
     * This function creates a thread
     * @param type $image_name
     * @param type $color
     * @return boolean
     */
    public function create_thread($image_name, $color){
        
        $id = $this->get_table_next_id("shmyde_threads");
        
        $this->db->query("INSERT INTO shmyde_threads(id, image_name, color) VALUES (".$id.", ".$this->db->escape($image_name).", ".$this->db->escape($color).")");
        
        return true;
    }
    
    public function create($table_name, $data)
    {
        if(isset($data['id']))
        {
            $query = $this->db->get_where($table_name, array('id' => $data['id']));
            $count = $query->num_rows(); 
            if($count === 0)
            {
                $this->db->insert($table_name, $data);
                return $this->db->insert_id();
            }
            else
            {
                $this->db->where('id', $data['id']);
                $this->db->update($table_name, $data);
                return $data['id'];
            }
        }
        else
        {
            $this->db->insert($table_name, $data);
            return $this->db->insert_id();
        }
        
        
    }
    
    public function create_button($image_name, $design_image_name, $name){
        
        $id = $this->get_table_next_id("shmyde_buttons");
        
        $this->db->query("INSERT INTO shmyde_buttons(id, image_name, design_image_name, name) "
                . "VALUES (".$id.", ".$this->db->escape($image_name).", ".$this->db->escape($design_image_name).", ".$this->db->escape($name).")");
        
        return true;
    }
    
    public function delete_thread($thread_id){
        
        //Get the thead to get the image name
        $fabric = $this->get_thread($thread_id);
        
        $image_path = ASSETS_DIR_PATH.'images/threads/'.$fabric->image_name;
            
        if(file_exists($image_path)){
            
            // Delete the image from directory
            unlink($image_path);
        }
        
        // Delete the thread
        $this->db->query("DELETE FROM shmyde_threads WHERE id = ".$thread_id);
        
    }
    
    public function delete_button($button_id){
        
        //Get the button to get the image name
        $button = $this->get_button($button_id);
        
        $image_path = ASSETS_DIR_PATH.'images/buttons/'.$button->image_name;
        
        $large_image_path = ASSETS_DIR_PATH.'images/buttons/'.$button->design_image_name;
            
        if(file_exists($image_path)){
            // Delete the image from directory
            unlink($image_path);
        }
        
        if(file_exists($large_image_path)){
            // Delete the image from directory
            unlink($large_image_path);
        }
        
        // Delete the thread
        $this->db->query("DELETE FROM shmyde_buttons WHERE id = ".$button_id);
        
    }
    
    public function edit_thread($thread_id, $color){
            
        $this->db->query("UPDATE shmyde_threads SET color = ".$this->db->escape($color)." WHERE id = ".$thread_id);
        
    }
    
    public function edit_button($button_id, $name){
            
        $this->db->query("UPDATE shmyde_buttons SET name = ".$this->db->escape($name)." WHERE id = ".$button_id);
        
    }

    public function get_option_design_data($product_id){
        
        $result = array();
        
        $result['front_images'] = $this->get_base_images($product_id, 'front');
        
        $result['back_images'] = $this->get_base_images($product_id, 'back');
                   
        $menus = $this->get_product_menus($product_id);

        foreach ($menus->result() as $menu) {
            
            if($menu->shmyde_design_category_id != 2){
                
                continue;
            }
            
            $menu_data = new MenuData($menu->id, $menu->name, $menu->mixed_fabric_support, $menu->inner_contrast_support, $menu->is_back_menu);
            
            $options = $this->get_menu_options($menu->id);

            foreach ($options->result() as $option) {

                $option_data = new OptionData($option->id, $option->name, $option->description);

                if($option->is_default){

                    $menu_data->option_id = $option->id;

                    $images = $this->get_images($option->id, 'shmyde_images');

                    foreach ($images as $image) {

                        $image_data = new ImageData($image['id'], $image['item_id'], $image['name'], $image['is_inner'], $image['is_back_image'], $image['depth'], $image['pos_x'], $image['pos_y']);
                                                
                        $image_buttons = $this->get_style_image_button($image['id']);
                        
                        foreach ($image_buttons->result() as $button) {
                            
                            $image_data->buttons[$button->id] = new ButtonData($button->id, $button->image_name, $button->pos_x, $button->pos_y, $button->depth, $image['item_id']);
                        }
                        
                        $option_data->images[$image['id']] = $image_data;
                    }

                    $menu_data->option = $option_data;
                }
                    
            }
            
            $result['design'][$menu->id] = $menu_data;

        }
                    
        return $result;
                
    }
    
    public function get_style_image_button($image_id){
           
        $sql = "SELECT shmyde_style_buttons.*, shmyde_buttons.image_name FROM shmyde_buttons, shmyde_style_buttons WHERE shmyde_style_buttons.button_type = shmyde_buttons.id AND shmyde_design_option_id = ".$image_id;
        
        $buttons = $this->db->query($sql);
        
        return $buttons;
    }
    
    public function get_product_image_button($image_id){
        
        $sql = "SELECT shmyde_product_buttons.*, shmyde_buttons.image_name FROM shmyde_buttons, shmyde_product_buttons WHERE shmyde_product_buttons.button_type = shmyde_buttons.id AND shmyde_product_id = ".$image_id;
        
        $buttons = $this->db->query($sql);
        
        return $buttons;
    }

    
    public function load_style_buttons($option_id){
        
        $buttons = $this->db->query("SELECT shmyde_style_buttons.*, shmyde_buttons.image_name from shmyde_style_buttons, shmyde_buttons WHERE shmyde_design_option_id = ".$option_id." AND shmyde_buttons.id = shmyde_style_buttons.button_type");
        
        $output = array();
        
        foreach ($buttons->result() as $button) {
            
            $output[$button->id]["id"] = $button->id;
            $output[$button->id]["pos_x"] = $button->pos_x;
            $output[$button->id]["pos_y"] = $button->pos_y;
            $output[$button->id]["image_name"] = $button->image_name;
            $output[$button->id]["name"] = $button->name." ".$button->id ;
            
        }
        
        return $output;
        
    }
    
    public function load_product_buttons($product_id, $side){
        
        $buttons = $this->db->query("SELECT shmyde_product_buttons.*, shmyde_buttons.image_name from shmyde_product_buttons, shmyde_buttons WHERE shmyde_product_id = ".$product_id." AND side = ".$side." AND shmyde_buttons.id = shmyde_product_buttons.button_type");
        
        $output = array();
        
        foreach ($buttons->result() as $button) {
            
            $output[$button->id]["id"] = $button->id;
            $output[$button->id]["pos_x"] = $button->pos_x;
            $output[$button->id]["pos_y"] = $button->pos_y;
            $output[$button->id]["image_name"] = $button->image_name;
            $output[$button->id]["name"] = $button->name." ".$button->id ;
            
        }
        
        return $output;
    }

    //============================================================ CATEGORIES ====================================================================
    
    public function get_categories(){
        
        $categories = $this->db->query('SELECT * from shmyde_design_category');
        
        return $categories;
    }
    
    public function get_category($id){
        
        $category = $this->db->query('SELECT * from shmyde_design_category where id = '.$id);
        
        return $category->row();
    }
    
    /**
     * Gets the button associated with the given product
     * @param type $productID
     * @return type
     */
    public function GetProductDefaultButton($productID){
        
        $button = $this->db->query("SELECT shmyde_buttons.* from shmyde_product, shmyde_buttons WHERE shmyde_buttons.id = shmyde_product.default_button_id AND shmyde_product.id = ".$productID);
        
        return $button->row();
    }
    
    /**
     * Gets the thread associated with the given product
     * @param type $productID
     * @return type
     */
    public function GetProductDefaultThread($productID){
        
        $thread = $this->db->query("SELECT shmyde_threads.* from shmyde_product, shmyde_threads WHERE shmyde_threads.id = shmyde_product.default_thread_id AND shmyde_product.id = ".$productID);
        
        return $thread->row();
    }

    //============================================================ END CATEGORIES ====================================================================
    
    public function get_measurements(){
        
        $measurements = $this->db->query('SELECT shmyde_measurement.*, shmyde_product.name as product_name from shmyde_measurement, shmyde_product where shmyde_product.id = shmyde_measurement.shmyde_product_id');
        
        return $measurements;
    }
    
    public function get_product_measurements($product_id){
        
        $measurements = $this->db->query('SELECT shmyde_measurement.*, shmyde_product.name as product_name from shmyde_measurement, shmyde_product where shmyde_product.id = shmyde_measurement.shmyde_product_id AND shmyde_product_id = '.$product_id);
        
        return $measurements;
    }
    
    public function get_measurements_data($product_id){
        
        $result = array();
        
        $measurements = $this->db->query('SELECT shmyde_measurement.*, shmyde_product.name as product_name from shmyde_measurement, shmyde_product where shmyde_product.id = shmyde_measurement.shmyde_product_id AND shmyde_product_id = '.$product_id);
        
        foreach ($measurements->result() as $measurement) {
            
            $measurement_data = new MeasurementData($measurement->id, $measurement->name, $measurement->default_value, $measurement->description, $measurement->youtube_video_link, $measurement->product_name);
            
            $result[$measurement->id] = $measurement_data;
        }
        
        return $result;
    }
    
    public function get_product_json_measurements($product_id){
        
        $json_result = array();
        
        $measurements = $this->db->query('SELECT shmyde_measurement.*, shmyde_product.name as product_name from shmyde_measurement, shmyde_product where shmyde_product.id = shmyde_measurement.shmyde_product_id AND shmyde_product_id = '.$product_id);
        
        foreach ($measurements->result() as $measurement) {
            
            $json_result[$measurement->id]["id"] = $measurement->id;
            $json_result[$measurement->id]["name"] = $measurement->name;
            $json_result[$measurement->id]["description"] = $measurement->description;
            $json_result[$measurement->id]["default_value"] = $measurement->default_value;
            
        }
        
        return json_encode($json_result);
    }
    
    public function get_json_measurement($id){
        
        $result = Array();
        
        $measurement = $this->get_measurement($id);
        
        $result['id'] = $measurement->id;
        $result['name'] = $measurement->name;
        $result['description'] = $measurement->description;
        $result['youtube_link'] = str_replace("watch?v=", "embed/", $measurement->youtube_video_link) ;
        
        return json_encode($result);
        
    }

    public function get_measurement($id){
        
        $measurement = $this->db->query('SELECT * from shmyde_measurement where id = '.$id);
        
        return $measurement->row();
    }
    
    public function edit_measurement($id, $name, $product, $description, $default_value, $youtube_video){
        
        $sql = 'UPDATE shmyde_measurement set '
                . 'name = '.$this->db->escape($name).', shmyde_product_id = '.$product.', '
                . 'description = '.$this->db->escape($description).', default_value = '.$default_value.', '
                . 'youtube_video_link = '.$this->db->escape($youtube_video).' WHERE id = '.$id;
        
        $this->db->query($sql);
        
        return true;
    }
    
    public function delete_measurement($id) {
        
        $sql = 'DELETE from shmyde_measurement WHERE id = '.$id;
        
        $this->db->query($sql);
        
        return true;
        
    }

    public function create_measurement($name, $product, $description, $default_value, $youtube_video){
        
        $id = $this->get_table_next_id('shmyde_measurement');
        
        $sql = 'INSERT INTO shmyde_measurement(id, name, shmyde_product_id, description, default_value, youtube_video_link ) '
                . 'VALUES ('.$id.', '.$this->db->escape($name).', '.$this->db->escape($product).', '.$this->db->escape($description).', '.$this->db->escape($default_value).', '.$this->db->escape($youtube_video).')';
        
        $this->db->query($sql);
        
        return true;
    }
    
    //============================================================ MEASUREMENTS ====================================================================
    
    
    //============================================================ BUTTONS ====================================================================
   
    public function create_new_style_button($image_id){
       
        $id = $this->get_table_next_id("shmyde_style_buttons");
        
        $this->db->query("INSERT INTO shmyde_style_buttons(id, name, button_type, shmyde_design_option_id)"
                . " VALUES (".$id.", 'BUTTON', 0, ".$image_id.")");
        
        $inserted_button = $this->db->query("SELECT shmyde_style_buttons.*, shmyde_buttons.image_name from shmyde_style_buttons, shmyde_buttons WHERE shmyde_style_buttons.id = ".$id." AND shmyde_buttons.id = shmyde_style_buttons.button_type")->row();
        
        $output = array();
        
        $output["id"] = $inserted_button->id;
        $output["image_name"] = $inserted_button->image_name;
        
        return $output;
        
    }
    
    public function create_new_product_button($image_id, $side){
        
        $id = $this->get_table_next_id("shmyde_product_buttons");
        
        $this->db->query("INSERT INTO shmyde_product_buttons(id, name, button_type, shmyde_product_id, side)"
                . " VALUES (".$id.", 'BUTTON', 0, ".$image_id.", ".$side.")");
        
        $inserted_button = $this->db->query("SELECT shmyde_product_buttons.*, shmyde_buttons.image_name FROM shmyde_product_buttons, shmyde_buttons WHERE shmyde_product_buttons.id = ".$id." AND shmyde_buttons.id = shmyde_product_buttons.button_type")->row();
        
        $output = array();
        
        $output["id"] = $inserted_button->id;
        $output["image_name"] = $inserted_button->image_name;
        
        return $output;
    }
    
    public function delete_style_button($image_id){
        
        return $this->db->query("DELETE FROM shmyde_style_buttons WHERE id = ".$image_id);
    }
    
    public function delete_product_button($image_id, $side){
        
        return $this->db->query("DELETE FROM shmyde_product_buttons WHERE id = ".$image_id." AND side = ".$side);
    }
    
    public function save_style_button($image_id, $pos_x, $pos_y){
        
        return $this->db->query("UPDATE shmyde_style_buttons SET pos_x = ".$pos_x.", pos_y = ".$pos_y." WHERE id = ".$image_id);
    }
    
    public function save_product_button($image_id, $side, $pos_x, $pos_y){
        
        return $this->db->query("UPDATE shmyde_product_buttons  SET pos_x = ".$pos_x.", pos_y = ".$pos_y." WHERE id = ".$image_id." AND side = ".$side);
    }


    //============================================================ OTHER ====================================================================
  
    /**
     * This function saves the current user design
     * @param type $user_id
     * @param type $userDesign
     */
    public function SaveTmpUserDesign($data)
    {        
        $this->db->where('session_id', $data['session_id']);
        $this->db->delete(USER_TMP_DESIGN_TABLE);
        $this->db->insert(USER_TMP_DESIGN_TABLE, $data);
    }
    
    public function GetTmpUserDesign($session_id)
    {
        $this->db->select('*');
        $this->db->from(USER_TMP_DESIGN_TABLE);
        $this->db->where('session_id', $session_id);
        $user_product_design = $this->db->get()->row();
        $this->db->reset_query();
        
        if(isset($user_product_design))
        {
            // Delete temporal design after getting it
            return $user_product_design->product_design;
        }
        
        return null;
         
    }




    public function get_images($id, $table_name){
        
        $image_query = $this->db->query('SELECT * from '.$table_name.' where item_id = '.$id);
        
        $result = Array();
        
        $i = 0;
        
        if($image_query->num_rows() > 0){
            
            foreach($image_query->result() as $row){
                
                $result[$i]['id'] = $row->id;
                $result[$i]['item_id'] = $row->item_id;
                $result[$i]['name'] = $row->name;
                $result[$i]['depth'] = $row->depth;
                $result[$i]['pos_x'] = $row->pos_x;
                $result[$i]['pos_y'] = $row->pos_y;
                $result[$i]['is_inner'] = $row->is_inner;
                $result[$i]['is_back_image'] = $row->is_back_image;
                $result[$i]['fabric_id'] = -1;
                
                $i++;
                
            }
                       
        }
        
        return $result;
    }
    
    public function get_images_2($id, $table_name){

        $image_query = $this->db->query('SELECT * from '.$table_name.' where item_id = '.$id);
        
        $result = Array();
                
        if($image_query->num_rows() > 0){
            
            foreach($image_query->result() as $row){
                
                $result[$row->id]['id'] = $row->id;
                $result[$row->id]['item_id'] = $row->item_id;
                $result[$row->id]['name'] = $row->name;
                $result[$row->id]['depth'] = $row->depth;
                $result[$row->id]['pos_x'] = $row->pos_x;
                $result[$row->id]['pos_y'] = $row->pos_y;
                $result[$row->id]['is_inner'] = $row->is_inner;
                $result[$row->id]['is_back_image'] = $row->is_back_image;
                $result[$row->id]['fabric_id'] = -1;
                                
            }
                       
        }
        
        return $result;
    }
    
    public function get_image($id, $table_name){

        $image_query = $this->db->query('SELECT * from '.$table_name.' where item_id = '.$id);
        
        $result = Array();
                
        if($image_query->num_rows() > 0){
            
            foreach($image_query->result() as $row){
                
                $result[$row->id]['id'] = $row->id;
                $result[$row->id]['item_id'] = $row->item_id;
                $result[$row->id]['name'] = $row->name;
                $result[$row->id]['depth'] = $row->depth;
                $result[$row->id]['pos_x'] = $row->pos_x;
                $result[$row->id]['pos_y'] = $row->pos_y;
                $result[$row->id]['is_inner'] = $row->is_inner;
                $result[$row->id]['is_back_image'] = $row->is_back_image;
                $result[$row->id]['fabric_id'] = -1;
                                
            }
                       
        }
        if(sizeof($result) > 0){
            
            return current($result);
        }
        
        return $result;
    }
    
    public function save_image($option_id, $image_id, $image_name, $table_name, $pos_x, $pos_y, $is_inner, $depth, $is_back_image){
        
        if($is_inner == null || !isset($is_inner)){
            
            $is_inner = 0;
        }
        
        $sql = '';
        
        if($this->image_exists($image_id, $option_id, $table_name)){
            
            $sql = "UPDATE ".$table_name." SET item_id = ".$this->db->escape($option_id).", name = ".$this->db->escape($image_name).", pos_x = ".$this->db->escape($pos_x).", pos_y = ".$this->db->escape($pos_y).", depth = ".$this->db->escape($depth).", is_inner = ".$this->db->escape($is_inner).", is_back_image = ".$is_back_image." WHERE id = ".$image_id." AND item_id = ".$option_id;
            
        }
        else{
            
            $sql = "INSERT into ".$table_name." (id, item_id, name, pos_x, pos_y, is_inner, depth, is_back_image) "
                . "VALUES (".$image_id." , ".$option_id.", ".$this->db->escape($image_name).", ".$this->db->escape($pos_x).", ".$this->db->escape($pos_y).", ".$this->db->escape($is_inner).", ".$this->db->escape($depth).", ".$this->db->escape($is_back_image).")";
            
        }
        
        
        
        
        $this->db->query($sql);
    }
    
    /**
     * This function checks if am image aleady exists in the database table. If it already does
     * then it is updated if not, it is created
     * @param type $image_id The ID of the image being checked
     * @param type $table_name The image table
     */
    private function image_exists($image_id, $item_id, $table_name){
        
        $sql = "SELECT id from ".$table_name." WHERE id = ".$image_id." AND item_id = ".$item_id;
        
        $result = $this->db->query($sql);
        
        if($result->num_rows() > 0){
            
            return true;
        }
        
        return false;
        
        
    }

    

    /**
     * This function resets all the default values for the fabrics of a certain
     * submenu specified in the parameter by using a replace sql command in all 
     * fields where the submenu_id appears
     * @param type $submenu_id The submenu for which the default fabric is reset
     */
    public function reset_default($submenu_id){
        
        $reset_sql = "UPDATE shmyde_fabric set sub_menus = replace(sub_menus, '\"submenu_id\":\"".$submenu_id."\",\"is_default\":\"1\"', '\"submenu_id\":\"".$submenu_id."\",\"is_default\":\"0\"')";
        
        $this->db->query($reset_sql);
        
    }
    
    public function set_product_fabric_default($product_id, $new_fabric_id){
        
        $this->db->query('UPDATE shmyde_product SET default_fabric_id = '.$new_fabric_id.' WHERE id = '.$product_id);
    }




    ///This table clears a table given the table name
    public function clear($table_name){
        
        $sql = "delete from ".$table_name;
        $this->db->query($sql);
    }    

    ///This function gets the next id of the table. 
    public function get_table_next_id($table_name){
    	
    	$count_sql = "SELECT max(id) as max_id FROM ".$table_name;	

        $count = $this->db->query($count_sql)->row()->max_id + 1;

        return $count;
    }
    
    ///Every Product has a base front image and a base back image on which other images are
    /// Interposed. This function gets these images using the product ID
    public function get_base_images($product_id, $side = 'front'){
                
        $base_image = Array();
        
        $front_sql = "SELECT * from shmyde_product_".$side."_image where item_id = ".$product_id;
        
        $front_result = $this->db->query($front_sql);
        
        foreach ($front_result->result() as $row){
                                  
            $image = new BaseImageData($row->id, $row->item_id, $row->name, $row->depth, $row->pos_x, $row->pos_y);
            
            $image_buttons = $this->get_product_image_button($row->id);
                        
            foreach ($image_buttons->result() as $button) {

                $image->buttons[$button->id] = new ButtonData($button->id, $button->image_name, $button->pos_x, $button->pos_y, 10, $row->item_id);
            }
            
            $base_image[$row->id] = $image;
            
        }
                
        return $base_image;
    }
    
    ///This function gets the default parameters of the product. The current model 
    ///Needs every option to have a default parameter. 
    /// The object retured is as such $parameters[menu_id][submenu_id][option_parameter] = value;
    public function get_json_parameters($product_id){
        
                
        $design_params = Array();
          
        /**
         * This query will get all the Design Category menus of the specified product 
         */
        $sql = "SELECT * from shmyde_design_main_menu where shmyde_product_id = ".$product_id.' AND shmyde_design_Category_id = 2';
        
       
        $result = $this->db->query($sql);
        
        foreach ($result->result() as $row){
            
            $main_menu_id = $row->id;
                                    
            if(!isset($design_params[$main_menu_id])){
                
                $design_params[$main_menu_id] = Array();
                
                $design_params[$main_menu_id]['image_data'] = Array();
                $design_params[$main_menu_id]['inner_contrast_support'] = $row->inner_contrast_support;
                $design_params[$main_menu_id]['mixed_fabric_support'] = $row->mixed_fabric_support;                       
                $design_params[$main_menu_id]['option_id'] = -1;
                $design_params[$main_menu_id]['option_description'] = '';
                $design_params[$main_menu_id]['option_price'] = '';
                $design_params[$main_menu_id]['option_name'] = '';
                $design_params[$main_menu_id]['default_fabric_id'] = -1;
                $design_params[$main_menu_id]['mix_fabric_id'] = -1;
            }
            
            /**
             * This query loops through all the options of the menus and populate those that have defaults
             */
            $sql = "SELECT * from shmyde_design_option where shmyde_design_main_menu_id = ".$row->id;
            
            $options = $this->db->query($sql);
            
            foreach ($options->result() as $option){
                
                if($option->is_default){
                    
                    $image_data = $this->get_option_image_data($option->id);
                                        
                    $design_params[$main_menu_id]['image_data'] = $image_data;
                    $design_params[$main_menu_id]['option_id'] = $option->id;
                    $design_params[$main_menu_id]['option_description'] = $option->description;
                    $design_params[$main_menu_id]['option_price'] = $option->price;
                    $design_params[$main_menu_id]['option_name'] = $option->name;
                    
                     
                }
                           
            }
             
        }
        
                
        return json_encode($design_params);
    }
    
    ///This function is a helper function that gets the name of an image given the item_id, image_id and the database
    ///table name
    public function get_image_name($option_id, $image_id, $table_name){
        
        $sql = "SELECT name from ".$table_name." WHERE id = ".$image_id." AND item_id = ".$option_id;
        
        $count = $this->db->query($sql)->num_rows();
        
        if($count > 0){
            
            return $this->db->query($sql)->row()->name;
        }
        else{
            
            return "";
        }
    }
    
    ///This function is used to delete an image from a given table given the table name, image_id and the item_id
    public  function delete_image($option_id, $image_id, $table_name){
        
        $sql = "DELETE from ".$table_name." WHERE id = ".$image_id." AND item_id = ".$option_id;
        
        $this->db->query($sql);
    }
    
    
    public function update_table($tableName, $columnName, $newValue, $condition)
    {
        $this->db->query("UPDATE ".$tableName." SET ".$columnName." = ".$this->db->escape($newValue)." WHERE ".$condition);
    }
	
    public function delete($table_name, $id)
    {
    	$this->db->where('id', $id);
	$this->db->delete($table_name);
    }
	
 
}

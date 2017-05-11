<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DesignModel
 *
 * @author beson
 */
class Design_model extends CI_Model 
{
    
    public function __construct()
    {
        parent::__construct();
    }
        
    /**
     * 
     * @param type $object_id
     * @return type
     */
    public function get_object($object_id)
    {
        $this->db->select('*');
        $this->db->from(OPTION_TABLE);
        $this->db->where('id', $object_id);
        $design_object = $this->db->get()->row();
        $this->db->reset_query();
        
        if(isset($design_object))
        {
            return $design_object;
        }
        
        return null;
    }
    
    /**
     * 
     * @param type $image_id
     */
    public function get_image_buttons($image_id)
    {
        $this->db->select('*');
        $this->db->from(OPTION_BUTTON_TABLE);
        $this->db->where('shmyde_design_option_id', $image_id);
        $image_buttons = $this->db->get();
        $this->db->reset_query();
        
        if(isset($image_buttons))
        {
            return $image_buttons;
        }
        
        return null;
    }

    /**
     * 
     * @param type $menu_id
     */
    public function get_menu_options($menu_id) 
    {
        $this->db->select('*');
        $this->db->from(OPTION_TABLE);
        $this->db->where('shmyde_design_main_menu_id', $menu_id);
        $menu_options = $this->db->get();
        $this->db->reset_query();
        
        if(isset($menu_options))
        {
            return $menu_options;
        }
        
        return null;
    }
    
    /**
     * 
     * @param type $object_id
     */
    public function get_option_dependent_menu_ids($object_id)
    {
        $this->db->select('*');
        $this->db->from(OPTION_DEPENDENT_MENU_TABLE);
        $this->db->where('shmyde_design_option_id', $object_id);
        $design_object_dependent_ids = $this->db->get();
        $this->db->reset_query();
        
        if(isset($design_object_dependent_ids))
        {
            return $design_object_dependent_ids;
        }
        
        return null;
    }
    
        /**
     * 
     * @param type $menu_id
     */
    public function get_menu($menu_id)
    {
        $this->db->select('*');
        $this->db->from(MENU_TABLE);
        $this->db->where('id', $menu_id);
        $menu_object = $this->db->get()->row();
        $this->db->reset_query();
        
        if(isset($menu_object))
        {
            return $menu_object;
        }
        
        return null;
    }
    
    public function get_menu_fabrics($menu_id, $product_id) 
    {
        $this->db->select(FABRIC_IMAGES_TABLE.'.*, '.FABRICS_TABLE.'.id as fabric_id');
        $this->db->from(FABRICS_TABLE);
        $this->db->join(FABRIC_IMAGES_TABLE, FABRICS_TABLE.'.id = '.FABRIC_IMAGES_TABLE.'.item_id', 'right');
        $this->db->join(PRODUCT_FABRC_MENU_TABLE, FABRICS_TABLE.'.id = '.PRODUCT_FABRC_MENU_TABLE.'.shmyde_fabric_id');
        $where_clause = array(PRODUCT_FABRC_MENU_TABLE.'.shmyde_product_id' => $product_id, PRODUCT_FABRC_MENU_TABLE.'.shmyde_submenu_id' => $menu_id);
        $this->db->where($where_clause);
        $fabrics = $this->db->get();
        $this->db->reset_query();
        
        if($fabrics != null)
        {
            return $fabrics;
        }
        
        return null;
        
    }
    
    /*
     * 
     */
    public function get_object_images($object_id) 
    {
        $this->db->select('*');
        $this->db->from(OPTION_IMAGE_TABLE);
        $this->db->where('item_id', $object_id);
        $design_object_images = $this->db->get();
        $this->db->reset_query();
        
        if(isset($design_object_images))
        {
            return $design_object_images;
        }
        
        return null;
    }
    
    public function get_object_thumbnail($object_id) 
    {
        $this->db->select('*');
        $this->db->from(OPTION_THUMBNAIL_TABLE);
        $this->db->where('item_id', $object_id);
        $design_object_thumbnail = $this->db->get()->row();
        $this->db->reset_query();
        
        if(isset($design_object_thumbnail))
        {
            return $design_object_thumbnail;    
        }
        
        return null;
    }
    
    
    
    /**
     * 
     * @param type $product_id
     * @return type
     */
    public function get_product($product_id)
    {
        $this->db->select('*');
        $this->db->from(PRODUCT_TABLE);
        $this->db->where('id', $product_id);
        $product_object = $this->db->get()->row();
        $this->db->reset_query();
        
        if(isset($product_object))
        {
            return $product_object;
        }
        
        return null;
    }
    
    /**
     * 
     * @param type $product_id
     */
    public function get_product_menus($product_id)
    {
        $this->db->select('*');
        $this->db->from(MENU_TABLE);
        $this->db->where('shmyde_product_id', $product_id);
        $product_menus = $this->db->get();
        $this->db->reset_query();
        
        if(isset($product_menus))
        {
            return $product_menus;
        }
        
        return null;
    }
    
    public function get_product_measurements($product_id) 
    {
        $this->db->select('*');
        $this->db->from(MEASUREMENTS_TABLE);
        $this->db->where('shmyde_product_id', $product_id);
        $measurements = $this->db->get();
        $this->db->reset_query();
        
        if(isset($measurements))
        {
            return $measurements;
        }
        
        return null;
    }
        
    /**
     * 
     * @return type
     */
    public function get_threads()
    {
        $this->db->select('*');
        $this->db->from(THREADS_TABLE);
        $threads = $this->db->get();
        $this->db->reset_query();
        
        if(isset($threads))
        {
            return $threads;
        }
        
        return null;
    }
    
    /**
     * 
     * @return type
     */
    public function get_buttons()
    {
        $this->db->select('*');
        $this->db->from(BUTTONS_TABLE);
        $buttons = $this->db->get();
        $this->db->reset_query();
        
        if(isset($buttons))
        {
            return $buttons;
        }
        
        return null;
    }
    
    /**
     * 
     * @return type
     */
    public function get_fabrics()
    {
        $this->db->select(FABRIC_IMAGES_TABLE.'.*, '.FABRICS_TABLE.'.id as fabric_id');
        $this->db->from(FABRICS_TABLE);
        $this->db->join(FABRIC_IMAGES_TABLE, FABRICS_TABLE.'.id = '.FABRIC_IMAGES_TABLE.'.item_id', 'right');
        $fabrics = $this->db->get();
        $this->db->reset_query();
        
        if(isset($fabrics))
        {
            return $fabrics;
        }
        
        return null;
    }
    
    /**
     * This function saves the current user design or updates it
     * @param type $user_id
     * @param type $userDesign
     */
    public function SaveUserDesign($user_id, $order_id, $name, $total_price, $designParameters, $frontBase64Image, $backBase64Image)
    {    
        $data = array
        (
            'user_design'       => $designParameters,
            'user_id'           => $user_id,
            'price'             => $total_price,
            'type'             => $name,
            'status'            => 20 // This is the status for saved designs
        );
        
        if($order_id == -1)
        {
            $insert_id = $this->get_next_id(ORDERS_TABLE);
            $data['id'] = $insert_id;
            
            $this->db->insert(ORDERS_TABLE, $data);
            
            $decoded_frontBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $frontBase64Image));
            $decoded_backBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $backBase64Image));
            
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_front.png', $decoded_frontBase64Image);
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_back.png', $decoded_backBase64Image);
            
            return $insert_id;
        }
        else
        {
            $this->db->where('id', $order_id);
            $this->db->update(ORDERS_TABLE, $data);
            
            $decoded_frontBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $frontBase64Image));
            $decoded_backBase64Image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $backBase64Image));
            
            $insert_id = $order_id;
            
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_front.png', $decoded_frontBase64Image);
            file_put_contents(ASSETS_DIR_PATH.'/images/orders/order_'.$insert_id.'_'.$user_id.'_back.png', $decoded_backBase64Image);
            
            return $insert_id;
        }        
    }
    
    /**
     * THis method deletes an order or design
     * @param type $order_id
     */
    public function delete_design($order_id) 
    {
        $this->db->where('id', $order_id);
        $this->db->delete(ORDERS_TABLE);
    }
}

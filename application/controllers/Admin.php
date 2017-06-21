<?php
class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        
        switch ($lang)
        {
            case "fr":
            $current_language = "french";
            break;
            case "en":
            $current_language = "english";
            break;
            default:
            $current_language = "english";
            break;
        }

        $this->lang->load('shmyde', $current_language);
        
        if(!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin'))
        {
            $this->session->set_userdata('last_page', current_url());
                        
            redirect("invalid/invalid_login_user");  
                        
            return;
        }
        
    }
    
    public function button_editor($editor_type, $id)
    {
                
        $this->data['id'] = json_encode($id);
        
        $this->data['title'] = 'Button Editor';
        
        $this->data['cssLinks'] = array('admin');
        
        $this->data['editor_type'] = json_encode($editor_type);
        
        $this->template->load('shmyde','admin/editor.php', $this->data);
    }
    
    public function index(){
        
        $this->data['products'] = $this->admin_model->get_all_products();
        
        $this->data['title'] = ucfirst('product'); 

        $this->lang->load('shmyde', CURRENT_LANGUAGE);

        $this->load->view('pages/header');
                
        $this->template->load('shmyde','admin/product', $this->data);
    }

    public function view($page = 'product', $product_id = -1, $menu_id = -1, $submenu_id = -1)
    {
        if ( ! file_exists(APPPATH.'/views/admin/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }
        
        $this->data['cssLinks'] = array('admin');

        if($page == 'product')
        {
            $this->data['products'] = $this->admin_model->get_all_products();

        }

        if($page == 'menu')
        {
            $this->data['menus'] = $this->admin_model->get_all_menus();
        }

        if($page == 'submenu')
        {
            $this->data['submenus'] = $this->admin_model->get_all_submenus();
        }

        if($page == 'option')
        {
            $this->data['menus'] = $this->admin_model->get_design_menus();

            $this->data['products'] = $this->admin_model->get_all_products();

            $this->data['default_product_id'] = $product_id;

            $this->data['default_menu_id'] = $menu_id;

            $this->data['default_submenu_id'] = $submenu_id;

            $query_options = $this->admin_model->get_all_options_extended();

            $options = Array();

            foreach ($query_options->result() as $row)
            {
                $options[$row->id]['id'] = $row->id;
                $options[$row->id]['name'] = $row->name;
                $options[$row->id]['description'] = $row->description;
                $options[$row->id]['price'] = $row->price;
                $options[$row->id]['product_name'] = $row->product_name;
                $options[$row->id]['menu_name'] = $row->menu_name;
                $options[$row->id]['product_id'] = $row->product_id;
                $options[$row->id]['menu_id'] = $row->shmyde_design_main_menu_id;
                $options[$row->id]['is_default'] = $row->is_default;

            }

            $this->data['options'] = $options;
        }
            
        if($page == 'fabric')
        {
            $this->data['fabrics'] = $this->admin_model->get_all_fabrics();
        }

        if($page == 'measurement')
        {
            $this->data['measurements'] = $this->admin_model->get_measurements();
        }

        if($page == 'thread')
        {
            $this->data['threads'] = $this->admin_model->get_threads();
        }

        if($page == 'button')
        {
            $this->data['buttons'] = $this->admin_model->get_buttons();
        }

        if($page == 'product_fabric')
        {
            $this->data['product_fabrics'] = $this->admin_model->get_all_product_fabrics_with_submenus();
        }

        $this->data['title'] = ucfirst($page); 

        $this->template->load('shmyde','admin/'.$page, $this->data);

    }

    public function edit($page, $id, $param0 = 0, $param1 = 0)
    {
        if ( ! file_exists(APPPATH.'/views/admin/create_'.$page.'.php'))
        {
            show_404();
        }
        
        $this->data['cssLinks'] = array('admin');

        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            if($page == 'product')
            {
                $this->edit_product($id);
            }

            if($page == 'menu')
            {
                $this->edit_menu($id);
            }

            if($page == 'option')
            {
                $this->create_option('option');
            }
            
            
            if($page == 'product_fabric')
            {
                $this->edit_product_fabric($id);
            }
            
            if($page == 'measurement')
            {
                $this->edit_measurement($id);
            }
            
            if($page == 'thread')
            {
                $this->edit_thread($id);
            }
            
            if($page == 'button')
            {
                $this->edit_button($id);
            }

            return;
        }
                
        
        $this->data['is_edit'] = json_encode(true);
        
        if($page == 'product')
        {
            $this->begin_edit_product($id);	
        }

        if($page == 'menu')
        {
            $this->begin_edit_menu($id);
        }

        if($page == 'option')
        {
            $this->begin_edit_option($id, $param0, $param1);
        }
        
        if($page == 'fabric')
        {
            $this->begin_edit_fabric($id);
        }
        
        if($page == 'measurement')
        {
            $this->begin_edit_measurement($id);
        }
        
        if($page == 'thread')
        {
            $this->begin_edit_thread($id);
        }
        
        if($page == 'button')
        {
            $this->begin_edit_button($id);
        }
        
        if($page == 'product_fabric')
        {
            $this->begin_edit_product_fabric($id);
        }

        $this->data['title'] = 'EDIT';  // Capitalize the first letter
                        
        $this->template->load('shmyde','admin/create_'.$page, $this->data);

    }

    function edit_product($id){
        
        if($this->admin_model->edit_product(
                $id, $this->input->post('name'),  
                $this->input->post('url_name'), 
                $this->input->post('target'),
                $this->input->post('price'))){

            
            redirect('/admin/view/product', 'refresh');
        }
        
    }
    
    function edit_thread($id){
                
        //Image was changed
        if($_FILES["image"]["size"] > 0){
        
            $image_uploaded = $this->UploadImage("image", ASSETS_DIR_PATH.'images/threads/');
            
            $image_name = basename($_FILES["image"]["name"]);
            
            if($image_uploaded){
                
                $this->admin_model->update_table("shmyde_threads", "image_name", $image_name, "id = ".$id);
            }
        }
          
        $this->admin_model->edit_thread($id, $this->input->post('color'));

        redirect('/admin/view/thread', 'refresh');
        
    }
    
    function edit_button($id){
                
        //Image was changed
        if($_FILES["image"]["size"] > 0){
                
            $image_uploaded = $this->UploadImage("image", ASSETS_DIR_PATH.'images/buttons/');
            
            $image_name = basename($_FILES["image"]["name"]);
            
            if($image_uploaded){
                
                $this->admin_model->update_table("shmyde_buttons", "image_name", $image_name, "id = ".$id);
            }
        }
        
        if($_FILES["design_image"]["size"] > 0){
                
            $image_uploaded = $this->UploadImage("design_image", ASSETS_DIR_PATH.'images/buttons/');
            
            $image_name = basename($_FILES["design_image"]["name"]);
            
            if($image_uploaded){
                
                $this->admin_model->update_table("shmyde_buttons", "design_image_name", $image_name, "id = ".$id);
            }
        }
        
            
        $this->admin_model->edit_button($id, $this->input->post('name'));

        redirect('/admin/view/button', 'refresh');
        
        
    }
    
    function edit_product_fabric($id){
                                                          
        $this->admin_model->remove_product_submenu_fabric($id);
                                       
        $this->create_product_fabric_submenu($id);
        
        redirect('/admin/view/product_fabric', 'refresh');
        
    }
    
    function edit_menu($id){
                
        if($this->admin_model->edit_menu(
                $id, 
                $this->input->post('name'), 
                $this->input->post('product'), 
                $this->input->post('category'), 
                $this->input->post('mixed_fabric_support') == null ? 0 : 1,
                $this->input->post('inner_contrast_support') == null ? 0 : 1,
                $this->input->post('is_back_menu') == null ? 0 : 1,
                $this->input->post('is_independent') == null ? 0 : 1)){
            
            redirect('/admin/view/menu', 'refresh');
        }
    }
    
    function edit_measurement($id){
                
        if($this->admin_model->edit_measurement(
                $id, 
                $this->input->post('name'), 
                $this->input->post('product'),                  
                $this->input->post('description'),
                $this->input->post('default_value'),
                $this->input->post('youtube_video'))){
            
            redirect('/admin/view/measurement', 'refresh');
        }
    }
    
    function begin_edit_product($id){
                
        $this->data['product'] = $this->admin_model->get_product($id);
        
        $this->data['product_id'] = $this->data['product']->id;
        
        $back_image = $this->admin_model->get_images($id, 'shmyde_product_back_image');
        
        $front_image = $this->admin_model->get_images($id, 'shmyde_product_front_image');
        
        if(isset($back_image)){
            
            if(!empty($back_image))
            {
                $this->data['back_images'] = json_encode($back_image);
            }
            else
            {
                $this->data['back_images'] = array();
            }
        }
        
        if(isset($front_image)){
            
            if(!empty($front_image)){
                
                
                $this->data['front_images'] = json_encode($front_image);
            }
            else{
                
                $this->data['front_images'] = array();
            }
            
        }
        
    }
        
    function begin_edit_product_fabric($id)
    {
        
        // Gets the fabric with its image data         
        $this->data['product_fabric'] = json_encode($this->admin_model->get_product_fabric($id));
                                                
        $this->data['fabric_id'] = $id;
        
        $this->data['products'] = $this->admin_model->get_all_products();
        
        $this->data['fabric_submenus'] = $this->admin_model->get_fabric_menus();
        
        $this->data['product_submenu_fabrics'] = $this->admin_model->get_product_submenu_fabrics();
                                      
    }
    
    function begin_edit_menu($id)
    {
        
        $this->data['menu'] = $this->admin_model->get_menu($id);
        
        $this->data['products'] = $this->admin_model->get_all_products();
        
        $this->data['categories'] = $this->admin_model->get_categories();
        
        $this->data['menu_category'] = $this->admin_model->get_category($this->data['menu']->shmyde_design_category_id);
                                
    }
    
    function begin_edit_measurement($id)
    {
        
        $this->data['measurement'] = $this->admin_model->get_measurement($id);
        
        $this->data['products'] = $this->admin_model->get_all_products();
                             
    }
    
    function begin_edit_thread($id)
    {
        $this->data['thread'] = $this->admin_model->get_thread($id);
    }
    
    function begin_edit_button($id)
    {
        $this->data['button'] = $this->admin_model->get_button($id);
    }
    
    function begin_edit_buttons($id)
    {
        $this->data['button'] = $this->admin_model->get_button($id);
                                     
    }
        
    function begin_edit_option($id, $param0 = 0, $param1 = 0){
                
        $this->data['id'] = $id;
        
        $this->data['menus'] = $this->admin_model->get_design_dependent_menus();

        $this->data['products'] = $this->admin_model->get_all_products();
        
        $this->data['selected_product'] = $param0;
        
        $this->data['selected_menu'] = $param1;
        
        $this->data['option_dependent_menu'] = $this->admin_model->get_option_dependent_menus($id);

        $option_images = $this->admin_model->get_images($id, 'shmyde_images');
                
        $option_thumbnail = $this->admin_model->get_images($id, 'shmyde_option_thumbnail');
        
        if(isset($option_images)){
            
            if(!empty($option_images)){
                
                
                $this->data['option_images'] = json_encode($option_images);
            }
            else{
                
                $this->data['option_images'] = array();
            }
            
        }
        
        if(isset($option_thumbnail)){
            
            if(!empty($option_thumbnail))
             {
                
                $this->data['option_thumbnails'] = json_encode($option_thumbnail);
            }
            else{
                
                $this->data['option_thumbnails'] = array();
            }
            
        }
        
        $this->data['option'] = $this->admin_model->get_option($id);
                
    }
    
    public function delete($page = 'product', $id)
    {
        	
            if($page == 'product'){

                $this->admin_model->delete_product($id);

                redirect('/admin/view/product', 'refresh');

            }
            
            if($page == 'product_fabric'){

                $this->admin_model->delete_product_fabric($id);

                redirect('/admin/view/product_fabric', 'refresh');

            }
            

            if($page == 'menu'){

                $this->admin_model->delete_menu($id);

                redirect('/admin/view/menu', 'refresh');

            }

            if($page == 'option'){

                $this->admin_model->delete_option($id);

                redirect('/admin/view/option', 'refresh');

            }
            
            if($page == 'measurement'){

                $this->admin_model->delete_measurement($id);

                redirect('/admin/view/measurement', 'refresh');

            }
            
            if($page == 'thread'){

                $this->admin_model->delete_thread($id);

                redirect('/admin/view/thread', 'refresh');

            }
            
            if($page == 'button'){

                $this->admin_model->delete_button($id);

                redirect('/admin/view/button', 'refresh');

            }

        }
    
    /**
     * This function is used to display the creation page of most of the
     * data elements of the website
     * @param type $page This represents the current page or item being created
     * @return type
     */    
    public function create($page = 'product', $param0 = 0, $param1 = 0)
    {

        if ( ! file_exists(APPPATH.'/views/admin/create_'.$page.'.php'))
        {
            show_404();
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST'){


            $this->create_product($page);
            
            $this->create_product_fabric($page);

            $this->create_menu($page);
                        
            $this->create_option($page);
            
            $this->create_measurement($page);
            
            $this->create_thread($page);
            
            $this->create_button($page);
            
            return;
        }
                
        $this->data['cssLinks'] = array('admin');
	
	// This creates a unique id for the object being created.     
	$this->data['id'] = uniqid();    
                       
        $this->view_create_product($page);
                       
        $this->view_create_product_fabric($page);
        
        $this->view_create_measurement($page);
        
        $this->view_create_menu($page);
        
        $this->view_create_thread($page);
        
        $this->view_create_button($page);
                
        $this->view_create_option($page, $param0, $param1);
                              
        $this->data['title'] = 'CREATE'; 
        
        $this->data['is_edit'] = json_encode(false);
        

        $this->lang->load('shmyde', CURRENT_LANGUAGE);
        
        $this->template->load('shmyde','admin/create_'.$page, $this->data);

    }
    
    /**
     * This function is called when the create product page is posted
     * @param type $page the current page being posted
     */
    private function create_product($page){
        
        if($page == 'product'){
                

            if($this->admin_model->create_product(
                $this->input->post('name'),
                $this->input->post('url_name'),
                $this->input->post('target'),
                $this->input->post('price'))){

                redirect('/admin/view/product', 'refresh');

            }
        }
    }
    
    /**
     * This function creates a thread
     * @param type $page
     */
    private function create_thread($page){
        
        if($page == 'thread'){
            
            $image_name = basename($_FILES["image"]["name"]);
            
            $image_uploaded = $this->UploadImage("image", ASSETS_DIR_PATH.'images/threads/');
            
            if(!$image_uploaded){
                
                $image_name = '';
            }
                                    
            if($this->admin_model->create_thread($image_name, $this->input->post('color')))
            {
                redirect('/admin/view/thread', 'refresh');
            }
        }
    }
    
    /**
     * This function uploads an image in a directory
     * @param type $name the name in the client
     * @param type $base_dir the directory where the image is saved
     * @param type $file_name the name of the file
     * @return boolean
     */
    public function UploadImage($name, $base_dir, $file_name = null){
                   
        $target_file = $base_dir . basename($_FILES[$name]["name"]);
        
        if($file_name != null)
        {
            $target_file = $base_dir.$file_name;
        }

        $uploadOk = 1;
        
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if($this->input->server('REQUEST_METHOD') == 'POST') 
        {
            $check = getimagesize($_FILES[$name]["tmp_name"]);

            if($check !== false) 
            {
                $uploadOk = 1;
            } 
            else 
            {
                $uploadOk = 0;
            }
        }
        
        // Check if file already exists
        if (file_exists($target_file)) 
        {
            unlink($target_file);
        }
        // Check file size
        if ($_FILES[$name]["size"] > 500000) 
        {
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) 
        {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {           
            return false;
        } 
        else 
        {
            if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) 
            {                    
                return true;
            } 
            else 
            {
                return false;
            }
        }
        
        return true;

    }


    /**
     * This function creates a button
     * @param type $page
     */
    private function create_button($page){
        
        if($page == 'button'){
            
            $image_name = "";
            
            $design_image_name = "";
            
            //Image was changed
            if($_FILES["image"]["size"] > 0){

                $image_uploaded = $this->UploadImage("image", ASSETS_DIR_PATH.'images/buttons/');

                $image_name = basename($_FILES["image"]["name"]);

                if(!$image_uploaded){

                    $image_name = "";
                }
            }

            if($_FILES["design_image"]["size"] > 0){

                $image_uploaded = $this->UploadImage("design_image", ASSETS_DIR_PATH.'images/buttons/');

                $design_image_name = basename($_FILES["design_image"]["name"]);

                if(!$image_uploaded){

                    $design_image_name = "";
                }
            }
               
            if($this->admin_model->create_button($image_name, $design_image_name, $this->input->post('name')))
            {
                redirect('/admin/view/button', 'refresh');
            }

        }
    }
    
    /**
     * This function is called when the create product page is posted
     * @param type $page the current page being posted
     */
    private function create_product_fabric($page){
        
        if($page == 'product_fabric'){
            
            $id = $this->admin_model->get_table_next_id("shmyde_fabrics");
                                       
            $this->create_product_fabric_submenu($id);
            
            $this->admin_model->save_fabric($id, "fabric_name");
            
            redirect('/admin/view/product_fabric', 'refresh');
        
        }
               
    }
    
    private function create_product_fabric_submenu($fabric_id){
        
        $products = $this->admin_model->get_all_products();
        
        foreach ($products->result() as $product) {

            if(isset($this->input->post('product')[$product->id]['default'])){

                $this->admin_model->set_product_fabric_default($product->id, $fabric_id);
            }

            $fabric_submenus = $this->admin_model->get_fabric_menus();

            foreach ($fabric_submenus->result() as $fabric_submenu){

                if(isset($this->input->post('product')[$product->id][$fabric_submenu->id])){

                    $this->admin_model->save_product_submenu_fabric($fabric_id, $product->id, $fabric_submenu->id);

                }

            }

        }
    }

    /**
     * This function is called when the create menu page is posted
     * @param type $page the current page being posted
     */
    private function create_menu($page){
                
        if($page == 'menu'){
            
            if($this->admin_model->create_menu(
                    $this->input->post('name'), 
                    $this->input->post('product'), 
                    $this->input->post('category'), 
                    $this->input->post('mixed_fabric_support') == null ? 0 : 1,
                    $this->input->post('inner_contrast_support') == null ? 0 : 1,
                    $this->input->post('is_back_menu') == null ? 0 : 1,
                    $this->input->post('is_independent') == null ? 0 : 1)){

                redirect('/admin/view/menu', 'refresh');
            }
        }
    }
    
    private function create_measurement($page){
                
        if($page == 'measurement'){
            
            if($this->admin_model->create_measurement(
                    $this->input->post('name'), 
                    $this->input->post('product'), 
                    $this->input->post('description'),
                    $this->input->post('default_value'),
                    $this->input->post('youtube_video')                    
                    )){

                redirect('/admin/view/measurement', 'refresh');
            }
        }
    }

    /**
     * This function is called when the create option page is posted
     * @param type $page the current page being posted
     */
    private function create_option($page)
    {
	if($page == 'option')
	{
		$data = $this->input->post('option');	
		// Create or edit option 
		$this->admin_model->create("shmyde_design_option", $data);	
		// Save dependent menus
		$option_dependent_menus = $this->input->post('option_dependent_menu');
		
		if($data['is_default'] == 1)
		{
			$this->admin_model->reset_option_defaults();
		}

		if(isset($option_dependent_menus))
		{
		    foreach ($this->input->post('option_dependent_menu') as $key => $value) 
		    {
			$this->admin_model->add_option_dependent_menu($data['id'], $key);
		    }
		}

		redirect('/admin/view/option/'.$this->input->post('shmyde_design_main_menu_id').'/'.$this->input->post('product'), 'refresh');
	}
    }
    
    
    /**
     * This function is used to generate the data required to create the page
     * @param array $data
     * @return type
     */
    private function view_create_product($page)
    {
        
        if($page == 'product'){
            
        }
                       
    }
    
    /**
     * This function is used to generate the data required to create the page
     * @param array $data
     * @return type
     */
    private function view_create_product_fabric($page){
        
        if($page == 'product_fabric'){
            
            $this->data['product_fabric'] = '';
                                    
            $this->data['products'] = $this->admin_model->get_all_products();
            
            $this->data['fabric_submenus'] = $this->admin_model->get_fabric_menus();
            
            $this->data['product_submenu_fabrics'] = $this->admin_model->get_product_submenu_fabrics();
            
        }
                       
    }
    
    /**
     * This function is used to generate the data required to create the page
     * @param array $data
     * @return type
     */
    private function view_create_menu($page){
        
        if($page == 'menu'){
            
            $this->data['products'] = $this->admin_model->get_all_products();
            
            $this->data['categories'] = $this->admin_model->get_categories();
        }
                
    }
    
    private function view_create_measurement($page){
        
        if($page == 'measurement'){
            
            $this->data['products'] = $this->admin_model->get_all_products();           
        }
                
    }
    
    private function view_create_thread($page){
        
        
    }
    
    private function view_create_button($page){
        
        
    }
    
    
    /**
     * This function is used to generate the data required to create the page
     * @param array $data
     * @return type
     */
    private function view_create_option($page, $param0, $param1){
        
        if($page == 'option'){
            
            $this->data['menus'] = $this->admin_model->get_design_dependent_menus();

            $this->data['products'] = $this->admin_model->get_all_products();
            
            $this->data['selected_product'] = $param0;
            
            $this->data['selected_menu'] = $param1;

        }
        
    }
    

		
    
    public function get_menus($product_id){
	
        $menus = Array();
        
        $result = $this->admin_model->get_product_menus($product_id);
        
        if(isset($result)){
            
            foreach($result->result() as $row){
                
                $menus[$row->id]['name'] = $row->name;
                $menus[$row->id]['id'] = $row->id;
                
            }
            
            echo json_encode($menus);
        }
        else{
            
            echo '';
        }

    }
   
    public function get_buttons(){
        
        echo json_encode($this->admin_model->get_buttons());
    }
    
    public function get_product_design_menus(){
        
        $product_id = $this->input->post('product_id');
        
        $selected_option_list = json_decode($this->input->post('selected_option_list'));
        
        $menus = Array();
        
        $result = $this->admin_model->get_product_design_menus($product_id, $selected_option_list);
        
        if(isset($result)){
            
            foreach($result as $row){
                
                $menus[$row->id]['name'] = $row->name;
                $menus[$row->id]['id'] = $row->id;
                $menus[$row->id]['mixed_fabric_support'] = $row->mixed_fabric_support;
                $menus[$row->id]['inner_contrast_support'] = $row->inner_contrast_support;
                
            }
            
            echo json_encode($menus);
        }
        else{
            
            echo json_encode('');
        }
    }

    public function get_product_menus($product_id, $category_id){
	        
        $menus = Array();
        
        $result = $this->admin_model->get_product_category_menus($product_id, $category_id);
        
        if(isset($result)){
            
            foreach($result->result() as $row){
                
                $menus[$row->id]['name'] = $row->name;
                $menus[$row->id]['id'] = $row->id;
                $menus[$row->id]['mixed_fabric_support'] = $row->mixed_fabric_support;
                $menus[$row->id]['inner_contrast_support'] = $row->inner_contrast_support;
                
            }
            
            echo json_encode($menus);
        }
        else{
            
            echo json_encode('');
        }

    }
    
    public function get_product_style_images($option_id){
                
        echo json_encode($this->admin_model->get_product_style_images($option_id));
    }
    
    public function get_product_base_images($side, $product_id){
        
        echo json_encode($this->admin_model->get_images($product_id, 'shmyde_product_'.$side.'_image'));
    }
    
    public function create_new_style_button($option_id){
                
        echo json_encode($this->admin_model->create_new_style_button($option_id));
    }
    
    public function load_style_buttons($option_id){
        
        echo json_encode($this->admin_model->load_style_buttons($option_id));
    }
    
    public function load_product_buttons($product_id, $side){
        
        echo json_encode($this->admin_model->load_product_buttons($product_id,$side));
    }
    
    public function delete_style_button($option_id){
        
        echo json_encode($this->admin_model->delete_style_button($option_id));
    }
    
    public function delete_product_button($product_id, $side){
        
        echo json_encode($this->admin_model->delete_product_button($product_id, $side));
    }
    
    public function save_style_button($option_id, $pos_x, $pos_y){
        
        echo json_encode($this->admin_model->save_style_button($option_id, $pos_x, $pos_y));
    }
    
    public function save_product_button($product_id, $side, $pos_x, $pos_y){
        
        echo json_encode($this->admin_model->save_product_button($product_id, $side, $pos_x, $pos_y));
    }

    public function create_new_product_button($product_id, $side){
        
        echo json_encode($this->admin_model->create_new_product_button($product_id,$side));
    }


    public function get_all_product_fabrics($product_id){
        
        $result = json_encode($this->admin_model->get_all_design_product_fabrics($product_id));
        
        if(isset($result)){

            echo $result;
        }
    }


    public function get_product_design_options($menu_id, $category_id, $product_id){
	        
        $result = '';
                
        if($category_id == 1)
        {
            $result = json_encode($this->admin_model->get_product_submenu_fabric_images($product_id, $menu_id));
        }
        else
            {
            $result = $this->admin_model->get_json_menu_options($menu_id);
        }
               
        if(isset($result)){

            echo $result;
        }
    }
    
    public function get_options($menu_id){

        $result = $this->admin_model->get_json_menu_options($menu_id);

        if(isset($result)){

            echo $result;
        }
    }
    
    public function get_measurement($measurement_id){
	
                
        $result = $this->admin_model->get_json_measurement($measurement_id);

        if(isset($result)){

            echo $result;
        }
    }
    
    public function get_fabric($fabric_id){
              
        $result = json_encode($this->admin_model->get_product_fabric($fabric_id));

        if(isset($result)){

            echo $result;
        }
    }


    public function get_product_fabrics($product_id, $submenu_id){
        
        
        $result = json_encode($this->admin_model->get_product_fabrics($product_id, $submenu_id));
        
        echo $result;
    }
    
    public function get_product_fabric($fabric_id){
               
        echo $this->admin_model->get_product_fabric_json($fabric_id);
        
    }


    public function get_option($id){
			
        $result = $this->admin_model->get_json_option($id);

        if(isset($result)){

            echo $result;
        }
    }
    
       
    /**
     * This function uploads an image with it's parameters given the option ID and json
     * parameters that are posted. 
     * This function still requires optimization
     * @param type $id The option_id to which the image is being posted. 
     */
    public function upload_image($id){
        
        $this->load->library('upload');
        
        $image_keys = array();
        
        if($this->input->post('depth') != null)
        {
            $image_keys = array_keys($this->input->post('depth'));
        }
        
        foreach ($image_keys as $image_key) 
        {
            	$this->initialize_upload_library(ASSETS_DIR_PATH."images/".$this->input->post('dir')."/", $image_key.".png");
		
            	$data = array();
		
		// Parameters are present
		if($this->input->post('depth')[$image_key] != null)
		{
			$data = array
		        (
				"id" => $image_key,
				"depth" => $this->input->post('depth')[$image_key],
				"item_id" => $this->input->post('id'),
				"pos_x" => $this->input->post('pos_x')[$image_key],
				"pos_y" => $this->input->post('pos_y')[$image_key],
				"is_inner" => $this->input->post('is_inner')[$image_key] != null ? 1 : 0,
				"is_back_image" => $this->input->post('is_back_image')[$image_key] != null ? 1 : 0,
				"name" => $image_key.".png"
		         );
		}
		else
		{
			$data = array
		        (
				"id" => $image_key,
				"item_id" => $this->input->post('id'),
				"name" => $image_key.".png"
		         );
		}
            
            
            $this->admin_model->create($this->input->post('table'), $data);
            
            if(!$this->upload->do_upload($image_key))
            {
                $this->upload->display_errors();
            }
        }
                            
    }
                    
    function recurse_copy($src,$dst) { 
        
        $dir = opendir($src); 
                
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    
                    try {
                        
                        copy($src . '/' . $file, $dst . '/' . $file); 
                    }
                    catch(Exception $e){
                        
                    }
                } 
            } 
        } 
        closedir($dir);
        $this->delete_all_files($src);
    }   
    
    public function delete_all_files($dir_name){
        
        $files = glob($dir_name.'*'); // get all file names
        foreach($files as $file){ // iterate files
          if(is_file($file)){
            unlink($file);
          }
        }
    }
    
    /**
     * This function is called by the uploader function to delete an image from 
     * the database and from the directory
     */
    public function delete_image(){
        
         $table_name = $this->input->post('table_name');
                
         $item_id = $this->input->post('item_id');
        
         $image_id = $this->input->post('image_id');
        
         $image_dir = $this->input->post('image_dir');
                
         $this->remove_image($table_name, $image_dir, $item_id, $image_id);
    }
    
    /**
     * This function is called to remove an image
     * @param type $table_name The database table from which the image is removed
     * @param type $image_dir The image directory from which the image will be removed
     * @param type $item_id The item id
     * @param type $image_id The id of the image
     */
    private function remove_image()
    {
        
 	$id = $this->input->post('id');
 	$dir = $this->input->post('dir');
    	$table = $this->input->post('table');
	$upload_file = ASSETS_DIR_PATH.'images/'.$image_dir.'/'.$id.'.png';
	    
	unlink($upload_file);    
	    
        $this->admin_model->delete($table_name, $id);
    }
    
    private function initialize_upload_library($uploadDirectory, $fileName)
    {
        $config['upload_path'] = $uploadDirectory;
        $config['file_name'] = $fileName;
        $config['overwrite'] = true;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|csv|tiff|jfif';
        $config['max_size']     = '10000';
        
        $this->upload->initialize($config);
    }
        

}


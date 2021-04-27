<?php
Class MY_Controller Extends CI_Controller{
    public static $site_settings;
    public static $permission_array;
    public function __construct(){
        parent::__construct();
       if(_is_sub_admin()){
            $a = $this->permissions_model->get_by_user_id(_get_current_user_id());
            self::$permission_array = array_column($a, 'role');
       }
		$this::$site_settings = get_options_by_type("general_setting");
		$this->db->trans_strict(FALSE);
    }
    
}
?>
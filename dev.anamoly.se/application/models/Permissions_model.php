<?php class Permissions_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_permissions';
        $this->primary_key= 'permission_id';
    }
	 /**
     * Product category Data listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = ""){
        if(!empty($filter))
        {
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.permission_title'=>$search));
        }
		$this->db->select("{$this->table_name}.*");
        
        $this->db->order_by($this->table_name.".permission_id");
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
    function get_by_user_id($user_id,$left=""){
        
        $this->db->select("{$this->table_name}.*");
        $this->db->join("user_role_permissions","user_role_permissions.permission_id = {$this->table_name}.permission_id");
        $this->db->join("users","users.role_id = user_role_permissions.role_id");        
        $this->db->order_by($this->table_name.".permission_id");
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
    function get_by_role_id($role_id,$left=""){
        
		$this->db->select("{$this->table_name}.*,ifnull(user_role_permissions.role_id,0) as role_id");
        $this->db->join("user_role_permissions","user_role_permissions.role_id = $role_id and user_role_permissions.permission_id = {$this->table_name}.permission_id",$left);
        $this->db->order_by($this->table_name.".permission_id");
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
	/**
	* get product category by id
	* @param string $id for product category id
	* @return object
	*/ 	
    function get_by_id($id){
        $this->db->where($this->table_name.".".$this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
}

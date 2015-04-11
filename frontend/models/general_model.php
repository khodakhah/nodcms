<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class General_Model extends CI_Model 
{    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_website_info()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }
    function get_website_info_options($language_id=null)
    {
        $this->db->select('*');
        $this->db->from('setting_options_per_lang');
        if($language_id!=null)
            $this->db->where('language_id',$language_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    function get_menu()
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->join('titles',"titles.relation_id=menu_id");
        $this->db->where('titles.data_type',"menu");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('public',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_languages()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_language_by_code($code)
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('code',$code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }
    function get_language_default()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('default',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    function get_preview_pages()
    {
        $this->db->select('*');
        $this->db->from('page');
        $this->db->join('titles',"titles.relation_id=page_id");
        $this->db->where('titles.data_type',"page");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('preview',1);
        $this->db->where('public',1);
        $this->db->order_by('page_order',"ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_extensions_by_page_id($page_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
    {
        $this->db->select('*');
        $this->db->from('extensions');
        $this->db->where("relation_id",$page_id);
        $this->db->where('data_type',"page");
        $this->db->where('extensions.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('status',1);
        $this->db->where('public',1);
        $this->db->order_by($order_by,$sort);
        if($limit!=null) $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_extension_related($page_id,$extension_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
    {
        $this->db->select('*');
        $this->db->from('extensions');
        $this->db->where("relation_id",$page_id);
        $this->db->where('data_type',"page");
        $this->db->where('extensions.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('status',1);
        $this->db->where('public',1);
        $this->db->where('extension_id != '.$extension_id);
        $this->db->order_by($order_by,$sort);
        if($limit!=null) $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_extension_by_extension_id($id)
    {
        $this->db->select('*,extensions.image');
        $this->db->from('extensions');
        $this->db->join('users', 'extensions.user_id = users.user_id',"left");
        $this->db->join('page', 'page.page_id = extensions.relation_id');
        $this->db->join('languages', 'languages.language_id = extensions.language_id',"left");
        $this->db->join('titles', 'page.page_id = titles.relation_id');
        $this->db->where('extensions.data_type',"page");
        $this->db->where('titles.data_type',"page");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('extensions.status',1);
        $this->db->where('extensions.public',1);
        $this->db->where('extensions.extension_id',$id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }
    function search_extension($texts,$limit=null,$offset=0)
    {
        $this->db->from('extensions');
        $this->db->join('users', 'extensions.user_id = users.user_id',"left");
        $this->db->join('page', 'page.page_id = extensions.relation_id');
        $this->db->join('languages', 'languages.language_id = extensions.language_id',"left");
        $this->db->join('titles', 'page.page_id = titles.relation_id');
        $this->db->where('extensions.data_type',"page");
        $this->db->where('titles.data_type',"page");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('extensions.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('extensions.status',1);
        $this->db->where('extensions.public',1);
        $this->db->where('page.page_dynamic',1);
        foreach ($texts as $value) {
            $where = array();
            if($value!=""){
                array_push($where,"extensions.name LIKE '%".$value."%'");
                array_push($where,"extensions.description LIKE '%".$value."%'");
            }
            $this->db->where("( ".implode(" OR ",$where)." )");
        }
//        $this->db->where("MATCH(extensions.description)AGAINST('$text')");
        if($limit!=null) $this->db->limit($limit,$offset);
        $this->db->order_by('extensions.updated_date',"desc");
        $this->db->order_by('extensions.created_date',"desc");
        $this->db->group_by('extensions.extension_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_page_detail($id)
    {
        $this->db->select('*,page.avatar');
        $this->db->from('page');
        $this->db->join('titles', "relation_id=page_id");
        $this->db->where('page_id', $id);
        $this->db->where('data_type', 'page');
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    function get_user_by_email_hash_and_active_code($email_hash,$active_code)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email_hash',$email_hash);
        $this->db->where('active_code',$active_code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_user_by_email_and_password($email,$password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function check_username_exists($text){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$text);
        $this->db->where('active_register',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?1:0;
    }
    function check_email_exists($text,$active_register=true){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$text);
        if($active_register==true)
            $this->db->where('active_register',1);
        elseif($active_register==false)
            $this->db->where('active_register',0);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?1:0;
    }
    function update_user_by_email_hash_and_active_code($data,$email){
        $this->db->where('email',$email);
        $this->db->update('users',$data);
    }
    function insert_user($data){
        $this->db->insert('users',$data);
    }

    function update_user_login($user_id,$keep_me_time,$user_agent){
        $this->db->where('user_id',$user_id);
        $this->db->set('keep_me_time',$keep_me_time);
        $this->db->set('user_agent',$user_agent);
        $this->db->update('users');
    }
    function user_set_new_password($user_id,$new_password){
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->set('active_register',1);
        $this->db->set('active',1);
        $this->db->set('active_code',"");
        $this->db->update('users');
    }
    function user_edit_password($user_id,$new_password){
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->update('users');
    }

    function check_comment_by_session_id($session_id,$content,$extension_id)
    {
        $this->db->select('count(*)');
        $this->db->from('comments');
        $this->db->where('session_id', $session_id);
        $this->db->where('content', $content);
        $this->db->where('extension_id', $extension_id);
        $result = $this->db->get();
        $return = $result->result_array();
        return $return[0]["count(*)"]!=0?1:0;
    }
    function check_extension_exists($extension_id)
    {
        $this->db->select('count(*)');
        $this->db->from('extensions');
        $this->db->where('extension_id', $extension_id);
        $result = $this->db->get();
        $return = $result->result_array();
        return $return[0]["count(*)"]!=0?1:0;
    }
    function insert_comment($data=null){
        $this->db->insert('comments', $data);
        $comment_id = $this->db->insert_id();
        return $comment_id;
    }
    function get_comments_by_extension_id($id)
    {
        $this->db->select('*,comments.created_date');
        $this->db->from('comments');
        $this->db->join('users', 'comments.user_id = users.user_id',"left");
        $this->db->where('comments.status',1);
        $this->db->where('comments.extension_id',$id);
        $this->db->where('comments.sub_id',0);
        $this->db->order_by('comments.created_date',"desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_replay_comments($id)
    {
        $this->db->select('*,comments.created_date');
        $this->db->from('comments');
        $this->db->join('users', 'comments.user_id = users.user_id',"left");
        $this->db->where('comments.status',1);
        $this->db->where('comments.sub_id',$id);
        $this->db->order_by('comments.created_date',"desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_gallery_by_page_id($page_id,$order_by="gallery_order",$sort="ASC",$limit=null,$offset=0)
    {
        $this->db->select('*');
        $this->db->from('gallery');
        $this->db->join("titles","titles.relation_id = gallery.gallery_id");
        $this->db->where("gallery.relation_id",$page_id);
        $this->db->where('gallery.data_type',"page");
        $this->db->where('titles.data_type',"gallery");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('gallery.status',1);
        $this->db->order_by("gallery.".$order_by,$sort);
        if($limit!=null) $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_gallery_image_by_page_id($page_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
    {
        $this->db->select('*,gallery_image.image,gallery_image.created_date');
        $this->db->from('gallery_image');
        $this->db->join("gallery","gallery_image.gallery_id = gallery.gallery_id");
        $this->db->join("titles","titles.relation_id = gallery.gallery_id");
        $this->db->where("gallery.relation_id",$page_id);
        $this->db->where('gallery.data_type',"page");
        $this->db->where('titles.data_type',"gallery");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('gallery.status',1);
        $this->db->order_by("gallery_image.".$order_by,$sort);
        if($limit!=null) $this->db->limit($limit,$offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_duplicate_visitor($session_id,$request_url)
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('session_id',$session_id);
        $this->db->where('request_url',$request_url);
        $query = $this->db->get();
        return $query->result_array();
    }
    function update_duplicate_visitor($session_id,$request_url,$data)
    {
        $this->db->where('session_id',$session_id);
        $this->db->where('request_url',$request_url);
        $this->db->update('visitors',$data);
    }
    function insert_visitors($data)
    {
        $this->db->insert('visitors', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
}
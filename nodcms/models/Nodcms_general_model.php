<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/15/2015
 * Time: 11:19 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Nodcms_general_model extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    // Select from "setting" table
    function getWebsiteInfo()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    // Select setting's options in one language from "setting_options_per_lang" table
    function getWebsiteInfoOptions($language_id=null)
    {
        $this->db->select('*');
        $this->db->from('setting_options_per_lang');
        if($language_id!=null)
            $this->db->where('language_id',$language_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    // Select a list from "menu" table
    function getMenu($conditions=null)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->join('titles',"titles.relation_id=menu_id");
        $this->db->where('titles.data_type',"menu");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('public',1);
        if($conditions!=null) $this->db->where($conditions);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Select a list from "languages" table
    function getLanguages()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Select a row with "code" field from "languages" table
    function getLanguageByCode($code)
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('code',$code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    // Select a row from "languages" table (main condition: default = 0)
    function getLanguageDefault()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('default',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    // Select a list from "pages" table if preview = 1
    function getPreviewPages()
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

    // Select a list from "extensions" table
    function getExtensionsByPageId($page_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
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

    // Select a list from "extensions" table
    function getExtensionRelated($page_id,$extension_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
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

    // Select a row from "extensions" table with extension_id
    function getExtensionByExtensionId($id)
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

    // Select a list form "extensions" table (extensions search results)
    function searchExtension($texts,$limit=null,$offset=0)
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

    // Select a row from "page" table with page_id
    function getPageDetail($id)
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

    // Select a row form "users" table with "email_hash" and "active_code"
    function getUserByEmailHashAndActiveCode($email_hash,$active_code)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email_hash',$email_hash);
        $this->db->where('active_code',$active_code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }

    // Select a row form "users" table with "email" and "password" (for login)
    function getUserByEmailAndPassword($email,$password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }

    // Return 1 if a "username" exists in "users" table, else return 0
    function checkUsernameExists($text)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$text);
        $this->db->where('active_register',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?1:0;
    }

    // Return 1 if a "email" exists in "users" table, else return 0
    function checkEmailExists($text,$active_register=true)
    {
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

    // Update "users" table with "email" condition
    function updateUserByEmail($data,$email)
    {
        $this->db->where('email',$email);
        $this->db->update('users',$data);
    }

    // Insert into "users" (for register form)
    function insertUser($data)
    {
        $this->db->insert('users',$data);
    }

    // Update "users" table (for keep a user login)
    function updateUserLogin($user_id,$keep_me_time,$user_agent)
    {
        $this->db->where('user_id',$user_id);
        $this->db->set('keep_me_time',$keep_me_time);
        $this->db->set('user_agent',$user_agent);
        $this->db->update('users');
    }

    // Update "users" table (set a new password form return password form)
    function userSetNewPassword($user_id,$new_password)
    {
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->set('active_register',1);
        $this->db->set('active',1);
        $this->db->set('active_code',"");
        $this->db->update('users');
    }

    // Update "users" table (set a new password from profile change password form)
    function userEditPassword($user_id,$new_password)
    {
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->update('users');
    }

    // Return 1 if a comment exists in "comments" table, else return 0
    function checkCommentBySessionId($session_id,$content,$extension_id)
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

    // Return 1 if an extension exists in "extensions" table, else return 0
    function checkExtensionExists($extension_id)
    {
        $this->db->select('count(*)');
        $this->db->from('extensions');
        $this->db->where('extension_id', $extension_id);
        $result = $this->db->get();
        $return = $result->result_array();
        return $return[0]["count(*)"]!=0?1:0;
    }

    // Insert into "comments" table
    function insertComment($data=null)
    {
        $this->db->insert('comments', $data);
        $comment_id = $this->db->insert_id();
        return $comment_id;
    }

    // Select a list form "comments" table
    function getCommentsByExtensionId($id)
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

    // Select a list form "comments" table
    function getReplayComments($id)
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

    // Select a list form "gallery" table
    function getGalleryByPageId($page_id,$order_by="gallery_order",$sort="ASC",$limit=null,$offset=0)
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

    // Select a list form "gallery_image" table
    function getGalleryImageByPageId($page_id,$order_by="created_date",$sort="DESC",$limit=null,$offset=0)
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

    /*
     * Calculating visits and visitors!
     * This methods used in nodcms_general_helper.php
     */
    function getDuplicateVisitor($session_id,$request_url)
    {
        $this->db->select('*');
        $this->db->from('visitors');
        $this->db->where('session_id',$session_id);
        $this->db->where('request_url',$request_url);
        $query = $this->db->get();
        return $query->result_array();
    }

    function updateDuplicateVisitor($session_id,$request_url,$data)
    {
        $this->db->where('session_id',$session_id);
        $this->db->where('request_url',$request_url);
        $this->db->update('visitors',$data);
    }

    function insertVisitors($data)
    {
        $this->db->insert('visitors', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function getMaxDateVisitor()
    {
        $this->db->select('max(created_date)');
        $this->db->from('visitors');
        $this->db->where('created_date < "'.strtotime(date("d.m.Y")).'"');
        $query = $this->db->get();
        $result = $query->result_array();
        return count($result)!=0?$result[0]["max(created_date)"]:0;
    }

    function getMinDateVisitor()
    {
        $this->db->select('min(created_date)');
        $this->db->from('visitors');
        $this->db->where('created_date < "'.strtotime(date("d.m.Y")).'"');
        $query = $this->db->get();
        $result = $query->result_array();
        return count($result)!=0?$result[0]["min(created_date)"]:0;
    }

    function updateStatistic($minDate,$maxDate)
    {
        // Initial data get
        $this->db->select('count(*), sum(count_view)');
        $this->db->from('visitors');
        $this->db->where('created_date >= "'.$minDate.'"');
        $this->db->where('created_date < "'.$maxDate.'"');
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result)!=0 && $result[0]["count(*)"]){
           // Initial data set
            $data = array(
                "created_date"=>time(),
                "statistic_date"=>$minDate,
                "visits"=>$result[0]["sum(count_view)"],
            );
           // Get visitors count
            $this->db->select('session_id');
            $this->db->from('visitors');
            $this->db->where('created_date >= "'.$minDate.'"');
            $this->db->where('created_date < "'.$maxDate.'"');
            $this->db->group_by('session_id');
            $query = $this->db->get();
            $result = $query->result_array();
            // Set visitors count
            $data["visitors"]=count($result);
            // Get popular URL info
            $this->db->select('request_url, count(request_url) as url_count');
            $this->db->from('visitors');
            $this->db->where('created_date >= "'.$minDate.'"');
            $this->db->where('created_date < "'.$maxDate.'"');
            $this->db->group_by('request_url');
            $this->db->order_by('url_count','DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            $result = $query->result_array();
            // Set popular URL info
            $data["popular_url"]=$result[0]["request_url"];
            $data["popular_url_count"]=$result[0]["url_count"];
            // Get popular Language info
            $this->db->select('language_id, count(language_id) as language_count');
            $this->db->from('visitors');
            $this->db->where('created_date >= "'.$minDate.'"');
            $this->db->where('created_date < "'.$maxDate.'"');
            $this->db->group_by('language_id');
            $this->db->order_by('language_count','DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            $result = $query->result_array();
            // Set popular Language info
            $data["popular_lang"]=$result[0]["language_id"];
            $data["popular_lang_count"]=$result[0]["language_count"];
           // Get popular Language visits percent
            $this->db->select('count(*)');
            $this->db->from('visitors');
            $this->db->where('created_date >= "'.$minDate.'"');
            $this->db->where('created_date < "'.$maxDate.'"');
            $query = $this->db->get();
            $result = $query->result_array();
            // Set popular Language visits percent
            $data["popular_lang_percent"]=round((100*$data["popular_lang_count"])/$result[0]["count(*)"]);
            // Insert in statistic's table
            $this->db->insert('statistic',$data);
            // Delete unused records from visitors table
            $this->db->delete('visitors','created_date >= "'.$minDate.'" AND created_date < "'.$maxDate.'"');
        }else{
            // Set data for statistic table if doesn't exists eny data in visitors table in the period time
            $data = array(
                "created_date"=>time(),
                "statistic_date"=>$minDate,
                "visitors"=>0,
                "visits"=>0,
                "popular_url"=>"",
                "popular_url_count"=>0,
                "popular_lang"=>0,
                "popular_lang_count"=>0,
                "popular_lang_percent"=>0,
            );
           // Insert in statistic's table
            $this->db->insert('statistic',$data);
        }
    }
}

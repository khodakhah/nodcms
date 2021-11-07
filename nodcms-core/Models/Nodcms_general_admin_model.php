<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Core\Models;

class Nodcms_general_admin_model extends CoreModel
{
    function get_website_info()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        return $query->result_array();
    }

    function edit_setting($data=null){
        $this->db->where('id', 1);
        $this->db->update('setting', $data);
        return true;
    }

    function get_all_language()
    {
        $this->db->select("*");
        $this->db->from('languages');
        $this->db->order_by('sort_order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_language_detail($id)
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('language_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function language_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if (!isset($data['rtl'])) {
            $data['rtl']=0;
        }
        if (!isset($data['public'])) {
            $data['public']=0;
        }
        if (!isset($data['default'])) {
            $data['default']=0;
        }

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data['default']) && $data['default']==1)
        {
            $this->db->set('default',0);
            $this->db->update('languages');
        }

        if($id!=null) // update
        {
            $this->db->where('language_id',$id);
            $this->db->update('languages',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('languages',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_menu($conditions=null)
    {
        $this->db->select('*');
        $this->db->from('menu');
        if($conditions!=null) $this->db->where($conditions);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_menu_detail($id)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where('menu_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function menu_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
            unset($data["titles"]);
        }

        if(isset($data['default']))
        {
            $this->db->set('default',0);
            $this->db->update('menu');
        }

        if($id!=null) // update
        {
            $this->db->where('menu_id',$id);
            $this->db->update('menu',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('menu',$data);
            $id=$this->db->insert_id();
        }

        if(isset($titles)){
            $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"menu"));
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"menu"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_currency()
    {
        $this->db->select("*");
        $this->db->from('currency');
        $this->db->order_by('default','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_currency_detail($id)
    {
        $this->db->select('*');
        $this->db->from('currency');
        $this->db->where('currency_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function currency_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }

        if(isset($data['default']))
        {
            $this->db->set('default',0);
            $this->db->update('currency');
        }
        $data['last_updated']=time();

        if($id!=null) // update
        {
            $this->db->where('currency_id',$id);
            $this->db->update('currency',$data);
        }
        else	//add
        {
            $this->db->insert('currency',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_comment()
    {
        $this->db->select("*,comments.status,comments.created_date");
        $this->db->from('comments');
        $this->db->join('users',"users.user_id=comments.user_id");
        $this->db->join('extensions',"extensions.extension_id=comments.extension_id");
        $this->db->order_by('comments.created_date','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_comment_detail($id,$replay=null)
    {
        $this->db->select('*,comments.status,comments.created_date');
        $this->db->from('comments');
        $this->db->join('users',"users.user_id=comments.user_id");
        if($replay==null) $this->db->where('comment_id', $id);
        elseif($replay==true) $this->db->where('sub_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function comment_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }

        if($id!=null) // update
        {
            $this->db->where('comment_id',$id);
            $this->db->update('comments',$data);
        }
        else	//add
        {
            $this->db->insert('comments',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    function comment_replay_manipulate($data,$id)
    {
        $this->db->trans_start();
        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }

        if(isset($data["id"]) && is_numeric($data["id"])) // update
        {
            $this->db->where('sub_id',$id);
            $this->db->where('comment_id',$data["id"]);
            unset($data["id"]);
            $this->db->update('comments',$data);
        }
        else	//add
        {
            if(isset($data["id"])) unset($data["id"]);
            $data["user_id"]=$this->session->userdata['user_id'];
            $data["created_date"]=time();
            $data["sub_id"]=$id;
            $this->db->insert('comments',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_user()
    {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->order_by('user_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_user_detail($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function user_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if (isset($data['password']) && ($this->session->userdata('group') != 1 || $data["password"]=="")) {
            unset($data['password']);
        }elseif(isset($data['password']) && $data['password']!=""){
            $data['password']=md5($data['password']);
        }

        if($id!=null) // update
        {
            $this->db->where('user_id',$id);
            $this->db->update('users',$data);
        }
        else	//add
        {
            $this->db->insert('users',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_country()
    {
        $this->db->select("*");
        $this->db->from('country');
        $this->db->join('languages','languages.language_id=country.language_id');
        $this->db->join('currency','currency.currency_id=country.currency_id');
        $this->db->order_by('country_name','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_country_detail($id)
    {
        $this->db->select('*');
        $this->db->from('country');
        $this->db->where('country_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }

    function country_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
            unset($data["titles"]);
        }

        if($id!=null) // update
        {
            $this->db->where('country_id',$id);
            $data['updated_date']=time();
            $this->db->update('country',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('country',$data);
            $id=$this->db->insert_id();

        }


        $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"country"));
        if(isset($titles)){
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"country"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_extension($data_type=null,$relation_id=null)
    {
        $this->db->select("*,extensions.public,extensions.created_date,extensions.status");
        $this->db->from('extensions');
        $this->db->join('languages','languages.language_id=extensions.language_id');
        if($data_type!=null){
            $this->db->where('data_type',$data_type);
        }
        if($relation_id!=null){
            $this->db->where('relation_id',$relation_id);
        }
        $this->db->order_by('extensions.Status','DESC');
        $this->db->order_by('extensions.created_date','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_extension_detail($id)
    {
        $this->db->select('*');
        $this->db->from('extensions');
        $this->db->where('extension_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }

    function extension_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if($id!=null && $id!=0) // update
        {
            $this->db->where('extension_id',$id);
            $data['updated_date']=time();
            $this->db->update('extensions',$data);
        }
        else	//add
        {
            $data['user_id']=$this->session->userdata['user_id'];
            $data['created_date']=time();
            $this->db->insert('extensions',$data);
            $id=$this->db->insert_id();

        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_panel($data_type=null,$relation_id=null)
    {
        $this->db->select("*");
        $this->db->from('panels');
        $this->db->join('languages','languages.language_id=panels.language_id');
        if($data_type!=null){
            $this->db->where('data_type',$data_type);
        }
        if($relation_id!=null){
            $this->db->where('relation_id',$relation_id);
        }
        $this->db->order_by('panels.panel_order','DESC');
        $this->db->order_by('panels.created_date','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_panel_detail($id)
    {
        $this->db->select('*');
        $this->db->from('panels');
        $this->db->where('panel_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }

    function panel_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if($id!=null && $id!=0) // update
        {
            $this->db->where('panel_id',$id);
            $data['updated_date']=time();
            $this->db->update('panels',$data);
        }
        else	//add
        {
            $data['user_id']=$this->session->userdata['user_id'];
            $data['created_date']=time();
            $this->db->insert('panels',$data);
            $id=$this->db->insert_id();

        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_city()
    {
        $this->db->select("*");
        $this->db->from('city');
        $this->db->join('country','country.country_id=city.country_id');
        $this->db->order_by('city_name','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_city_detail($id)
    {
        $this->db->select('*');
        $this->db->from('city');
        $this->db->where('city_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }

    function city_manipulate($data,$id=null)
    {
        $this->db->trans_start();
        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
        }
        unset($data["titles"]);

        if($id!=null) // update
        {
            $this->db->where('city_id',$id);
            $this->db->update('city',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('city',$data);
            $id=$this->db->insert_id();

        }


        $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"city"));
        if(isset($titles)){
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"city"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_page()
    {
        $this->db->select("*");
        $this->db->from('page');
        $this->db->order_by('page_order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_page_detail($id)
    {
        $this->db->select('*');
        $this->db->from('page');
        $this->db->where('page_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }

    function page_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if(isset($data['default']) && $data['default']==1)
        {
            $this->db->set('default',0);
            $this->db->update('languages');
        }

        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
        }
        unset($data["titles"]);

        if($id!=null) // update
        {
            $this->db->where('page_id',$id);
            $this->db->update('page',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('page',$data);
            $id=$this->db->insert_id();

        }

        if(isset($titles)){
            $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"page"));
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"page"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    function get_search_page($data)
    {
        $this->db->select("*");
        $this->db->from('page');
        foreach ($data as $key=>$value) {
            $this->db->where($key,$value);
        }
        $this->db->order_by('page_order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_gallery($data_type=null,$relation_id=null)
    {
        $this->db->select("*");
        $this->db->from('gallery');
        $this->db->where('data_type',$data_type);
        $this->db->where('relation_id',$relation_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_gallery_detail($gallery_id)
    {
        $this->db->select("*");
        $this->db->from('gallery');
        $this->db->where('gallery_id',$gallery_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_insert_gallery($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $this->db->insert('gallery',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function gallery_manipulate($data,$id=null)
    {
        $this->db->trans_start();
        if(!isset($data['status'])){
            $data['status']=0;
        }
        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
        }
        unset($data["titles"]);

        if($id!=null && $id!=0) // update
        {
            $this->db->where('gallery_id',$id);
            $data['updated_date']=time();
            $this->db->update('gallery',$data);
        }
        else	//add
        {
            $data['user_id']=$this->session->userdata['user_id'];
            $data['created_date']=time();
            $this->db->insert('gallery',$data);
            $id=$this->db->insert_id();

        }
        $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"gallery"));
        if(isset($titles)){
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"gallery"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    function get_gallery_image($gallery_id)
    {
        $this->db->select("*");
        $this->db->from('gallery_image');
        $this->db->where('gallery_id',$gallery_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_gallery_image_detail($gallery_image_id)
    {
        $this->db->select("*");
        $this->db->from('gallery_image');
        $this->db->where('image_id',$gallery_image_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_insert_gallery_image($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $this->db->insert('gallery_image',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function get_all_images(){
        $this->db->select("*");
        $this->db->from('images');
        $query = $this->db->get();
        return $query->result_array();
    }
    function insert_image($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $data['user_id']=$this->session->userdata['user_id'];
        $this->db->insert('images',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function get_image_detail($image_id)
    {
        $this->db->select("*");
        $this->db->from('images');
        $this->db->where('image_id',$image_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_all_titles($data_type=null,$relation_id=null)
    {
        $this->db->select("*");
        $this->db->from('titles');
        $this->db->where('data_type',$data_type);
        $this->db->where('relation_id',$relation_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function count_extensions($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('extensions');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_comment($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('comments');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_page($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('page');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("public",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_gallery($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('gallery');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_gallery_image($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('gallery_image');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_uploaded_image($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('images');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_users($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('users');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function get_all_setting_options(){
        $this->db->select("*");
        $this->db->from('setting_options_per_lang');
        $query = $this->db->get();
        return $query->result_array();
    }
    function check_setting_options($language_id){
        $this->db->select("count(*)");
        $this->db->from('setting_options_per_lang');
        $this->db->where('language_id',$language_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function edit_setting_options($language_id,$data){
        $this->db->where('language_id',$language_id);
        $this->db->update('setting_options_per_lang', $data);
    }
    function insert_setting_options($language_id,$data){
        $data['language_id']= $language_id;
        $this->db->insert('setting_options_per_lang', $data);
    }
    function get_all_statistic(){
        $this->db->select("*");
        $this->db->from('statistic');
        $this->db->order_by('statistic_date','DESC');
        $this->db->limit(14);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_statistic_max_visitors(){
        $this->db->select("max(visitors)");
        $this->db->from('statistic');
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["max(visitors)"])?$result[0]["max(visitors)"]:0;
    }
    function get_statistic_total_visits(){
        $this->db->select("sum(visits)");
        $this->db->from('statistic');
        $query = $this->db->get();
        $result = $query->result_array();
        $return = isset($result[0]["sum(visits)"])?$result[0]["sum(visits)"]:0;
        $this->db->select("sum(count_view)");
        $this->db->from('visitors');
        $query = $this->db->get();
        $result = $query->result_array();
        $return += isset($result[0]["sum(count_view)"])?$result[0]["sum(count_view)"]:0;
        return $return;
    }
    function get_statistic_total_visitors(){
        $this->db->select("sum(visitors)");
        $this->db->from('statistic');
        $query = $this->db->get();
        $result = $query->result_array();
        $return = isset($result[0]["sum(visitors)"])?$result[0]["sum(visitors)"]:0;
        $this->db->select("count(*)");
        $this->db->from('visitors');
        $query = $this->db->get();
        $result = $query->result_array();
        $return += isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
        return $return;
    }
}

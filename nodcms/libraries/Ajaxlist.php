<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-Aug-18
 * Time: 6:07 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class Ajaxlist
{
    public $CI;
    public $options = array(
        'data' => array(),
        'type' => "ajax",
        'loadType' => "pagination",
        'headers' => "",
        'ajaxURL' => "",
        'ajaxData' => null,
        'ajaxMethod' => null,
        'key_field' => "id",
        'theme' => null,
        'listID' => '',
        'listType' => 'table',
        'callback_rows' => array(),
        'page' => 1,
        'pages' => 1,
        'total_rows' => 0,
        'per_page' => 10,
    );

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Set configuration data
     *
     * @param $options array
     */
    function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
        if($this->options['total_rows']>$this->options['per_page']){
            $div = $this->options['total_rows']/$this->options['per_page'];
            $round = round($div, 0);
            $this->options['pages'] = $round+($round<$div?1:0);
        }
    }

    /**
     * Convert DB data to json array with a filter
     *
     * @param $ajax_data
     * @return array
     */
    private function getData($ajax_data)
    {
        $result = array();
        foreach ($ajax_data as $row_index=>$data) {
            $row_id = $this->options['listID']."_row_$row_index";
            $row = array();
            $item_default = array(
                'content'=>"",
                'column_class'=>"",
            );
            $row['row_id']=$row_id;
            $row['row_class'] = "";
            $row['row_bold'] = 0;
            foreach($this->options['callback_rows'] as $callback_row=>$value){
                $row = $this->$callback_row($row, $data, $value);
            }
            foreach ($this->options['headers'] as $key => $item) {
                $item = array_merge($item_default, $item);
                // TODO: Put more cells in one
                if (is_array($item['content'])) {
                    $row['columns'][$key] = "";
                    continue;
                }

                if(key_exists('function', $item)){
                    $content = $item['function']($data);
                }else{
                    $content = $data[$item['content']];
                }
                if(key_exists('callback_function', $item)){
                    $content = $item['callback_function']($content);
                }
                if(key_exists('theme', $item)){
                    $exists_theme = "common/ajaxlist/item_themes/$item[theme].php";
                    // Set theme patch
                    if(file_exists(VIEWPATH.$exists_theme)) $theme = $exists_theme; else $theme = $item['theme'];
                    $content = $this->CI->load->view($theme, array('content' => $content, 'data'=>$data, 'config'=>$item, 'row_id'=>$row_id), true);
                }
                $row['columns'][$key] = array_merge($item, array('content' => $content, 'row_id'=>$row_id, 'column_class'=>$item['column_class']));
            }
            array_push($result, $row);
        }
        return $result;
    }

    function ajaxData($data){
        return json_encode(array(
            'status'=>"success",
            'msg'=>null,
            'data'=>array(
                'result'=>$this->getData($data),
                'page'=>$this->options['page'],
                'pages'=>$this->options['pages']
            )
        ));
    }

    /**
     * The result of the search post form
     *
     * @param $data
     * @param $list_id
     * @return string
     */
    function ajaxDataSearchPost($data,$list_id){
        return json_encode(array(
            'status'=>"success",
            'msg'=>null,
            'data'=>array(
                'callback_success'=>'$("#'.$list_id.'").setAjaxData(' .
                     json_encode(array(
                         'result'=>$this->getData($data),
                         'page'=>$this->options['page'],
                         'pages'=>$this->options['pages']
                     )).
                    ')',
            ),
        ));
    }

    function getPage()
    {
        if($this->options['type']=='static'){
            $this->options['data'] = $this->getData($this->options['data']);
        }else{
            $this->options['data'] = null;
        }
        $this->CI->data['options'] = $this->options;
        return $this->CI->load->view("common/ajaxlist/handel-page", $this->CI->data, true);
    }

    /**
     * Callback function on the rows: callback_rows
     *  - Check a row to be bold
     *
     * @param $row
     * @param $data
     * @param $params
     * @return mixed
     */
    private function check_bold($row, $data, $params)
    {
        $result = $row;
        if(!is_array($params)){
            if($data[$params]==0)
                $result['row_bold'] = 1;
        }
        elseif(count($params)!=2){
            show_error("The parameter of check_bold shall be a single value or an array with two element.");
        }
        else{
            if($data[$params[0]]==$params[1])
                $result['row_bold'] = 1;
        }
        return $result;
    }

}
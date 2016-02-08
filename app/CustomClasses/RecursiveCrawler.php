<?php
namespace App\CustomClasses;

class RecursiveCrawler {
    private $_depth=5;
    private $_urls=array();


    function extract_links($url)
    {
        if(!$this->_started){
            $this->_started=1;
            $curr_depth=0;
        }else{
            $curr_depth++;
        }
        if($curr_depth<$this->_depth)
        {
            $data=file_get_contents($url);
            if(preg_match_all('/((?:http|https):\/\/(?:www\.)*(?:[a-zA-Z0-9_\-]{1,15}\.+[a-zA-Z0-9_]{1,}){1,}(?:[a-zA-Z0-9_\/\.\-\?\&\:\%\,\!\;]*))/',$data,$urls12))
            {
                foreach($urls12[0] as $k=>$v){
                    $check=get_headers($v,1);
                    if(strstr($v,$url) && $check[0]=='HTTP/1.1 200 OK' && !array_search($v,$this->_urls) && $curr_depth<$this->_depth){
                        $this->_urls[]=$v;
                        $this->extract_links($v);
                    }
                }
            }
        }
        return $this->_urls;
    }
}

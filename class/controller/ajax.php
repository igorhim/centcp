<?php
class Controller {
    protected $app;
    
    function __construct($app) {
        $this->app = $app;
    }
    
    function iframe(){
        $this->app->saveIframe($_REQUEST);
        header('Location: ?');
    }
    
    function deleteIframe(){
        $this->app->deleteIframe($_REQUEST['id']);
        header('Location: ?');
    }
    
    function ads(){
        $this->app->saveAds($_REQUEST);
        header('Location: ?action=index&method=adss&iframe=' . $_REQUEST['ads_iframe']);
    }
    
    function disableAds() {
        $this->app->disableAds($_REQUEST['id'], $_REQUEST['status']);
        header('Location: ?action=index&method=adss&iframe=' . $_REQUEST['iframe']);
    }
    
    function deleteAds(){
        $this->app->deleteAds($_REQUEST['id']);
        header('Location: ?action=index&method=adss&iframe=' . $_REQUEST['iframe']);
    }
    
    /**
     * <noindex><iframe id="ad" width="300" scrolling="no" height="250" frameborder="0"
        allowtransparency="true" marginheight="0" marginwidth="0"
        src="//realitytraffic.com/iframes/desktop­straight.php?tags={tags}&site=X&exoid=X"?
        name="ad" scrolling="false"></iframe></noindex>

     */
    function getcode(){
        $iframe = $this->app->getIframeById($_REQUEST['iframe']);
        if(!empty($iframe)) {
            
            $size = explode('x', trim($iframe['iframe_size']));            
            
            $params = '';
            $tags = false;
            $vars = array();
            $adss = $this->app->getAdsByIframe($_REQUEST['iframe']);     
            foreach($adss as $ads) {
                if(!empty($ads['ads_keywords'])) $tags = true;
                $vars = array_unique(array_merge($vars, $this->__getVars($ads['ads_banner'])));
            }
            
            if($tags || !empty($vars)) {
                $params = '?';
                if($tags) {
                    $params .= 'tags={tags}';
                }
                foreach($vars as $var) {
                    $params .= '&' . $var . '=X';
                }
            }
            
            $this->app->assign('width', $size[0]);
            $this->app->assign('height', $size[1]);
            $this->app->assign('url', $this->app->settings['url'] . $this->app->getFilename($iframe['iframe_name']));            
            $this->app->assign('params', $params);
               
            echo $this->app->fetch('code.php');
        }
        
    }
    
    protected function __getVars($str) {
        preg_match_all('#\{([^}]+)\}#', $str, $match);
        return $match[1];
    }
    
    protected function __explode($delimiter, $str) {
        $arr = explode($delimiter, $str);
        foreach($arr as $i => $item) {
            $item = trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $item));
            if(!empty($item)) {
                $arr[$i] = $item;
            }else {
                unset($arr[$i]);
            }
        }
        
        return $arr;
    }
    
    function build() {
        $iframe = $this->app->getIframeById($_REQUEST['id']);
        if(!empty($iframe)) {
            
            $size = explode('x', trim($iframe['iframe_size']));            
            
            $params = '';
            $tags = false;
            $geos  = false;
            $zones = false;
            $vars = array();
            $adss = $this->app->getAdsByIframe($_REQUEST['id']);     
            foreach($adss as $i => $ads) {                
                if(!empty($ads['ads_keywords'])) $tags = true;
                if(!empty($ads['ads_geo'])) $geos = true;
                if(!empty($ads['ads_zone'])) $zones = true;
                $vars = $this->__getVars($ads['ads_banner']);
                $adss[$i]['ads_banner'] = str_replace("'", "\\'", $adss[$i]['ads_banner']);
                $adss[$i]['url'] = $adss[$i]['ads_banner'];
                foreach($vars as $var) {
                    $adss[$i]['url'] = str_replace('{' . $var . '}', '\' . filter_input(INPUT_GET, "' . $var . '").\'', $adss[$i]['url']);
                }
                
                $adss[$i]['tags'] = $this->__explode(',', $ads['ads_keywords']);
                $adss[$i]['geos'] = $this->__explode(',', $ads['ads_geo']);
                $adss[$i]['zones'] = $this->__explode(',', $ads['ads_zone']);
                $adss[$i]['weight'] =  $ads['ads_weight'] > 0 ? $ads['ads_weight'] : 1;
            }
            
            $this->app->assign('isGeos', $geos); 
            $this->app->assign('isTags', $tags); 
            $this->app->assign('isZones', $zones);           
            $this->app->assign('ads', $adss);            
            
            $iframe = array_merge($iframe, array('width' => $size[0], 'height' => $size[1]));            
            $this->app->assign('iframe', $iframe);           
            
            $content = $this->app->fetch('iframe-tmpl.php');          
               
            $this->app->saveFile($this->app->getFilename($iframe['iframe_name']), '<?php ' . PHP_EOL . $content);
        }
    }
}
<?php

namespace Centcp\Controller;

class Dashboard extends \Centcp\Controller {
    
    function __construct($app) {
        
        parent::__construct($app);
        
        $this->app->assign('menu', '/');
        $this->app->assign('title', 'Dashboard');
        $this->app->assign('subtitle', '');        
        $this->app->prependMeta('title', 'Dashboard | ');
        
        $this->prepareHeader();
    }
    
    function index() {
        
       // var_dump($res = sys_getloadavg());
        
        exec("uptime", $system); // get the uptime stats
        
        $string = $system[0]; // this might not be necessary
        $uptime = explode(" ", $string); // break up the stats into an array
        //var_dump($uptime);
        
        $up_days = $uptime[3]; // grab the days from the array
        
        $hours = explode(":", $uptime[5]); // split up the hour:min in the stats
        
        $up_hours = $hours[0]; // grab the hours
        $mins = $hours[1]; // get the mins
        $up_mins = str_replace(",", "", $mins); // strip the comma from the mins
        
        $uptimeStr =  $up_days . " days " . $up_hours . " hours " . $up_mins . " min"; 
        $loadAverage = array(str_replace(",", "", $uptime[12]), str_replace(",", "", $uptime[13]), str_replace(",", "", $uptime[14]));
        
        exec('df -h', $result);
        $diskspace = array();
        
        foreach($result as $i => $row) {
            if($i == 0) continue;
            $data = explode(" ", preg_replace('/\s+/', ' ', $row));
            //var_dump($data);
            if($data[0] == '') {
                unset($data[0]);
                $diskspace[$i-1] = array_merge($diskspace[$i-1], $data);
            }else {
                $diskspace[$i] = $data;
            }
        } 
        
        /**
         * array(5) {
          [0]=>
          string(54) "Filesystem            Size  Used Avail Use% Mounted on"
          [1]=>
          string(45) "/dev/vda1              20G  8.3G   11G  45% /"
          [2]=>
          string(52) "tmpfs                 246M     0  246M   0% /dev/shm"
          [3]=>
          string(25) "/home/usertmp_donotdelete"
          [4]=>
          string(48) "                      3.7G  7.9M  3.5G   1% /tmp"
        }
        */
        
        $this->app->assign('uptime', $uptimeStr);
        $this->app->assign('loadAverage', $loadAverage);
        $this->app->assign('diskspace', $diskspace);
        
        $this->app->display('header.php');
        $this->app->display('dashboard.php');
        $this->app->display('footer.php');
    }
    
}
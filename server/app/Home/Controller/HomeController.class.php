<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends CommonController {
    
    //获得首页数据包
    public function getPacket(){
        $res=[];
        
        //===================================================
        $Time=D('Time');
        $Carousel=D('Carousel');
        $Nav=D('Nav');
        //===================================================
        $where=[];
        $where['pages_id']=0;
        $carousels=$Carousel->getList($where);
        //===================================================
        $times=$Time->getList();
        $navList= $Nav->getList();
        $homeData=$Nav->get('0');
        
        
        //组成导航数据
        for ($i=0; $i < count($navList); $i++) {
            $nav_id=$navList[$i]['nav_id'];
            $navList[$i]['data']=$Nav->get($nav_id);
        }
        
        
        
        $res['carousel']=$carousels;
        $res['homeData']=$homeData;
        $res['navList']=$navList;
        $res['times']=$times;
        
        echo json_encode($res);
        
    }
    
}
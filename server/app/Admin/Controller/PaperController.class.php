<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年4月8日11:23:10
* 最新修改时间：2018年4月8日11:23:10
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####文章管理控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class PaperController extends CommonController{
    
    public function getList(){
        $Paper=M('paper');
        
        $page=I('page')?I('page'):1;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $res['count']=$Paper->where($where)->count()+0;
        $papers=$Paper->where($where)->limit(($page-1)*$limit,$limit)->select();
        
        $papers=toTime($papers);
        
        if($papers){
            $res['res']=count($papers);
            $res['msg']=$papers;
        }else{
            $res['res']=-1;
            $res['msg']=$papers;
        }
        echo json_encode($res);
        
    }
    
    public function add(){
        $Paper=M('paper');
        
        $paper_id=getMd5('paper');
        
        $add=I('add','',false);
        $add['paper_id']=$paper_id;
        $add['add_time']=time();
        $add['edit_time']=time();
        
        
        $result=$Paper->add($add);
        
        
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $paper_id=I('paper_id');
        $Paper=D('Paper');
        $where=[];
        $where['paper_id']=['in',$paper_id];
        $result=$Paper->where($where)->delete();
        if($result){
            $res['res']=1;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
        
    }
    
    
}
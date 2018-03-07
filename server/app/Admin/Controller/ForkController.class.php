<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月7日11:09:10
* 最新修改时间：2018年3月7日11:09:10
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####分销商控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class ForkController extends CommonController{
    
    
    public function getList(){
        $model=M('user');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $where['user_type']=1;
        
        $res['count']=$model
        ->where($where)
        ->order('add_time desc')
        ->count()+0;
        
        
        $result=$model
        ->table('c_user as t1,c_star as t2')
        ->field('t1.*,t2.*')
        ->where($where)
        ->where('t1.star_id = t2.star_id')
        ->order('t1.add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
    }
    
    
}
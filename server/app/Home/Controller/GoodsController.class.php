<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月6日10:46:01
* 最新修改时间：2018年2月6日10:46:01
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller{
    
    
    //获得商品列表
    public function getList(){
        
        $model=M('goods');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=[];
        $where['is_up']=1;
        
        $result=$model
        ->where($where)
        ->order('sort desc,add_time desc')
        // ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            $map=[];
            $map['img_list']=false;
            $map['goods_class']=false;
            $map['spec']=false;
            
            $result=arrJsonD($result,$map);
            
            
            for ($i=0; $i < count($result); $i++) {
                $result[$i]['isCollection']=false;
            }
            
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    
    
}
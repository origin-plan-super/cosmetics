<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月7日10:14:54
* 最新修改时间：2018年3月7日10:14:54
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####用户控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{
    
    public function getList(){
        
        $model=M('user');
        $page=I('page')?I('page'):0;
        $limit=I('limit')?I('limit'):10;
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('add_time desc')
        ->limit(($page-1)*$limit,$limit)
        ->select();
        
        
        // =========判断=========
        if($result){
            //总条数
            $result=toTime($result);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    public function test(){
        echo "<h3>CTOS用户生成器</h3>";
        
        $model=M('user');
        $start=$model->count()+1;
        $max=$start+20;
        $adds=[];
        
        for ($i=$start; $i < $max; $i++) {
            
            $add=[];
            $add['user_id']="1213$i";
            $add['user_pwd']=md5('123'.__KEY__);
            
            $add['user_name']="用户$i";
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['user_head']='https://avatar.tower.im/2d7c6e666b344b0888fee32964593bb5';
            
            $add['user_type']=0;
            $add['star_id']=null;
            
            if($i%3==0){
                //代理商
                $add['user_type']=1;
                $add['star_id']="480a56015f1f68e82bdb54471b6af126";
                
            }
            if($i%2==0){
                //推广员
                $add['user_type']=2;
            }
            
            $adds[]=$add;
        }
        
        // $model->addAll($adds);
        dump($adds);
        
    }
    
    
    public function save(){
        
        $model=M('user');
        $where=I('where');
        $save=I('save');
        if(!$where || !$save){
            $res['res']=-2;
            echo json_encode($res);
            die;
        }
        $result=$model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        
        echo json_encode($res);
        
    }
    
    
    
}
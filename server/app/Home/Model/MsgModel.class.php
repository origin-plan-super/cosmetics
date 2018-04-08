<?php
namespace Home\Model;
use Think\Model;
class MsgModel extends Model {
    
    
    public function getList($where=[]){
        
        $msgs=$this->where($where)->select();
        $msgs=toTime($msgs);
        return $msgs;
        
    }
    
    
    
}
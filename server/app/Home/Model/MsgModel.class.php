<?php
namespace Home\Model;
use Think\Model;
class MsgModel extends Model {
    
    
    public function getList($data){
        $where=$data['where'];
        $msgs=$this
        ->order('add_time desc')
        ->where($where)
        ->select();
        $msgs=toTime($msgs);
        return $msgs;
    }
    
}
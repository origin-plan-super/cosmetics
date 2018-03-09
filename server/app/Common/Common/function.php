<?php
/**批量转换时间 */
function toTime($arr,$code='Y-m-d H:i:s'){
    
    
    foreach ($arr as $key => $value) {
        
        if(!empty($value['add_time'])){
            $arr[$key]['add_time']=date($code,$value['add_time']);
        }
        if(!empty($value['edit_time'])){
            $arr[$key]['edit_time']=date($code,$value['edit_time']);
        }
    }
    
    return $arr;
    
}
function toHtml($arr,$field){
    
    
    foreach ($arr as $key => $value) {
        
        if(!empty($value[$field])){
            $arr[$key][$field]=htmlspecialchars_decode($value[$field]);
        }
        
    }
    
    return $arr;
    
}
function html($arr){
    return htmlspecialchars_decode($arr);
}
/**强验证是否正确 */
function check($var){
    
    return isset($var) && !empty($var) ? true:false;
    
}
/**判断验证码是否正确 */
function isCode($code){
    //验证 验证码
    //校验验证码（不需要传参）
    $verify = new \Think\Verify();
    //验证
    return $verify -> check($code);
    
}
/**获得验证码 */
function getCode($cfg){
    
    if(!$cfg){
        //验证码配置
        $cfg = array(
        'fontSize' => 12, // 验证码字体大小(px)
        'useCurve' => false, // 是否画混淆曲线
        'useNoise' => false,
        // 是否添加杂点
        'length' => 4, // 验证码位数
        'fontttf' => '4.ttf', // 验证码字体，不设置随机获取
        );
    }
    
    //实例化验证码类
    $verify = new \Think\Verify($cfg);
    //输出验证码
    ob_clean();
    $verify -> entry();
    
}
/**验证用户名和密码是否匹配 */
function login($form,$id,$pwd,$isMd5=true){
    
    if($isMd5){
        $pwd=md5($pwd.__KEY__);
    }
    
    $model=M($form);
    $where=[];
    $where[$form.'_id']=$id;
    
    $result=$model->where($where)->find();
    if($result[$form.'_pwd']===$pwd){
        //验证成功
        return $result;
    }else{
        return false;
    }
    
}
/**
* 查询数据
*/
function getList(){
    
    //初始化
    $data=[];
    $res=[];
    $where=[];
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    $model=M($table);
    //获得条件查询
    $where=I('where')?I('where'):[];
    //初始化 end
    
    //分页记录
    //当前页数
    $page=I('page')?I('page'):0;
    //一次查询条数
    $limit=I('limit')?I('limit'):10;
    //分页记录 end
    
    //条件查询
    $key=I('key');
    $key_where= I('key_where');
    
    if(check($key)){
        //如果存在就查询
        $where[$key_where] = array(
        'like',
        "%".$key."%",
        'OR'
        );
    }
    
    //条件查询 end
    
    //生成数据
    $data=$model
    ->field('t1.*,t2.user_id,t2.user_name')
    ->table('ao_feedback as t1,ao_user as t2')
    ->order('t1.add_time asc')
    ->where('t1.user_id = t2.user_id')
    ->where($where)
    ->select();
    
    //总条数
    $res['count']=count($data);
    //取指定条数
    //索引位置=当前页数-1*每页展示量
    
    if(check($page)){
        //如果有分页数据，才分页
        $data=array_slice($data ,($page-1)*$limit,$limit);
    }
    //转换时间戳
    $data=toTime($data);
    //取得成功状态S
    $res['res']=1;
    //数据
    $res['msg']=$data;
    return $res;
    
}
/**
* 初始化获得列表
*/
function initGetList(){
    $table=strtolower(I('table'));
    $model=M($table);
    $where=I('where')?I('where'):[];
    $key=I('key');
    $key_where= I('key_where');
    if(check($key)){
        //如果存在就查询
        $where[$key_where] = array(
        'like',
        "%".$key."%",
        'OR'
        );
    }
    
    $conf=[];
    $conf['page']=I('page')?I('page'):0;
    $conf['limit']=I('limit')?I('limit'):10;
    $conf['where']=$where;
    $conf['model']=$model;
    
    return $conf;
    
}
/**
* 分页处理
*/
function getPageList($conf,$data){
    
    if(check($conf['page'])){
        //索引位置=当前页数-1*每页展示量
        //如果有分页数据，才分页
        $data=array_slice($data ,($conf['page']-1)*$conf['limit'],$conf['limit']);
    }
    return $data;
    
}
/**
* 保存
*/
function save(){
    
    //获取要保存的数据
    $save=I('save');
    unset($save['add_time']);
    
    
    if($save['id']){
        
        $id=$save['id'];
        unset($save['id']);
        
    }
    
    
    $save['edit_time']=time();
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    //获得条件查询
    $where=I('where')?I('where'):[];
    //设置基本插叙条件为此条数据的id
    
    if($id!==null){
        $where['id']=$id;
    }else{
        
        if(I('id')){
            //如果有id，就使用id的，否则就使用上传的条件
            $where[$table.'_id']=I('id');
        }
        
    }
    
    
    
    
    //创建模型
    $model=M($table);
    $result=$model->where($where)->save($save);
    
    
    $res['sql']=$model->_sql();
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    
    return $res;
}
/**
* 删除单个
* $isRecycle 是否设置回收状态，默认是false，也就是真的直接删除，如果为true，并不会被真的删掉，而是设置某个字段
*/

function del($isRecycle=false,$field ,$val,$whereData){
    
    
    
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    //获得条件查询
    $where=I('where')?I('where'):[];
    if($whereData){
        $where+=$whereData;
    }
    //设置基本插叙条件为此条数据的id
    if(I('id')){
        //如果有id，就使用id的，否则就使用上传的条件
        $where[$table.'_id']=I('id');
    }
    //创建模型
    $model=M($table);
    
    if($isRecycle){
        //放在回收站里
        $save[$field]=$val;
        $result=$model->where($where)->save($save);
        
    }else{
        //真的删除
        $result=$model->where($where)->delete();
        
    }
    
    
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    return $res;
    
}
/**
* 批量删除
*/
function dels($isRecycle=false,$field ,$val){
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    $model=M($table);
    //获得条件查询
    
    $where=I('where')?I('where'):[];
    
    $where[$table.'_id']=array('in',I('ids'));
    
    if($isRecycle){
        //放在回收站里
        $save[$field]=$val;
        $result=$model->where($where)->save($save);
        
    }else{
        //真的删除
        $result=$model->where($where)->delete();
    }
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    
    //=========判断end=========
    return $res;
}
/**
* 添加
*/
function add($id=false,$idType=false,$addData){
    //获得表名并且处理表名大小写
    $table = strtolower(I('table'));
    
    $model=M($table);
    
    if(I('isDelAll')===true){
        //清空后
    }
    
    //获得添加数据
    $add=I('add');
    if($addData){
        // $add =array_merge($add,$addData);
        $add+=$addData;
    }
    if(!$idType){
        $idType=I('idType');
    }
    
    if($id===false || $id===null){
        
        if($idType=='auto'){
            
        }
        if($idType=='md5'){
            $add[$table.'_id']=md5($table.time().rand().__key__.rand(0,9999));
        }
        
    }else{
        //如果是指定的id
        $add[$table.'_id']=$id;
    }
    
    
    $add['add_time']=time();
    $add['edit_time']=time();
    //添加
    $result=$model->add($add);
    
    // $res['sql']=$model->_sql();
    
    if(I('returnData')){
        $where=[];
        $where[$table.'_id']=$id;
        $field=I('field')?I('field'):[];
        $res['msg']=$model->where($where)->field($field)->find();
    }
    
    //=========判断=========
    if($result!==false){
        $res['res']=1;
    }else{
        $res['res']=-1;
    }
    //=========判断end=========
    return $res;
}
/**
* 验证用户是否登录
*/
function isUserLogin(){
    
    //接收登录参数
    $user_id=I('user_id');
    $token=I('token');
    
    if(!$user_id || !$token){
        //没有参数
        return -992;
    }
    
    $where['user_id']=$user_id;
    $where['token']=$token;
    $model=M('token');
    $result=$model->where($where)->find();
    if($result){
        //账户正确
        //token存在
        
        //验证token的时间过期没有
        $tokenTime=$result['edit_time'];
        $toTome=time();
        
        $end_time=3600;
        
        
        if(($tokenTime+$end_time)>$toTome){
            //未到期
            //如果+10大于现在的时间，就是没过期
            session('user_id',$user_id);
            return 1;
        }else{
            //如果 + $end_time 秒小于或者等于现在的时间，就是过期了
            //到期了
            //删除令牌操作
            $where=[];
            $where['user_id']=$user_id;
            session(null);
            $model->where($where)->delete();
            return -991;
        }
    }else{
        //没有相关账户
        //未登录
        //没有令牌
        return -992;
    }
    
}
/**
* 创建目录
* set_mkdir
* =================================
* 创建日期：2017年12月16日11:31:58
* 作者：代码狮
* github：https://github.com/ALNY-AC
* 微信:AJS0314
* QQ:1173197065
* 留言：后来者想了解详细情况的请联系作者。
* =================================
*可以创建多级目录
*/
function set_mkdir($src) {
    //创建目录
    if (is_dir($src)) {
        //存在不创建
        return true;
    } else {
        //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
        $res = mkdir(iconv("UTF-8", "GBK", $src), 0777, true);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
/**
* +-----------------------------------------------------------------------------------------
* 删除目录及目录下所有文件或删除指定文件
* +-----------------------------------------------------------------------------------------
* @param str $path   待删除目录路径
* @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
* +-----------------------------------------------------------------------------------------
* @return bool 返回删除状态
* +-----------------------------------------------------------------------------------------
*/
function delFile($path, $delDir = false) {
    if (is_array($path)) {
        foreach ($path as $subPath)
        delFile($subPath, $delDir);
    }
    if (is_dir($path)) {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? delFile("$path/$item", $delDir) : unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        }
    } else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }
    clearstatcache();
}
/**
* 让商品数量减少
* 2018年1月29日17:52:05
*/
function decGoods($orderListId){
    
    //让商品数量减少
    // $ids[0]='201801291348504982';
    $where['order_id']=array('in',$orderListId);
    $model=M('order_info');
    $result=$model->where($where)->select();
    $goodsList=[];
    foreach ($result as $key => $value) {
        $order_info=$value['order_info'];
        $order_info=html($order_info);
        $order_info=json_decode($order_info,true);
        foreach ($order_info['goods_info'] as $j => $v) {
            $goodsList[]=$v;
        }
    }
    //找商品
    $model=M('goods');
    foreach ($goodsList as $key => $value) {
        $where=[];
        $where['goods_id']=$value['goods_id'];
        $num=$value['num'];
        $model->where($where)->setDec('goods_count',3);
    }
    
}

//获得订单详细信息，返回的是json型数据
function getOrderInfo($order_id){
    $model=M('order_info');
    $where=[];
    $where['order_id']=$order_id;
    $result= $model->where($where)->find();
    $orderInfo=$result['order_info'];
    $orderInfo=html($orderInfo);
    $orderInfo=json_decode($orderInfo,true);
    return $orderInfo;
}

//获得一个订单的总价
function getOrderMoney($orderInfo){
    $money=0;
    foreach ($orderInfo['goods_info'] as $key => $value) {
        $money+=($value['money']*$value['num']);
    }
    return $money;
}
//将数组转换为json字符串
function json($arr){
    
    return serialize($arr);
}
//字符串转换为数组
function stringToArr($arr,$map){
    
    for ($i=0; $i < count($arr); $i++) {
        
        
        for ($j=0; $j < count($map); $j++) {
            
            $arr[$i][$map[$j]]=unserialize($arr[$i][$map[$j]]);
            
            
        }
        
    }
    
    return $arr;
    
}
//遍历数组并将其中的数组转换为json
function arrToString($arr){
    
    
    foreach ($arr as $key => $value) {
        if(gettype($value)==='array'){
            $arr[$key]=serialize($value);
        }
    }
    
    return $arr;
    
}
//将字符串转换为json数组
function jsonD($arr ,$is=false){
    return json_decode($arr,$is);
}

//遍历，并将map中的字段转换为数组或json
function arrJsonD($arr,$map){
    
    // $result['img_list']=jsonD($result['img_list']);
    // $result['goods_class']=jsonD($result['goods_class']);
    // $result['spec']=jsonD($result['spec']);
    
    for ($i=0; $i < count($arr); $i++) {
        # code...
        foreach ($map as $key => $value) {
            
            $arr[$i][$key]=jsonD($arr[$i][$key],$value);
            
        }
        
    }
    
    
    
    
    return $arr;
}
//获得md5加密后的id
function getMd5($name="12138"){
    return md5($name.__KEY__.rand().time());
}
function getBagNum(){
    
    $where['user_id']=session('user_id');
    $bag=M('bag')->where($where)->select();
    $bag_num=0;
    for ($i=0; $i <count($bag) ; $i++) {
        $bag_num+=$bag[$i]['goods_count'];
    }
    $res['bag_num']=$bag_num;
    
    return $bag_num;
}
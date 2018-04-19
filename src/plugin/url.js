// @ts-nocheck
export default function (Vue, options) {

    // var server = 'http://cosmetics.com/index.php/';
    // var server = 'http://120.78.162.200:12138/index.php/';
    // var server = 'http://server.followenjoy.cn/index.php/';
    var server = 'http://server2.followenjoy.cn/index.php/';

    var serverAdmin = server + 'Admin/';
    var serverHome = server + 'Home/';
    var serverDefault = serverAdmin;

    //获得处理过的地址，主要用于获得图片的地址
    Vue.prototype.$getUrl = function (url) {

        if (!url) {
            return '';
        }
        //判断是不是相对路径 ./，如果是就不需要添加
        if (url.indexOf('./') >= 0) {
            //是相对路径 ./，所以直接返回
            return url;
        }

        //开始判断是不是http开头，如果是就不再添加头了
        var _url;
        if (url.indexOf('http') == -1) {
            var head = server;
            //取出index.php
            head = head.replace('index.php/', '');
            _url = head + url;
        } else {
            _url = url;
        }

        return _url;

    }
    //服务器地址
    Vue.prototype.$server = server;
    Vue.prototype.$serverAdmin = serverAdmin;
    Vue.prototype.$serverHome = serverHome;
    Vue.prototype.$serverDefault = serverDefault;
    //上传文件地址
    Vue.prototype.$serverUpFile = serverDefault + "Use/upFile";

}

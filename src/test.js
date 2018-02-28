// @ts-nocheck
var Url = {};
Url.install = function (Vue, options) {

    var server = 'http://cosmetics.com/index.php/';
    var serverAdmin = server + 'Admin/';
    var serverHome = server + 'Home/';

    //获得处理过的地址，主要用于获得图片的地址
    Vue.prototype.$getUrl = function (url) {

        if (!url) {
            console.warn('【url为空】：' + url);
            return '';
        }
        //开始判断是不是http开头，如果是就不再添加头了
        var _url;
        if (url.indexOf('http') == -1) {
            _url = server + url;
        } else {
            _url = url;
        }
        //取出index.php
        _url = _url.replace('index.php/', '');
        return _url;

    }
    //服务器地址
    Vue.prototype.$server = server;
    Vue.prototype.$serverAdmin = serverAdmin;
    Vue.prototype.$serverHome = serverHome;
    //上传文件地址
    Vue.prototype.$serverUpFile = serverAdmin + "Use/upFile";


}
//起源插件
var Origin = {};
Origin.install = function (Vue, options) {


    Vue.prototype.$get = function (url, data, success, error) {

        // if (data.token == null) {
        //     data.token = localStorage.token ? localStorage.token : '';
        // }
        // if (data.user_id == null) {
        //     data.user_id = localStorage.user_id ? localStorage.user_id : '';
        // }

        if (url.indexOf("http") == -1) {
            //没有http 
            url = this.$serverHome + url;
        }

        $.ajax({
            url: url,
            type: 'get',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            success(res) {

                try {

                    res = JSON.parse(res);

                } catch (error) {
                    console.error(url + '：接口出现错误！');
                    console.error(error);
                    console.error(res);
                    if (error) {
                        error(false, error);
                    }
                    return false;
                }

                //登录验证
                if (res.res == -992 || res.res == -991) {
                    //登录失败跳转登录页
                    router.push("/login");
                } else {
                    if (success) {
                        success(res);
                    }
                }
            }

        });

    }

    Vue.prototype.$post = function (url, data, success, error) {
        // if (data.token == null) {
        //     data.token = localStorage.token ? localStorage.token : '';
        // }
        // if (data.user_id == null) {
        //     data.user_id = localStorage.user_id ? localStorage.user_id : '';
        // }
        if (url.indexOf("http") == -1) {
            //没有http
            url = this.$serverHome + url;
        }
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            // crossDomain: true,
            success(res) {

                try {

                    res = JSON.parse(res);

                } catch (error) {
                    console.error(url + '：接口出现错误！');
                    console.error(error);
                    console.error(res);
                    if (error) {
                        error(false, error);
                    }
                    return false;
                }

                //登录验证
                if (res.res == -992 || res.res == -991) {
                    //登录失败跳转登录页
                    router.push("/login");
                } else {
                    if (success) {
                        success(res);
                    }
                }
            }

        });

    }

    Vue.prototype.$getTextCount = function (str) {
        if (str == null || str == undefined) {
            str = '';
        }
        return str.length;
    }


}
Vue.use(Origin)
Vue.use(Url)

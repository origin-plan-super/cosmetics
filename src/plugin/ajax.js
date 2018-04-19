import $ from "jquery";
import VueResource from 'vue-resource';

var Login = function () {
    this.noLogin = function (router) {
        router.replace("/login");
    }
}

export default function (Vue, options) {
    Vue.use(VueResource);

    //取得用户数据
    Vue.prototype.$getUserInfo = function () {
        if (!localStorage['adminUserInfo']) return {};
        return JSON.parse(localStorage.adminUserInfo);
    }
    Vue.prototype.$get = function (url, data, success, error) {
        if (!data) data = {};

        if (data.token == null) {
            data.token = localStorage.token ? localStorage.token : '';
        }
        if (data.admin_id == null) {
            data.admin_id = localStorage.admin_id ? localStorage.admin_id : '';
        }

        if (url.indexOf("http") == -1) {
            //没有http
            url = this.$serverDefault + url;
        }

        $.ajax({
            url: url,
            type: 'get',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            success: res => {
                if (res == '' || res == null || res == undefined) {
                    this.$message({ type: "warning", message: '接口并没有返回任何数据！' })
                    console.warn("接口并没有返回任何数据！");
                    return;
                }

                try {

                    res = JSON.parse(res);

                } catch (e) {
                    this.$message({ type: "error", message: '数据在转换为JSON时出现错误！' })
                    if (error) error(false, error);

                    return false;
                }


                if (res['res']) {

                    //登录验证
                    if (res.res == -992 || res.res == -991) {
                        //登录失败跳转登录页
                        new Login().noLogin(this.$router);
                    } else {
                        if (success) {
                            success(res);
                        }
                    }

                } else {
                    if (success) {
                        success(res);
                    }
                }

            },
            error: () => {
                this.$message({ type: "error", message: '接口错误！' })
                if (error) error(false, error);

            }
        });
    }

    Vue.prototype.$post = function (url, data, success, error) {
        if (!data) data = {};

        if (data.token == null) {
            data.token = localStorage.token ? localStorage.token : '';
        }
        if (data.admin_id == null) {
            data.admin_id = localStorage.admin_id ? localStorage.admin_id : '';
        }
        if (url.indexOf("http") == -1) {
            //没有http
            url = this.$serverDefault + url;
        }
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            // crossDomain: true,
            success: res => {
                if (res == '' || res == null || res == undefined) {
                    console.warn("接口并没有返回任何数据！");
                    this.$message({ type: "warning", message: '接口并没有返回任何数据！' })
                    return;
                }

                try {

                    res = JSON.parse(res);

                } catch (e) {
                    this.$message({ type: "error", message: '数据在转换为JSON时出现错误！' })
                    if (error) error(false, error);

                    return false;
                }

                if (res['res']) {

                    //登录验证
                    if (res.res == -992 || res.res == -991) {
                        //登录失败跳转登录页
                        new Login().noLogin(this.$router);
                    } else {
                        if (success) {
                            success(res);
                        }
                    }

                } else {
                    if (success) {
                        success(res);
                    }
                }


            },
            error: () => {
                this.$message({ type: "error", message: '接口错误！' })
                if (error) error(false, error);
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
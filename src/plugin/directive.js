// @ts-nocheck
import $ from "jquery";

//自定义指令

export default function (Vue, options) {

    function focus(el, binding) {
        if (binding.value) {
            $(el).find('input').focus();
        }
    }

    function img(el, binding) {
        if (binding.value) {
            var img = Vue.prototype.$getUrl(binding.value);
            $(el).attr('src', img);
        }
    }

    //自动获取焦点
    Vue.directive('focus', {
        inserted: focus,
        update: focus,
        componentUpdated: focus,
    })
    //图片处理指令
    Vue.directive('img', {
        inserted: img,
        update: img,
        componentUpdated: img,
    })

    Vue.filter('isNull', function (value) {

    })


}

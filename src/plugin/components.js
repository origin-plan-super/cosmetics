// @ts-nocheck
//自定义组件

import load from "@/component/load/load.vue";//加载
import OImg from "@/component/OImg/OImg.vue";//预加载图片
import ImgList from "@/component/img-list/img-list.vue";//可拖拽的图片列表
import OUpload from "@/component/o-upload/o-upload.vue";//上传组件

import ClassSelect from "@/component/class-select/class-select.vue";//商品类别选择器

import SupplierSelect from "@/component/select-supplier/select-supplier.vue";//供货商选择器


import SelectFreight from "@/component/select-freight/select-freight.vue";//运费模板选择器

import FreightArea from "@/component/freight-area/freight-area.vue";//区域列表

import SelectArea from "@/component/select-area/select-area.vue";//区域列表


export default function (Vue, options) {

    Vue.component('load', load);
    Vue.component('OImg', OImg);
    Vue.component(OUpload.name, OUpload);
    Vue.component(ImgList.name, ImgList);

    Vue.component(ClassSelect.name, ClassSelect);

    Vue.component(SupplierSelect.name, SupplierSelect);

    Vue.component(SelectFreight.name, SelectFreight);

    Vue.component(FreightArea.name, FreightArea);

    Vue.component(SelectArea.name, SelectArea);


    //二次封装饿了么的消息插件
    Vue.prototype.$warn = function (msg) {
        this.$message({ type: "warning", message: msg });
    }

    Vue.prototype.$error = function (msg) {
        this.$message({ type: "error", message: msg });
    }

    Vue.prototype.$info = function (msg) {
        this.$message({ type: "info", message: msg });
    }

    Vue.prototype.$success = function (msg) {
        this.$message({ type: "success", message: msg });
    }

}

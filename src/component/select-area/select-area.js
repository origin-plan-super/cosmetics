// @ts-ignore
import AreaPanel from "./area-panel/area-panel.vue";
import getAddressData from "./proCityCountry.js";


export default {
    name: 'select-area',
    props: {
        value: {
            type: [Array, Object],
            default: [],
        }
    },
    data() {
        return {

            addressData: (function () {
                let list = getAddressData();
                list.forEach(item => {
                    item.check = false;
                });
                return list;
            })(),

            provinces: [],//省
            citys: [],//市
            countys: [],//区

            provincesSelect: null,//省
            citysSelect: null,//市
            countysSelect: null,//区

            selectListProvinces: [],//省选择的列表
            selectListCitys: [],//市选择的列表
            selectListCountys: [],//区选择的列表
            selectList: [],
            tree: [],//用于展示的树


            isShopGHelp: false,
        };
    },
    methods: {
        update() {
            //构建省数据
            this.provinces = this.addressData.filter(
                item => !item['parent']
            );
        },
        reset() {
            this.addressData.forEach(item => {
                item.check = false;
            });
            this.selectListProvinces = [];
            this.selectListCitys = [];
            this.selectListCountys = [];
            this.selectList = [];
            this.countysSelect = null;
            this.citysSelect = null;
            this.provincesSelect = null;

            this.citys = [];
            this.countys = [];

        },
        change(isUpdate = true) {
            var addressData = this.addressData;

            // let list = [
            //     this.selectListProvinces,
            //     this.selectListCitys,
            //     this.selectListCountys
            // ];

            this.selectList = this.addressData.filter(
                item => item.check
            );

            let list = [];

            this.selectList.forEach(item => {
                let xyz = {};
                for (let x in item) {
                    if (x != 'check') {
                        xyz[x] = item[x];
                    }
                }
                list.push(xyz);
            });

            //组装树，用于展示



            let tree = [];

            this.selectList.forEach(item => {
                let node = {
                    label: item.label,
                    children: [],
                    value: item.value,
                };

                if (!item['parent']) {
                    //没有上级，是根节点
                    tree.push(node);

                } else {
                    node.parent = item.parent;
                    //有上级
                    //找上级
                    //先找tree中有没有
                    let parent = getTreeItem(node);
                    parent.children.push(node);

                }

            });


            //获得tree中的指定一项
            function getTreeItem(activeItem) {
                let select = {};
                tree.forEach(item => {
                    if (item.value == activeItem.parent) {
                        select = item;
                        return false;
                    } else {
                        item.children.forEach(node => {
                            if (node.value == activeItem.parent) {
                                select = node;
                                return false;
                            }
                        });
                    }
                });

                if (JSON.stringify(select) == '{}') {

                    //如果没有上级，要创建上级
                    addressData.forEach(item => {
                        if (item.value == activeItem.parent) {
                            item.check = true;
                            let node = {
                                label: item.label,
                                children: [],
                                value: item.value,
                            };

                            if (item['parent']) {
                                node.parent = item.parent;
                                //有上级
                                //找上级
                                addressData.forEach(item2 => {

                                    if (item.parent == item2.value) {
                                        //
                                        item2.check = true;
                                        let node2 = {
                                            label: item2.label,
                                            children: [node],
                                            value: item2.value,
                                        };
                                        tree.push(node2);
                                    }

                                });

                            } else {
                                tree.push(node);
                            }
                            select = node;
                            return false;
                        }
                    });

                }

                return select;
            }

            this.tree = tree;
            if (isUpdate) {
                this.setValue(list);
            }

        },
        setValue(list) {
            this.$emit('input', list);
        },
        handleNodeClick(data) {
            console.log(data);
        },
        remove(data) {

            this.addressData.forEach(item => {
                if (data.value == item.value) {
                    item.check = false;
                    return false;
                }
            });
            if (data.children.length > 0) {
                data.children.forEach(item => {
                    this.remove(item);
                });
            }

            this.change();
        },
        setDate() {
            this.value.forEach(item => {
                this.addressData.forEach(xyz => {
                    if (item.value == xyz.value) {
                        xyz.check = true;
                    }
                });
            });
            this.change(false);
        }
    },
    computed: {},
    //过滤器
    filters: {},
    mounted() {
        this.update();

        this.$nextTick(() => {
            this.setDate();
        });

    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {
        [AreaPanel.name]: AreaPanel
    },
    watch: {
        provincesSelect(val) {


            if (!val || !val['value']) {
                this.citys = [];
                return;
            }
            this.citys = this.addressData.filter(
                item => item['parent'] && item['parent'] == val.value
            );
            this.countys = [];


        },
        citysSelect(val) {

            if (!val || !val['value']) {
                this.countys = [];
                return;
            }
            this.countys = this.addressData.filter(
                item => item['parent'] && item['parent'] == val.value
            );


        },
        value(val) {
            this.setDate();
        },
        selectListProvinces() {
            this.change();
        },
        selectListCitys() {
            this.change();
        },
        selectListCountys() {
            this.change();
        },
    }
};
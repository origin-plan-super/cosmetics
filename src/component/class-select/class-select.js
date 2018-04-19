export default {
    name: "class-select",
    props: {
        value: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            list: [],
            select: '',
            isLoad: false,
        };
    },
    methods: {
        //获取分类数据
        update() {
            this.isLoad = true;
            //取得商品分类
            this.$get("Class/getList", {}, res => {
                if (res.count > 0) {
                    this.list = this.bilider(res.msg);
                    this.isLoad = false;
                }
            });
        },
        //构建分类列表，设置层级关系，组成树
        bilider(list) {
            var tree = [];

            list.forEach(item => {
                item.sort = parseInt(item.sort);
                //没有上级代表是跟节点
                if (!item.super_id) {
                    let root = item;
                    root.children = [];
                    tree.push(root);
                }
            });

            list.forEach(item => {

                //如果上级id，就寻找上级id，并且插入
                if (item.super_id) {

                    tree.forEach(root => {
                        if (item.super_id == root.class_id) {
                            root.children.push(item);
                        }
                    });

                }


            });

            return tree;
        },
    },
    computed: {},
    //过滤器
    filters: {},
    mounted() {
        this.update();
        this.$nextTick(() => { });
    },
    //Vue 实例销毁后调用
    destroyed() { },
    watch: {
        select(val) {
            this.$emit('input', val);
        }
    },
    components: {}
};
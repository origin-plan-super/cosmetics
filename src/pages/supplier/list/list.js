export default {
    name: 'list',
    props: {},
    data() {
        return {
            // 当前页
            currentPage: 1,
            // 总条数
            total: 0,
            // 当前每页显示的数量
            pageSize: 20,
            //表格数据
            tableData: [],
            //表格是否显示加载层
            tableLoading: false,
            //记录用的值
            testValue: "",
            //被选中项
            selectItem: [],
            //是否是保存数据状态
            isPreservation: false,
        };
    },
    methods: {
        //页面切换事件
        handleCurrentChange: function (val) {
            this.currentPage = val;
            this.update();
        },
        //大小改变事件
        handleSizeChange: function (val) {
            this.pageSize = val;
            this.update();
        },
        //取得供应商列表
        update() {
            this.tableLoading = true;
            this.$get('supplier/getList', {}, res => {
                this.tableLoading = false;
                this.total = res.count;
                if (res.res > 0) this.tableData = res.msg;
            });
        },
        rowKey(item) {
            return item.feeback_id;
        },
        // 当选择项发生变化时会触发该事件
        selectionChange(items) {
            this.selectItem = items;
        },
        edit(item) {
            this.$router.push({
                path: '/supplier/edit',
                query: {
                    supplier_id: item.supplier_id
                }
            });
        },
        del(item, i, list) {
            this.$post(
                "supplier/del",
                {
                    ids: [item.supplier_id]
                },
                res => {

                    if (res.res >= 1) {
                        this.$success("删除成功~");
                        list.splice(i, 1);
                        this.total -= 1;

                        return;
                    }
                    this.$error("删除失败！请重试~");
                }
            );
        },
        dels() {
            let ids = [];
            this.selectItem.forEach(item => {
                ids.push(item.supplier_id);
            });
            this.$post(
                "supplier/del",
                {
                    ids: ids
                },
                res => {
                    this.selectItem = [];
                    console.log(res);
                    if (res.res >= 1) {
                        this.$success("删除成功！");
                        this.tableData = this.tableData.filter(
                            item => ids.indexOf(item.supplier_id) < 0
                        );
                        this.total -= ids.length;
                        return;
                    }
                    this.$error("删除失败！");
                }
            );
        },
        showOrder(item) {

            // supplier_orderList
            this.$router.push({
                path: '/supplier/orderList',
                query: {
                    supplier_id: item.supplier_id
                }
            });

        }
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
    components: {},
    watch: {}
};
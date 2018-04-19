export default {
    name: "orderList",
    data() {
        return {
            // 当前页
            currentPage: 1,
            // 总条数
            total: 0,
            // 当前每页显示的数量
            pageSize: 10,
            //表格数据
            tableData: [],
            //表格是否显示加载层
            tableLoading: false,
            //记录用的值
            testValue: "",
            //被选中项
            selectItem: [],
            //状态值
            state: [
                {
                    icon: "fa fa-credit-card",
                    value: "未支付",
                    text: "未支付",
                    type: "text-info"
                },
                {
                    icon: "fa fa-cube",
                    value: "未发货",
                    text: "未发货",
                    type: "text-warning"
                },
                {
                    icon: "fa fa-truck",
                    value: "已发货",
                    text: "已发货",
                    type: "text-primary"
                },
                {
                    icon: "el-icon-success",
                    value: "已签收",
                    text: "已签收",
                    type: "text-success"
                },
                {
                    icon: "el-icon-service",
                    value: "退款/售后",
                    text: "退款/售后",
                    type: "text-danger"
                }
            ],
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
        update: function (showInfo, message) {
            var setTim = setTimeout(() => {
                this.tableLoading = true;
            }, 500);

            this.$get(
                "order/getList",
                { page: this.currentPage, limit: this.pageSize },
                res => {

                    clearTimeout(setTim);
                    this.tableLoading = false;
                    console.log(res);
                    if (res.res >= 1) {
                        this.total = res.count;
                        this.tableData = res.msg;
                        if (showInfo) {
                            this.$message({
                                message: message ? message : "更新完成~",
                                type: "success"
                            });
                        }

                    }

                }
            );
        },
        // 保存
        save(item, saveName, isInfo, isValidate) {
            if (isValidate && item[saveName] == this.testValue) return;

            var save = {};
            save[saveName] = item[saveName];

            this.$post(
                "order/save",
                { where: { order_id: item.order_id }, save: save },
                res => {
                    if (res.res >= 1 && isInfo) {
                        this.$message({ message: "保存成功！", type: "success" });
                    }
                    if (res.res < 0) {
                        this.$message({ message: "保存失败！请重试！", type: "error" });
                    }
                }
            );
        },
        editorder(item) {
            this.$router.push({ name: "/order/edit", params: { order: item } });
        },
        rowKey(item) {
            return item.order_id;
        },
        //记录值
        recordValue(value) {
            this.testValue = value;
        },
        setUpAll(is_up) {
            // this.$refs["].
            var list = this.selectItem;
            for (let i = 0; i < list.length; i++) {
                list[i].is_up = is_up + "";
                this.save(list[i], "is_up", false);
            }
        },
        // 当选择项发生变化时会触发该事件
        selectionChange(items) {
            this.selectItem = items;
        },
        see(item) {
            this.$router.push({
                name: "/order/info",
                query: { order_id: item.order_id }
            });
        },

        filterMethod(value, row, column) {
            if (!this.state[row.state]) return true;
            return this.state[row.state].text === value;

            // const property = column["property"];
            // return row[property] ===asdasdasdas12312 value;
        }
    },
    mounted: function () {
        this.update();

    },
    destroyed() {
    },
    watch: {}
};


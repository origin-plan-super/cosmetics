export default {
    name: "info",
    data() {
        return {
            order_id: "",
            order: null,
            refreshBtnLoad: false,
            info: {
                type: "",
                text: "",
                isShow: false,
                close: false,
                icon: false
            },
            testValue: ""
        };
    },
    methods: {
        update() {
            this.refreshBtnLoad = true;
            this.$get("order/get", { order_id: this.order_id }, res => {
                this.refreshBtnLoad = false;
                console.log(res);
                if (res.res >= 1) {
                    this.order = res.msg;
                    return;
                }
                if (res.res < 0) {
                    //订单不存在
                    this.info.text = `订单获取失败，请重试！ 订单号：[ ${
                        this.order_id
                        } ]`;
                    this.info.isShow = true;
                    this.info.type = "error";

                    this.$message({
                        showClose: true,
                        type: "error",
                        message: `订单获取失败，请重试！`
                    });
                }
            });
        },
        saveExpressNumber() {
            if (this.order.express_number == this.testValue) return;
            var save = {
                express_number: this.order.express_number
            };

            this.$post(
                "order/save",
                { where: { order_id: this.order_id }, save: save, table: "orderInfo" },
                res => {
                    if (res.res >= 1) {
                        this.$message({ message: "保存成功！", type: "success" });
                    }
                    if (res.res < 0) {
                        this.$message({ message: "保存失败！请重试！", type: "error" });
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
        }
    },
    mounted() {
        this.order_id = this.$route.query.order_id;
        this.update();
    },
    components: {},
    watch: {}
};
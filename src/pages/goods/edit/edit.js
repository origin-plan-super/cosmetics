// @ts-ignore
import spec from "@/component/spec/spec.vue";
// @ts-ignore
import editor from "@/component/editor/editor.vue";
// @ts-ignore
import selectImg from "@/component/selectImg/selectImg.vue";

export default {
    name: "goodsAdd",
    data() {
        return {
            model: "add",
            show: {
                spec: true
            },
            logistics: {
                template: [{ title: "免邮" } /*{ title: "按体积算" }*/]
            },
            goods_class: [],
            goods: {
                sku: [],
                tree: [],
                //标题
                goods_title: "",
                //物流模板
                logistics: "免邮",
                //是否立刻上架
                is_up: "0",
                //商品分类
                goods_class: "",
                //图片
                img_list: [],
                //详情
                goods_content: "",
                is_unique: "0",
                supplier_id: ''//供货商id
            },
            //验证条件
            rules: {
                goods_title: [
                    { required: true, message: "请输入商品标题", trigger: "blur" },
                    { max: 255, message: "长度小于255个字符", trigger: "blur" }
                ],
                logistics: [
                    { required: true, message: "请选择物流模板", trigger: "blur" }
                ],
                goods_class: [
                    { required: true, message: "请选择商品分类", trigger: "blur" }
                ]
            }
        };
    },
    methods: {
        cancelar() {
            this.$router.replace("/goods/list");
        },
        submitForm() {
            this.goods.goods_content = this.$refs["editor"].getContent();
            var goods = this.goods;
            this.$refs["goodsForm"].validate(valid => {
                if (valid) {
                    //验证成功
                    this.$message({ message: "正在提交", type: "info" });
                    const loading = this.$loading({
                        lock: true,
                        text: "正在提交……",
                        spinner: "el-icon-loading",
                        background: "rgba(0, 0, 0, 0.7)"
                    });
                    if (this.model == "add") {
                        this.$post("goods/add", { add: goods }, res => {
                            loading.close();
                            if (res.res == 1) {
                                this.$message({ message: "提交成功", type: "success" });
                                setTimeout(() => {
                                    this.$router.go(-1);
                                }, 300);
                            }
                            if (res.res < 0) {
                                this.$message({ message: "提交失败！请重试！", type: "error" });
                            }
                        });
                    }

                    if (this.model == "edit") {
                        this.$post("goods/save", { save: goods }, res => {
                            loading.close();
                            if (res.res == 1) {
                                this.$message({ message: "保存成功", type: "success" });
                                setTimeout(() => {
                                    this.$router.go(-1);
                                }, 300);
                            }
                            if (res.res < 0) {
                                this.$message({
                                    message: "保存失败！请重试！",
                                    type: "error"
                                });
                            }
                        });
                    }
                } else {
                    this.$message({ message: "缺少必填项！", type: "warning" });
                    return false;
                }
            });
        }
    },
    mounted: function () {
        // 如果路由传来了商品的id，就异步获取商品的信息，并且设置到goods上，
        // 如果路由没有传来任何商品的id，就代表是添加模式。
        // 默认是添加模式

        if (this.$route.query["goods_id"]) {
            this.model = "edit";
            var goods_id = this.$route.query["goods_id"];
            this.$get(
                "goods/get",
                {
                    goods_id: goods_id
                },
                res => {
                    console.log(res.msg);

                    this.goods = res.msg;
                }
            );
        }
    },
    components: {
        spec,
        selectImg,
        editor
    }
};

export default {
    name: 'add',
    props: {},
    data() {
        return {
            add: {
                supplier_login_id: '',
                supplier_name: "",
                company_name: "",
                company_tel: "",
                company_province: "",//省
                company_city: "",//市
                company_county: "",//区县
                company_address_detail: "",
                name: "",
                telephone: "",
                phone: "",
                email: "",
                qq: "",
                weixin: "",
                gender: "",
                file_src: '',
            },
            address: [],
            rules: {
                supplier_login_id: [
                    { required: true, message: '请输入账号登录名', trigger: 'blur' },
                    { max: 32, message: '长度不能超过 32 个字符', trigger: 'blur' }
                ],
                supplier_name: [
                    { required: true, message: '请输入供货商名称', trigger: 'blur' },
                    { max: 255, message: '长度不能超过 255 个字符', trigger: 'blur' }
                ],

            }
        };
    },
    methods: {
        submitForm() {

            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.$post('supplier/add', { add: this.add }, res => {
                        if (res.res == -2) {
                            this.$warn('已经存在供货商！');
                            return;
                        }

                        if (res.res >= 1) {
                            this.$success('保存成功！');
                            setTimeout(() => {
                                this.$router.go(-1);
                            }, 200);

                            return;
                        }
                        this.$error('操作失败！请重试');

                    });

                } else {
                    this.$warn('缺少必填项~');
                    return false;
                }
            });
        },
        resetForm() {
            this.$refs['form'].resetFields();
        },
    },
    computed: {

        uploadText() {

            if (this.add.file_src.length <= 0) {
                return '上传';
            } else {
                return '重新上传';
            }

        }


    },
    //过滤器
    filters: {},
    mounted() {
        this.$nextTick(() => { });
    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {},
    watch: {
        address(val) {
            this.add.company_province = val[0];
            this.add.company_city = val[1];
            this.add.company_county = val[2];
        }
    }
};
export default {
    name: 'edit',
    props: {},
    data() {
        return {
            supplier_id: '',
            save: null,
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
        update() {

            this.$get('supplier/get', {
                supplier_id: this.supplier_id
            }, res => {
                this.save = res.msg;


                if (this.save.company_province.length > 0) {
                    this.address[0] = this.save.company_province;
                    this.address[1] = this.save.company_city;
                    this.address[2] = this.save.company_county;
                } else {
                    this.address = [];
                }


            });
        },
        submitForm() {

            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.$post('supplier/save', { supplier_id: this.supplier_id, save: this.save }, res => {
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

            if (this.save.file_src.length <= 0) {
                return '上传';
            } else {
                return '重新上传';
            }

        }
    },



    //过滤器
    filters: {},
    mounted() {
        this.supplier_id = this.$route.query.supplier_id;
        this.update();
        this.$nextTick(() => { });
    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {},
    watch: {
        address(val) {
            this.save.company_province = val[0];
            this.save.company_city = val[1];
            this.save.company_county = val[2];
        }
    }
};
export default {
    name: 'add',
    props: {},
    data() {
        return {
            data: {
                freight_name: '',
                freight_type: 2,
                value_type: 1,
                areas: [],
            },
            rules: {
                freight_name: [
                    { required: true, message: '请输入模板名称', trigger: 'blur' },
                    { max: 255, message: '长度不能超过255个字符', trigger: 'blur' }
                ],

            }
        };
    },
    methods: {
        submitForm() {
            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.$post('freight/creat', {
                        add: this.data,
                    }, res => {
                        if (res.res >= 1) {
                            this.$success('添加成功！');
                            setTimeout(() => {
                                this.$router.go(-1);
                            }, 300);
                            return;
                        }
                        this.$error('添加失败请重试！');
                    });
                } else {
                    this.$warn('缺少必填项！');
                    return false;
                }
            });
        },
        resetForm() {
            this.$refs['form'].resetFields();
        }
    },
    computed: {},
    //过滤器
    filters: {},
    mounted() {
        this.$nextTick(() => { });
    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {},
    watch: {
        'data.freight_type'(val) {

            if (this.data.areas.length > 0 && val == 1) {
                this.data.freight_type = 2;
                this.$confirm('切换运费计算方式后，所有区域的运费将设置为0，且当前运费设置信息将会被清空，请再次确认', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.data.areas = [];
                    this.data.freight_type = 1;
                }).catch(() => {
                });
            }


        }
    }
};
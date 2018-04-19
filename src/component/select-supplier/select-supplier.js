export default {
    name: 'select-supplier',
    props: {
        value: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            list: [],
            isLoading: false,
            select: ''
        };
    },
    methods: {
        update() {
            this.isLoading = true;
            this.$get('supplier/getList', {}, res => {
                this.isLoading = false;
                console.log(res);
                this.list = res.msg;
            });
        }
    },
    computed: {},
    //过滤器
    filters: {},
    mounted() {
        this.update();
        this.$nextTick(() => { })
    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {},
    watch: {
        select(val) {
            this.$emit('input', val);
        },
        value(val) {
            this.select = val;
        }
    }
};
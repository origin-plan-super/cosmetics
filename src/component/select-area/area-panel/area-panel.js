export default {
    name: 'area-panel',
    props: {
        list: {
            type: Array,
            defalut: []
        },
        addressData: {
            type: Array,
            defalut: []
        },
        title: {
            type: String,
            defalut: ''
        },
        value: {
            type: [Object, Array, String],
            defalut: []
        }
    },
    data() {
        return {
            addressList: [],
            selectAll: false,
            selectItem: null,
        };
    },
    methods: {
        select(item, i, list) {
            this.$emit('change', this.list[i]);
        },
        //选中项发生变化
        change() {
            let selectList = this.addressList.filter(
                item => item.check
            );
            this.$emit('input', selectList);
        },
        bulider(list) {
            this.addressList = [];
            this.addressList = list.slice();
        }
    },
    computed: {},
    //过滤器
    filters: {},
    mounted() {
        this.bulider(this.list);
        this.$nextTick(() => { })
    },
    //Vue 实例销毁后调用
    destroyed() { },
    components: {},
    watch: {
        list(list) {
            this.bulider(list);
        },
        selectAll(val) {
            this.addressList.forEach(item => {
                item.check = val;
            });
            this.change();
        }
    }
};
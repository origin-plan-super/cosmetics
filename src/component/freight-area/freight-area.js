export default {
    name: 'freight-area',
    props: {
        value: {
            type: Array,
            default: []
        },
        valueType: {
            type: Number,
            default: 1,
        },
        type: {
            type: String,
            default: 'look',
        }
    },
    data() {
        return {
            areas: [],
            isShowSelectArea: false,
            manyAreaValue: [],
            isShopGHelp: false,//帮助模态框
            selectItem: null,
            isShowWarn: false,
        };
    },
    methods: {
        setSelectArea() {
            let item = this.selectItem;
            item.areasInfo = this.manyAreaValue;
            item.area = JSON.stringify(this.manyAreaValue);
            this.isShowSelectArea = false;
            this.$refs['select-area'].reset();
        },
        selectArea(item) {
            this.selectItem = item;
            this.manyAreaValue = item.areasInfo;
            this.isShowSelectArea = true;
        },
        del(item, i, list) {
            list.splice(i, 1);

        },
        add() {
            let area = {
                area: '',
                areasInfo: [],
                first: '0',
                first_price: '0',
                continued: '0',
                continued_price: '0',
            };
            this.areas.push(area);
        }
    },
    computed: {
        isType() {
            let text = '个';
            switch (this.valueType) {
                case 1:
                    text = "个";
                    break;
                case 2:
                    text = "kg";
                    break;
                case 3:
                    text = "m³";
                    break;
                default:
                    text = "个";
                    break;
            }
            return text;
        }
    },
    //过滤器
    filters: {},
    mounted() {
        this.areas = this.value;
        this.$nextTick(() => {
        })
    },
    //Vue 实例销毁后调用
    destroyed() {
        // this.$emit('input', []);
    },
    components: {},
    watch: {
        value(val) {
            this.areas = val;
        },
        areas(val) {
            this.$emit('input', val);
        }
    }
};
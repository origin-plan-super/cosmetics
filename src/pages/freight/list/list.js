export default {
    name: 'list',
    props: {},
    data() {
        return {
            list: [],
        };
    },
    methods: {
        update() {
            this.$get('freight/getList', {}, res => {
                if (res.res >= 1) {
                    this.list = this.bulider(res.msg);
                }
            });
        },
        bulider(list) {

            list.forEach(item => {
                item.value_type = parseInt(item.value_type);
                item.freight_type = parseInt(item.freight_type);

                item.areas.forEach(area => {
                    area.areasInfo = JSON.parse(area.area);
                });
            });
            return list;
        },
        edit(item, i, list) {
            this.$router.push({
                path: 'edit',
                query: {
                    freight_id: item.freight_id
                }
            });
        },
        del(item, i, list) {

            this.$post('freight/del', {
                ids: [item.freight_id]
            }, res => {
                if (res.res >= 1) {
                    this.$success('删除成功！');
                    list.splice(i, 1);
                    return;
                }
                this.$error('操作失败！请重试！');
            });

        },
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
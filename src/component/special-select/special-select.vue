<template>
  <div class="special-select">

    <template v-for="(item,i) in list">

      <special-card size="small" :key="item.special_id" :bg="item.special_head" :special-id="item.special_id" :title="item.special_title" :info="item.special_title_2" :open="false" @click="select(item,i,list)">
        <transition name="right-fade-in">
          <div class="select-box" v-if="item.isActive">
            <i class="el-icon-check"></i>
          </div>
        </transition>

      </special-card>

    </template>
    <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :page-sizes="[1,5,10]" :total="total">
    </el-pagination>
  </div>
</template>
<script>
import specialCard from "../special-card/special-card.vue";

export default {
  name: "special-select",
  props: {
    value: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 5,
      //数据
      list: [],
      //是否显示加载层
      isLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectList: []
    };
  },
  methods: {
    //页面切换事件
    handleCurrentChange: function(val) {
      this.currentPage = val;
      this.update();
    },
    //大小改变事件
    handleSizeChange: function(val) {
      this.pageSize = val;
      this.update();
    },
    update() {
      var setTim = setTimeout(() => {
        this.isLoading = true;
      }, 500);
      this.list = [];
      this.$get(
        "special/getList",
        {
          page: this.currentPage,
          limit: this.pageSize
        },
        res => {
          clearTimeout(setTim);
          this.isLoading = false;
          this.total = res.count;
          if (res.count > 0) this.list = this.buliderList(res.msg);
        }
      );
    },
    buliderList(list) {
      for (let i = 0; i < list.length; i++) {
        list[i].isActive = false;
      }
      return list;
    },
    select(item, i, list) {
      item.isActive = !item.isActive;
      this.updateInput();
    },
    updateInput() {
      let list = this.list.filter(item => item.isActive);
      this.$emit("input", list);
    }
  },
  computed: {},
  //过滤器
  filters: {},
  mounted() {
    this.update();
    this.$nextTick(() => {});
  },
  //Vue 实例销毁后调用
  destroyed() {},
  watch: {},
  components: {
    specialCard
  }
};
</script>
<style lang="scss" scoped>
@import "special-select.scss";
</style>
<template>
  <div class="goods-select">

    <div class="goods-list clearfix">
      <div :class="['goods-item',{'active':item.isActive}]" @click="selectItem(item,i,list)" v-for="(item,i) in list" :key="item.goods_id">
        <div class="active-icon">
          <i class="fa fa-check"></i>
        </div>
        <img :src="$getUrl(item.img_list[0].src)" class="goods-img" alt="">
        <div class="goods-title">
          {{item.goods_title}}
        </div>
        <div class="goods-money">
          ￥ {{item.sku[0].price}}
        </div>
      </div>
    </div>

    <div class="frame">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :page-sizes="[1,10, 20, 30, 40, 50, 100]" :total="total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
export default {
  name: "goods-select",
  props: {
    value: {
      type: Array,
      default() {
        return [];
      }
    }
  },
  data() {
    return {
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 20,
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
    handleCurrentChange: function() {
      this.update();
    },
    //大小改变事件
    handleSizeChange: function() {
      this.update();
    },
    update: function() {
      //   this.list = [];
      var setTim = setTimeout(() => {
        this.isLoading = true;
      }, 500);
      this.$get(
        "goods/getList",
        {
          page: this.currentPage,
          limit: this.pageSize,
          field: "goods_id,goods_title,spec,img_list"
        },
        res => {
          clearTimeout(setTim);
          this.isLoading = false;
          this.total = res.count;
          if (res.count > 0) this.buliderList(res.msg);
        }
      );
    },
    buliderList(list) {
      for (let i = 0; i < list.length; i++) {
        list[i].isActive = false;
      }
      this.list = list;
      this.buliderSelectList();
    },
    selectItem(item, index, list) {
      item.isActive = !item.isActive;
      var selectList = this.selectList;
      if (item.isActive == false) {
        //移除
        for (let j = 0; j < selectList.length; j++) {
          const item_s = selectList[j];
          if (item.goods_id == item_s.goods_id) {
            selectList.splice(j, 1);
            //然后就停止运行，返回就好
            break;
          }
        }
      } else {
        //添加
        selectList.push(item);
      }
      this.$emit("input", selectList);
    },
    buliderSelectList() {
      for (let i = 0; i < this.list.length; i++) {
        let item = this.list[i];

        for (let j = 0; j < this.selectList.length; j++) {
          let ietm_s = this.selectList[j];

          if (item.goods_id === ietm_s.goods_id) {
            if (ietm_s.isActive) {
              this.selectList[j] = item;
              this.selectList[j].isActive = true;
              break;
            }
          }
        }
      }
    }
  },
  computed: {},
  mounted() {
    this.update();
  },
  destroyed() {
    this.selectList = [];
    this.$emit("input", []);
  },
  components: {},
  watch: {
    selectList() {}
  }
};
</script>
<style lang="scss" scoped>
@import "goodsSelect.scss";
</style>
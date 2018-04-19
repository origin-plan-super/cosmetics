<template>
  <div class="goods-select">

    <div class="goods-list clearfix" v-if="list.length>0">

      <template v-for="(item,i) in list">
        <goods-card @click="selectItem(item,i,list)" :class="['goods-item',{'active':item.isActive}]" :title="item.goods_title" :info="'￥'+(item.sku.length>0?item.sku[0].price:'暂无价格')" :img="item.img_list.length>0?item.img_list[0].src:''" :key="item.goods_id+'412'">
          <div class="active-icon" v-if="item.isActive">
            <i class="fa fa-check"></i>
          </div>
        </goods-card>
      </template>

    </div>
    <div v-else>
      <p class="text-info">
        暂无商品，请先将商品上线或
        <span @click="$router.push('/goods/edit')">点击添加</span>
      </p>
    </div>

    <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :page-sizes="[1,10, 20, 30, 40, 50, 100]" :total="total">
    </el-pagination>

  </div>
</template>
<script>
import goodsCard from "../goods-card/goods-card.vue";

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
      pageSize: 10,
      //数据
      list: [],
      //是否显示加载层F
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
          field: "goods_id,goods_title,spec,img_list",
          where: {
            is_up: 1
          }
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
  components: {
    goodsCard
  },
  watch: {
    selectList() {}
  }
};
</script>
<style lang="scss" scoped>
@import "goodsSelect.scss";
</style>
<template>
  <div id="starGoodsList">
    <div class="frame">
      <el-button size="mini" icon="el-icon-refresh" @click="update()" :loading="tableLoading"></el-button>
    </div>
    <div class="frame">

      <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

        <el-table-column type='selection' align="center"></el-table-column>

        <el-table-column width="70" align="center" label="图片">
          <template slot-scope="scope">
            <img :src="$getUrl(scope.row.img_list[0].src)" v-if="scope.row.img_list.length>0" class="table-goods-img" alt="图片错误！">
          </template>
        </el-table-column>

        <el-table-column prop="goods_title" width="200" label="商品名称" show-overflow-tooltip></el-table-column>

        <el-table-column prop="" label="销售价 (￥)"></el-table-column>
        <el-table-column prop="" label="星级折扣 (%)"></el-table-column>
        <el-table-column prop="" label="星级进货价 (￥)"></el-table-column>
        <el-table-column prop="" label="星级零售折扣区间(%)"></el-table-column>
        <el-table-column prop="" label="星级零售价区间(￥)"></el-table-column>

        <el-table-column label="商品状态" fixed="right" width="80" align="center">
          <template slot-scope="scope">
            <span v-if="scope.row.is_up==1">上架中</span>
            <span v-if="scope.row.is_up==0">未上架</span>
          </template>
        </el-table-column>

        <el-table-column fixed="right" label="操作" width="100" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini">查看</el-button>
            <el-button type="text" size="mini" @click="editGoods(scope.row)">编辑</el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>
  </div>
</template>
<script>
export default {
  name: "starGoodsList",
  props: {},
  data() {
    return {
      star_id: "",
      goodsList: null,
      refreshBtnLoad: false,
      testValue: "",
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 20,
      //表格数据
      tableData: [],
      //表格是否显示加载层
      tableLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectItem: [],
      //是否是保存数据状态
      isPreservation: false
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
    update() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);
      this.$get("star/getGoodList", { star_id: this.star_id }, res => {
        clearTimeout(setTim);
        this.tableLoading = false;
        var map = ["img_list", "goods_class", "spec"];
        this.total = res.count;
        if (res.count > 0) this.tableData = stringToArr(res.msg, map);
      });
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    rowKey(item) {
      return item.goods_id;
    }
  },
  computed: {},
  mounted() {
    if (!this.$route.params["star_id"]) {
      if (!localStorage.star_id) {
        this.$router.go(-1);
        return;
      } else {
        this.star_id = localStorage.star_id;
      }
    } else {
      this.star_id = this.$route.params["star_id"];
    }
    localStorage.star_id = this.star_id;
    this.update();
  },
  destroyed() {},
  components: {},
  watch: {}
};
function stringToArr(arr, map) {
  for (var i = 0; i < arr.length; i++) {
    for (var j = 0; j < map.length; j++) {
      arr[i][map[j]] = JSON.parse(arr[i][map[j]]);
    }
  }
  return arr;
}
</script>
<style lang="scss" scoped>
@import "starGoodsList.scss";
</style>
<style>

</style>
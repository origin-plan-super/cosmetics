<template>
  <div id="goodsList">

    <template>
      <router-view></router-view>

      <div class="frame">
        <el-button-group>

          <el-button type="primary" size='mini' icon="el-icon-plus" @click='add()'>新增商品</el-button>

          <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
            <el-button type="primary" size='mini' icon="el-icon-refresh" @click='upDate'></el-button>
          </el-tooltip>
          <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
            <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
          </el-tooltip>

        </el-button-group>
        <el-button-group>

          <el-button type="primary" size='mini' :disabled="selectItem.length<=0" icon="el-icon-sort-up" @click='setUpAll(1)'>批量上架</el-button>
          <el-button type="primary" size='mini' :disabled="selectItem.length<=0" icon="el-icon-sort-down" @click='setUpAll(0)'>批量下架</el-button>

        </el-button-group>
      </div>
      <div class="frame">

        <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

          <el-table-column type='selection' align="center"></el-table-column>

          <el-table-column width="70" align="center" label="图片">

            <template slot-scope="scope">

              <img :src="$getUrl(scope.row.img_list[0].src)" v-if="scope.row.img_list.length>0" class="table-goods-img" alt="图片错误！">

            </template>

          </el-table-column>

          <el-table-column prop="goods_id" label="商品ID" resizable show-overflow-tooltip width="100"></el-table-column>

          <el-table-column prop="goods_title" label="商品名称" show-overflow-tooltip></el-table-column>

          <el-table-column label="价格" width="80">
            <template slot-scope="scope">
              <span>￥{{scope.row.spec.paramList[0].money}}</span>
            </template>
          </el-table-column>

          <el-table-column label="库存" width="80">
            <template slot-scope="scope">
              <span>{{scope.row.spec.paramList[0].stock}}</span>
            </template>
          </el-table-column>

          <el-table-column label="排序" width="80">

            <template slot-scope="scope">
              <el-input v-model="scope.row.sort" size="mini" @focus="recordValue(scope.row.sort)" @blur="save(scope.row,'sort',true,true)" @keyup.enter.native="save(scope.row,'sort',true,true)"></el-input>
            </template>

          </el-table-column>

          <el-table-column label="上架" fixed="right" width="80" align="center">
            <template slot-scope="scope">
              <el-tooltip :content="'上架：' + (scope.row.is_up==1?'开':'关')" placement="right">
                <el-switch active-value="1" inactive-value="0" v-model="scope.row.is_up" active-color="#13ce66" @change="save(scope.row,'is_up')"></el-switch>
              </el-tooltip>
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

      <div class="frame">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
      </div>

    </template>

  </div>
</template>

<script>
export default {
  name: "goodsList",
  data() {
    return {
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
      selectItem: []
    };
  },
  methods: {
    //页面切换事件
    handleCurrentChange: function() {
      this.upDate();
    },
    //大小改变事件
    handleSizeChange: function() {
      this.upDate();
    },
    upDate: function() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get(
        "goods/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          var map = ["img_list", "goods_class", "spec"];
          this.total = res.count;
          if (res.count > 0) this.tableData = stringToArr(res.msg, map);
        }
      );
    },
    add() {
      this.$router.push("/goods/edit");
    },
    // 保存
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "goods/save",
        { where: { goods_id: item.goods_id }, save: save },
        res => {
          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    editGoods(item) {
      this.$router.push({ name: "/goods/edit", params: { goods: item } });
    },
    rowKey(item) {
      return item.goods_id;
    },
    //记录值
    recordValue(value) {
      this.testValue = value;
    },
    setUpAll(is_up) {
      // this.$refs['table'].
      var list = this.selectItem;
      for (let i = 0; i < list.length; i++) {
        list[i].is_up = is_up + "";
        this.save(list[i], "is_up", false);
      }
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      console.log(items);
      this.selectItem = items;
    }
  },
  mounted: function() {
    this.upDate();
  }
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


<style lang='scss' scoped>
@import "list.scss";
</style>


<template>
  <div id="goodsList">

    <template>
      <!-- <router-view></router-view> -->

      <div class="frame">
        <el-button-group>

          <el-button type="primary" size='mini' icon="el-icon-plus" @click='add()'>新增商品</el-button>
          <el-button type="primary" size='mini' icon="el-icon-refresh" @click='update'></el-button>
          <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>

        </el-button-group>

        <el-button-group>

          <el-button type="primary" size='mini' :disabled="selectItem.length<=0" icon="el-icon-sort-up" @click='setUpAll(1)'>批量上架</el-button>
          <el-button type="primary" size='mini' :disabled="selectItem.length<=0" icon="el-icon-sort-down" @click='setUpAll(0)'>批量下架</el-button>

        </el-button-group>

      </div>
      <div class="frame">

        <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border size='mini'>

          <el-table-column type='selection' align="center"></el-table-column>

          <el-table-column width="100" align="center" label="图片">
            <template slot-scope="scope">
              <img :src="$getUrl(scope.row.img_list[0].src)" v-if="scope.row.img_list.length>0" class="table-goods-img" alt="图片错误！">
            </template>
          </el-table-column>

          <el-table-column prop="goods_id" label="商品ID" resizable show-overflow-tooltip width="100"></el-table-column>

          <el-table-column prop="goods_title" label="商品名称" show-overflow-tooltip></el-table-column>

          <el-table-column label="价格" width="100">
            <template slot-scope="scope">
              <span v-if="scope.row.sku.length>0">￥{{scope.row.sku[0].price}}</span>
              <span v-else>未配置规格！</span>
            </template>
          </el-table-column>

          <el-table-column label="库存" width="100">
            <template slot-scope="scope">
              <span v-if="scope.row.sku.length>0">{{scope.row.sku[0].stock_num}}</span>
              <span v-else>未配置规格！</span>
            </template>
          </el-table-column>

          <el-table-column label="排序" width="80">
            <template slot-scope="scope">
              <el-input :disabled="isPreservation" v-model="scope.row.sort" size="mini" @focus="recordValue(scope.row.sort)" @blur="save(scope.row,'sort',true,true)" @keyup.enter.native="save(scope.row,'sort',true,true)"></el-input>
            </template>
          </el-table-column>

          <el-table-column label="上架" fixed="right" width="80" align="center">
            <template slot-scope="scope">
              <el-switch title="设置上架" :disabled="isPreservation" active-value="1" inactive-value="0" v-model="scope.row.is_up" active-color="#13ce66" @change="up(scope.row)"></el-switch>
            </template>
          </el-table-column>

          <el-table-column fixed="right" label="操作" width="100" align="center">
            <template slot-scope="scope">
              <el-button title="删除" type="text" icon="el-icon-delete" size="mini" @click="delGoods(scope.row,scope.$index,tableData)"></el-button>
              <el-button title="编辑" type="text" icon="el-icon-edit-outline" size="mini" @click="editGoods(scope.row)"></el-button>
            </template>
          </el-table-column>

        </el-table>

      </div>

      <div class="frame">
        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total" />
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
      selectItem: [],
      //是否是保存数据状态
      isPreservation: false
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
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);
      this.$get(
        "goods/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          if (res.count > 0) this.tableData = res.msg;
        },
        error => {
          this.tableLoading = false;
        }
      );
    },

    add() {
      this.$router.push("/goods/edit");
    },
    // 保存
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;
      var tim = setTimeout(() => {
        this.isPreservation = true;
      }, 100);
      var msg;
      msg = this.$message({
        message: "正在保存",
        duration: 0,
        iconClass: "el-icon-loading"
      });
      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "goods/saveInfo",
        { where: { goods_id: item.goods_id }, save: save },
        res => {
          clearTimeout(tim);
          msg.close();
          this.isPreservation = false;
          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    delGoods(item, i, list) {
      this.tableLoading = true;
      this.$post(
        "goods/del",
        { goods_id: [item.goods_id] },
        res => {
          this.tableLoading = false;
          if (res.res >= 1) {
            list.splice(i, 1);
            this.$success("删除成功~");
          }
          if (res.res < 0) {
            this.$error("操作失败！请重试！");
          }
        },
        error => {
          this.$error("操作失败！请重试！");
          this.tableLoading = false;
        }
      );
    },
    up(item) {
      var tim = setTimeout(() => {
        this.isPreservation = true;
      }, 100);
      var msg;

      msg = this.$message({
        message: "正在保存",
        duration: 0,
        iconClass: "el-icon-loading"
      });
      this.$post("goods/up", { save: item }, res => {
        clearTimeout(tim);
        msg.close();

        this.isPreservation = false;

        if (res.res >= 1) {
        }
        if (res.res < 0) {
          this.$message({ message: "操作失败！请重试！", type: "error" });
        }
      });
    },
    editGoods(item) {
      this.$router.push({
        name: "/goods/edit",
        query: { goods_id: item.goods_id }
      });
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
      this.selectItem = items;
    }
  },
  mounted: function() {
    this.update();
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


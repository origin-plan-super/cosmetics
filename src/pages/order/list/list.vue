<template>
  <div id="orderList">

    <template>
      <router-view></router-view>

      <div class="frame">
        <el-button-group>


          <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
            <el-button type="primary" size='mini' icon="el-icon-refresh" @click='upDate'></el-button>
          </el-tooltip>
          <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
            <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
          </el-tooltip>

        </el-button-group>
   
      </div>
      <div class="frame">

        <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

          <el-table-column type='selection' align="center"></el-table-column>

          <el-table-column prop="order_id" label="订单号" resizable show-overflow-tooltip width="155"></el-table-column>
          <el-table-column prop="user_id" label="用户ID" resizable show-overflow-tooltip width="100"></el-table-column>


          <el-table-column label="价格￥" width="80" prop="money"></el-table-column>


          <el-table-column fixed="right" label="操作" width="100" align="center">
            <template slot-scope="scope">
              <el-button type="text" size="mini">查看</el-button>
              <el-button type="text" size="mini" @click="editorder(scope.row)">编辑</el-button>
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
  name: "orderList",
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
        "order/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          var map = ["order_info"];
          this.total = res.count;
          this.tableData=res.msg;
          // if (res.count > 0) this.tableData = stringToArr(res.msg, map);
          console.log(res.msg);
          
        }
      );
    },
    // 保存
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];

      this.$post(
        "order/save",
        { where: { order_id: item.order_id }, save: save },
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
    editorder(item) {
      this.$router.push({ name: "/order/edit", params: { order: item } });
    },
    rowKey(item) {
      return item.order_id;
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


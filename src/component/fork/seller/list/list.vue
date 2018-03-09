<template>
  <div id="sellerList">

    <div class="frame">

      <el-button-group>

        <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
          <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()" :loading="tableLoading"></el-button>
        </el-tooltip>
        <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
          <el-button type="primary" size="mini" icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
        </el-tooltip>

      </el-button-group>

      <!-- <el-button type="primary" size="mini" :disabled="selectItem.length<=0">批量设置上级</el-button> -->

    </div>

    <div class="frame">

      <el-table stripe header-cell-class-name="table-head-call" header-row-class-name="table-head" :default-expand-all="false" ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border height="70vh" max-height="70vh" size="mini">
        <el-table-column type="selection" align="center"></el-table-column>

        <el-table-column label="分销商名称" prop="user_name" resizable show-overflow-tooltip width="100"></el-table-column>
        <el-table-column prop="user_id" label="分销商账户" resizable show-overflow-tooltip width="100"></el-table-column>

        <el-table-column label="分销商等级" prop="star_name" resizable show-overflow-tooltip width="100"></el-table-column>

        <el-table-column label="上级" resizable show-overflow-tooltip width="200">

          <template slot-scope="scope">
            <span class="text-info">
              <span> {{scope.row.super_star_name}} </span>
              <span v-if="scope.row.super_star_name">:</span>
              <span> {{scope.row.super_name}} </span>
            </span>
          </template>

        </el-table-column>

        <el-table-column></el-table-column>

        <el-table-column prop="add_time" label="添加时间" resizable show-overflow-tooltip width="155"></el-table-column>

        <el-table-column fixed="right" label="操作" width="200" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" icon="el-icon-search"></el-button>
            <el-button type="text" size="mini" @click="showSetUserType(scope.row)">指定身份</el-button>
            <el-button type="text" size="mini" @click="showSetUserSuper(scope.row)">设置上级</el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>

    <div class="frame">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <set-user-type @on-success="setUserTypeSuccess" ref="set-user-type"></set-user-type>
    <set-user-super @on-success="setUserSuperSuccess" ref="set-user-super"></set-user-super>

  </div>
</template>


<script>
import setUserType from "../../../setUserType/setUserType.vue";
import setUserSuper from "../../../setUserSuper/setUserSuper.vue";

export default {
  name: "sellerList",
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

      isAdd: false
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
    update: function(showInfo, message) {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 100);
      this.$get(
        "fork/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          if (showInfo) {
            this.$message({
              message: message ? message : "更新完成~",
              type: "success"
            });
          }
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          this.tableData = res.msg;
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
    //打开设置用户身份的窗口
    showSetUserType(item) {
      this.$refs["set-user-type"].show(item);
    },
    //打开设置用户身份的窗口
    showSetUserSuper(item) {
      this.$refs["set-user-super"].show(item);
    },

    setUserTypeSuccess() {
      this.update();
    },
    setUserSuperSuccess() {
      this.update();
    },

    editorder(item) {
      this.$router.push({ name: "/order/edit", params: { order: item } });
    },
    rowKey(item) {
      return item.user_id;
    },
    //记录值
    recordValue(value) {
      this.testValue = value;
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    }
  },
  mounted: function() {
    this.update();
  },
  destroyed() {},
  watch: {},
  components: { setUserType, setUserSuper }
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


<style lang="scss" >
@import "list.scss";
</style>


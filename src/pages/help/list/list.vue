<template>
  <div id="HelpList">
    <div class="frame">
      <el-button-group>
        <el-button type="primary" size='mini' icon="el-icon-plus" @click="add"></el-button>
        <el-button type="primary" size='mini' icon="el-icon-refresh" @click="update"></el-button>
        <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0" @click="dels()"></el-button>
      </el-button-group>
    </div>
    <div class="frame">

      <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

        <el-table-column type='selection' align="center"></el-table-column>

        <el-table-column prop="help_title" label="帮助标题" resizable></el-table-column>

        <el-table-column label="帮助分类" width="150" align="center">
          <template slot-scope="scope">
            {{type[scope.row.help_type]}}
          </template>
        </el-table-column>

        <el-table-column prop="add_time" label="添加时间" width="180" resizable></el-table-column>
        <el-table-column prop="edit_time" label="最后修改时间" width="180" resizable></el-table-column>

        <el-table-column label="热门推荐" fixed="right" width="80" align="center">
          <template slot-scope="scope">
            <el-switch title="热门推荐" :disabled="isPreservation" active-value="1" inactive-value="0" v-model="scope.row.is_up" active-color="#13ce66" @change="up(scope.row)"></el-switch>
          </template>
        </el-table-column>

        <el-table-column fixed="right" label="操作" width="100" align="center">
          <template slot-scope="scope">

            <el-button type="text" icon="el-icon-edit" size="mini" @click="open(scope.row,scope.$index,tableData)"></el-button>
            <el-button type="text" icon="el-icon-delete" size="mini" @click="del(scope.row,scope.$index,tableData)"></el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>
    <div class="frame">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
export default {
  name: "HelpList",
  props: {},
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
      isPreservation: false,
      type: ["新手指引", "活动", "物流配送", "售后问题", "订单问题"]
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
    add(item) {
      this.$router.push({
        name: "/help/add"
      });
    },
    rowKey(item) {
      return item.feeback_id;
    },
    update() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);
      this.$get(
        "help/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          if (res.count > 0) this.tableData = res.msg;
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
      this.$post(
        "help/save",
        {
          where: {
            help_id: item.help_id
          },
          save: {
            is_up: item.is_up
          }
        },
        res => {
          clearTimeout(tim);
          msg.close();
          this.isPreservation = false;
          if (res.res >= 1) {
          }
          if (res.res < 0) {
            this.$message({ message: "操作失败！请重试！", type: "error" });
          }
        }
      );
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    del(item, i, list) {
      this.$post(
        "help/del",
        {
          help_id: [item.help_id]
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功~");
            list.splice(i, 1);
            return;
          }
          this.$error("删除失败！请重试~");
        }
      );
    },
    dels() {
      let ids = [];
      this.selectItem.forEach(item => {
        ids.push(item.help_id);
      });

      this.$post(
        "help/del",
        {
          help_id: ids
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            this.tableData = this.tableData.filter(
              item => ids.indexOf(item.help_id) < 0
            );
            return;
          }
          this.$error("删除失败！");
        }
      );
    },
    open(item, i, list) {
      this.$router.push({
        name: "/help/edit",
        query: {
          help_id: item.help_id
        }
      });
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
  components: {}
};
</script>
<style lang="scss" scoped>
@import "list.scss";
</style>
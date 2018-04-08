<template>
  <div id="list">

    <div class="frame">
      <el-button-group>
        <el-button type="primary" size='mini' icon="el-icon-plus" @click="add"></el-button>
        <el-button type="primary" size='mini' icon="el-icon-refresh" @click="update"></el-button>
        <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
      </el-button-group>
    </div>
    <div class="frame">

      <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

        <el-table-column type='selection' align="center"></el-table-column>
        <el-table-column prop="msg" label="内容" resizable></el-table-column>
        <el-table-column prop="add_time" label="添加时间" width="150" resizable></el-table-column>

        <el-table-column fixed="right" label="操作" width="50" align="center">
          <template slot-scope="scope">
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
  name: "list",
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
    add(item) {
      this.$router.push({
        name: "/msg/add",
        params: { feedback_id: item.feedback_id }
      });
    },
    rowKey(item) {
      return item.feeback_id;
    },
    update: function() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get(
        "msg/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          if (res.count > 0) this.tableData = res.msg;
        }
      );
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    del(item, i, list) {
      this.$post(
        "msg/del",
        {
          where: {
            msg_id: item.msg_id
          }
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
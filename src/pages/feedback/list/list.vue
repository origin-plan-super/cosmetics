<template>
  <div id="feedbackList">
    <div class="frame">
      <el-button-group>

        <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
          <el-button type="primary" size='mini' icon="el-icon-refresh" @click='update'></el-button>
        </el-tooltip>
        <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
          <el-button type="primary" size='mini' icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
        </el-tooltip>

      </el-button-group>

    </div>
    <div class="frame">

      <el-table ref='table' @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height='70vh' stripe size='mini'>

        <el-table-column type='selection' align="center"></el-table-column>

        <el-table-column prop="state" label="反馈类型" align="left" :filters="type" :filter-method="filterMethod" resizable width="140">
          <template slot-scope="scope">

            <template v-if="type[scope.row.feedback_type]">

              <span :class="type[scope.row.feedback_type].type">
                <i style="width:20px;display: inline-block;" :class="type[scope.row.feedback_type].icon" v-if="type[scope.row.feedback_type].icon"></i>
                {{type[scope.row.feedback_type].text}}
              </span>

            </template>
            <template v-else>
              <span class="text-error">
                <i style="width:20px;display: inline-block;" class="el-icon-error"></i>
                未知类型：{{scope.row.feedback_type}}
              </span>
            </template>

          </template>
        </el-table-column>

        <el-table-column prop="feedback_info" label="详情" resizable show-overflow-tooltip></el-table-column>
        <el-table-column prop="user_name" label="用户" resizable show-overflow-tooltip></el-table-column>
        <el-table-column prop="user_id" label="用户ID" resizable show-overflow-tooltip></el-table-column>
        <el-table-column label="状态" resizable show-overflow-tooltip>

          <template slot-scope="scope">
            <span v-if="scope.row.is_ok == 0" class="text-warning">
              <i class="el-icon-warning"></i>
            </span>
            <span v-if="scope.row.is_ok == 1" class="text-success">
              <i class="el-icon-success"></i>
            </span>
          </template>

        </el-table-column>
        <el-table-column prop="add_time" label="添加时间" width="150" resizable show-overflow-tooltip></el-table-column>

        <el-table-column fixed="right" label="操作" width="130" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" @click="setOk(scope.row)">处理</el-button>
            <el-button type="text" icon="el-icon-search" size="mini" @click="show(scope.row)"></el-button>
            <el-button type="text" icon="el-icon-delete" size="mini"></el-button>
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
  name: "feedbackList",
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
      //反馈类型
      // 0 : bug 反馈
      // 1 : 意见反馈
      // 2 : UI问题
      // 3 ：其他
      type: [
        {
          icon: "fa fa-bug",
          value: "bug 反馈",
          text: "bug 反馈",
          type: "text-danger"
        },
        {
          icon: "el-icon-service",
          value: "意见反馈",
          text: "意见反馈",
          type: "text-warning"
        },
        {
          icon: "fa fa-window-maximize",
          value: "UI问题",
          text: "UI问题",
          type: "text-primary"
        },
        {
          icon: "",
          value: "其他",
          text: "其他",
          type: "text-info"
        }
      ]
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
    show(item) {
      this.$router.push({
        name: "/feedback/info",
        params: { feedback_id: item.feedback_id }
      });
    },
    rowKey(item) {
      return item.feeback_id;
    },
    setOk(item) {
      var is_ok = item.is_ok == 1 ? 0 : 1;
      this.$post(
        "feedback/save",
        {
          where: { feedback_id: item.feedback_id },
          save: { is_ok: is_ok }
        },
        res => {
          if (res.res >= 1) {
            this.$success("操作成功！");
            item.is_ok = is_ok;
            return;
          }
          this.$error("操作失败！请重试");
        }
      );
    },
    update: function() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get(
        "feedback/getList",
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
    //筛选条件函数
    filterMethod(value, row, column) {
      if (!this.type[row.feedback_type]) return true;
      return this.type[row.feedback_type].text === value;
    }
  },
  computed: {},
  mounted() {
    this.update();
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "list.scss";
</style>
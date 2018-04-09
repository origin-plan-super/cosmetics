<template>
  <div id="couponList">
    <el-button-group>
      <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update"></el-button>
      <el-button type="primary" size="mini" icon="el-icon-plus" @click="$router.push('/coupon/add')"></el-button>
      <el-button type="primary" size="mini" icon="el-icon-delete" :disabled="selectItem.length<=0" @click="dels()"></el-button>
    </el-button-group>
    <el-table ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border max-height="70vh" stripe size="mini">

      <el-table-column type="selection" align="center"></el-table-column>
      <!--       
coupon_id
user_id
name
denominations
origin_condition
start_at
end_at
reason
value
state
add_time
edit_time
 -->

      <el-table-column prop="coupon_id" label="ID" resizable show-overflow-tooltip></el-table-column>
      <el-table-column prop="user_id" label="用户信息" resizable show-overflow-tooltip></el-table-column>
      <el-table-column prop="name" label="名称" resizable></el-table-column>
      <el-table-column prop="denominations" label="面值" resizable></el-table-column>
      <el-table-column prop="start_at" label="卡有效开始时间" resizable></el-table-column>
      <el-table-column prop="end_at" label="卡失效日期" resizable></el-table-column>
      <el-table-column prop="reason" label="不可用原因" resizable></el-table-column>
      <el-table-column prop="value" label="可减免" resizable></el-table-column>
      <el-table-column prop="state" label="使用状态" resizable></el-table-column>

      <el-table-column prop="add_time" label="创建时间" width="150" resizable show-overflow-tooltip></el-table-column>

      <el-table-column fixed="right" label="操作" width="130" align="center">
        <template slot-scope="scope">

          <el-button type="text" icon="el-icon-delete" size="mini" @click="del(scope.row,scope.$index,tableData)"></el-button>
          <el-button type="text" icon="el-icon-share" size="mini" @click="inquiry(scope.row)"></el-button>

        </template>
      </el-table-column>

    </el-table>

    <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
    </el-pagination>
  </div>
</template>
<script>
export default {
  name: "couponList",
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
      //优惠券类型
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
        }
      ]
    };
  },
  methods: {
    //页面切换事件
    handleCurrentChange: function() {
      this.currentPage = val;
      this.update();
    },
    //大小改变事件
    handleSizeChange: function() {
      this.pageSize = val;
      this.update();
    },
    rowKey(item) {
      return item.coupon_id;
    },
    update: function() {
      var setTim = setTimeout(() => {
        this.tableLoading = true;
      }, 500);

      this.$get(
        "coupon/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          clearTimeout(setTim);
          this.tableLoading = false;
          this.total = res.count;
          if (res.count > 0) this.tableData = res.msg;
        }
      );
    },
    del(item, i, list) {
      this.$get(
        "coupon/del",
        {
          coupon_id: [item.coupon_id]
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            list.splice(i, 1);
            return;
          }
          this.$error("删除失败！");
        }
      );
    },
    dels() {
      let ids = [];

      this.selectItem.forEach(item => {
        ids.push(item.coupon_id);
      });

      this.$post(
        "coupon/del",
        {
          coupon_id: ids
        },
        res => {
          if (res.res >= 1) {
            this.$success("删除成功！");
            this.tableData = this.tableData.filter(
              item => ids.indexOf(item.coupon_id) < 0
            );
            return;
          }
          this.$error("删除失败！");
        }
      );
    },
    inquiry(item) {
      this.$prompt("请输入用户 id 以指派", "指派", {
        confirmButtonText: "确定",
        cancelButtonText: "取消"
      })
        .then(({ value }) => {
          this.setUser(item, value);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "取消指派"
          });
        });
    },
    setUser(item, user_id) {
      this.$post(
        "coupon/save",
        {
          where: {
            coupon_id: item.coupon_id
          },
          save: {
            user_id: user_id
          }
        },
        res => {
          console.log(res);
          if (res.res >= 1) {
            this.update();
            this.$success("操作成功！");
            return;
          }
          this.$error("操作失败！");
        }
      );
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
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
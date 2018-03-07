<template>
  <div id="adminList">

    <div class="frame">

      <switch-panel :show="isAdd" hide-title="新增管理员" show-title="关闭">

        <el-button-group slot="head">
          <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
            <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()"></el-button>
          </el-tooltip>
          <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
            <el-button type="primary" size="mini" icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
          </el-tooltip>
        </el-button-group>

        <el-form label-width="80px" ref="addFrom" :model="addAdmin" :rules="rules" size="small" style="width:300px">
          <el-form-item label="登录账户" prop="admin_id">
            <el-input v-model="addAdmin.admin_id"></el-input>
          </el-form-item>
          <el-form-item label="登录密码" prop="admin_pwd">
            <el-input v-model="addAdmin.admin_pwd"></el-input>
          </el-form-item>
          <el-form-item label="确定密码" prop="admin_pwd2">
            <el-input v-model="addAdmin.admin_pwd2"></el-input>
          </el-form-item>
          <el-form-item label="管理名" prop="admin_name">
            <el-input v-model="addAdmin.admin_name"></el-input>
          </el-form-item>
          <el-form-item>

            <el-button type="primary" @click="add()">创建</el-button>
            <el-button @click="resetForm()">重置</el-button>

          </el-form-item>
        </el-form>

      </switch-panel>
    </div>
    <div class="frame">

      <el-table header-cell-class-name="table-head-call" border header-row-class-name="table-head" :default-expand-all="false" ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" badmin height="70vh" max-height="70vh" size="mini">

        <el-table-column type="selection" align="center"></el-table-column>

        <el-table-column prop="admin_id" label="管理ID" resizable show-overflow-tooltip width="200"></el-table-column>
        <el-table-column prop="admin_name" label="管理名" resizable show-overflow-tooltip width=""></el-table-column>
        <el-table-column prop="add_time" label="添加时间" resizable width="200"></el-table-column>
        <el-table-column prop="edit_time" label="最后修改时间" resizable width="200"></el-table-column>

        <el-table-column fixed="right" label="操作" width="150" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" @click="edit(scope.row)">编辑</el-button>
            <el-button type="text" size="mini" @click="del(scope.row)" v-if="scope.row.admin_id!='root'">删除</el-button>
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
import switchPanel from "../../../component/switchPanel/switchPanel.vue";

export default {
  name: "adminList",
  data() {
    return {
      // 当前页
      currentPage: 1,
      // 总条数
      total: 0,
      // 当前每页显示的数量
      pageSize: 10,
      //表格数据
      tableData: [],
      //表格是否显示加载层
      tableLoading: false,
      //记录用的值
      testValue: "",
      //被选中项
      selectItem: [],
      //状态值
      state: [],
      interval: null,
      isAdd: false,
      addAdmin: {
        admin_id: "",
        admin_pwd: "",
        admin_pwd2: "",
        admin_name: ""
      },
      rules: {
        admin_id: [
          { required: true, message: "请输入登录账户", trigger: "blur" },
          { max: 16, message: "不能超过 16 个字符", trigger: "blur" }
        ],
        admin_pwd: [
          { required: true, message: "请输入登录密码", trigger: "blur" },
          { min: 3, max: 16, message: "长度在 6 到 16 个字符", trigger: "blur" }
        ],
        admin_pwd2: [
          { required: true, message: "请确认密码", trigger: "blur" },
          { min: 3, max: 16, message: "长度在 6 到 16 个字符", trigger: "blur" }
        ],
        admin_name: [
          { max: 16, message: "不能超过 16 个字符", trigger: "blur" }
        ]
      }
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
      }, 500);

      this.$get(
        "admin/getList",
        { page: this.currentPage, limit: this.pageSize },
        res => {
          if (res.res <= 0) {
            this.isAdd = true;
          }
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
        "admin/save",
        { where: { admin_id: item.admin_id }, save: save },
        res => {
          this.isAdd = false;
          if (res.res >= 1 && isInfo) {
            this.$message({ message: "保存成功！", type: "success" });
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }
      );
    },
    del(item) {
      this.$post(
        this.$serverAdmin + "Admin/del",
        { where: { admin_id: item.admin_id } },
        res => {
          if (res.res >= 1) {
            this.$message({
              message: "删除成功",
              type: "success"
            });
            this.update();
          }
        }
      );
    },
    editadmin(item) {
      this.$router.push({
        name: "/admin/edit",
        params: { admin_id: item.admin_id }
      });
    },
    rowKey(item) {
      return item.admin_id;
    },
    //记录值
    recordValue(value) {
      this.testValue = value;
    },
    // 当选择项发生变化时会触发该事件
    selectionChange(items) {
      this.selectItem = items;
    },
    edit(item) {
      this.$router.push({
        name: "/admin/info",
        params: { admin_id: item.admin_id }
      });
    },
    add() {
      this.$refs["addFrom"].validate(valid => {
        if (valid) {
          if (this.addAdmin.admin_pwd !== this.addAdmin.admin_pwd2) {
            this.$message({ type: "error", message: "两次输入的密码不一致！" });
            return false;
          } else {
            this.$post(
              this.$serverAdmin + "Admin/add",
              { add: this.addAdmin },
              res => {
                if (res.res == 1) {
                  this.$message({
                    message: "添加成功",
                    type: "success"
                  });
                  this.update();
                }
              }
            );
          }
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    resetForm() {
      this.$refs["addFrom"].resetFields();
    },
    filterMethod(value, row, column) {
      if (!this.state[row.state]) return true;
      return this.state[row.state].text === value;
    }
  },
  mounted: function() {
    this.update();
  },
  destroyed() {},
  watch: {},
  components: { "switch-panel": switchPanel }
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


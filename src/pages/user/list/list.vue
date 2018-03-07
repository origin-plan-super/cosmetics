<template>
  <div id="orderList">

    <div class="frame">

      <el-button-group>

        <el-tooltip class="item" effect="dark" content="刷新列表" placement="top-start">
          <el-button type="primary" size="mini" icon="el-icon-refresh" @click="update()"></el-button>
        </el-tooltip>
        <el-tooltip class="item" effect="dark" content="删除选中" placement="top-start">
          <el-button type="primary" size="mini" icon="el-icon-delete" :disabled="selectItem.length<=0"></el-button>
        </el-tooltip>

      </el-button-group>

      <el-button type="primary" size="mini" :disabled="selectItem.length<=0">批量设置上级</el-button>

      <el-button type="primary" size="mini" icon="el-icon-plus" @click="isAdd=!isAdd">
        <span v-if="!isAdd">添加用户</span>
        <span v-if="isAdd">取消</span>
      </el-button>

    </div>

    <div class="frame" v-if="isAdd">
      <el-card>
        <span slot="header">添加用户</span>
        <add-user></add-user>
      </el-card>
    </div>

    <div class="frame">

      <el-table header-cell-class-name="table-head-call" header-row-class-name="table-head" :default-expand-all="false" ref="table" @selection-change="selectionChange" v-loading="tableLoading" :data="tableData" :row-key="rowKey" style="width: 100%" border height="70vh" max-height="70vh" size="mini">
        <el-table-column type="selection" align="center"></el-table-column>

        <el-table-column prop="user_id" label="用户账户" resizable show-overflow-tooltip width="100"></el-table-column>

        <el-table-column label="基本信息" resizable show-overflow-tooltip width="200">

          <template slot-scope="scope">
            <div class="user-info">

              <img :src="$getUrl(scope.row.user_head)" class="user-head" alt="图片错误">
              <span class="text-info">{{scope.row.user_name}}</span>

            </div>

          </template>

        </el-table-column>

        <el-table-column prop="user_type" label="用户类型" align="left" :filters="user_types" :filter-method="filterMethod" width="100">
          <template slot-scope="scope">

            <template v-if="user_types[scope.row.user_type]">

              <span :class="user_types[scope.row.user_type].type">
                {{user_types[scope.row.user_type].text}}
              </span>

            </template>
            <template v-else>
              <span class="text-error">
                <i style="width:20px;display: inline-block;" class="el-icon-error"></i>
                未知类型：{{scope.row.user_type}}
              </span>
            </template>

          </template>
        </el-table-column>

        <el-table-column></el-table-column>

        <el-table-column prop="add_time" label="创建时间" resizable show-overflow-tooltip width="155"></el-table-column>

        <el-table-column fixed="right" label="操作" width="200" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="mini" icon="el-icon-search"></el-button>
            <el-button type="text" size="mini" @click="showSetUserType(scope.row)">指定身份</el-button>
            <el-button type="text" size="mini" @click="superD.activeUser=scope.row;superD.isShow=true">设置上级</el-button>
          </template>
        </el-table-column>

      </el-table>

    </div>

    <div class="frame">
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-size.sync="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <set-user-type ref="set-user-type"></set-user-type>

  </div>
</template>


<script>
import addUser from "../../../component/addUser/addUser.vue";
import setUserType from "../../../component/setUserType/setUserType.vue";

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
      selectItem: [],
      //用户类型
      user_types: [
        {
          value: "普通用户",
          text: "普通用户"
        },
        {
          value: "分销商",
          text: "分销商"
        },
        {
          value: "推广员",
          text: "推广员"
        }
      ],
      isAdd: false,
      identity: {
        isShow: false,
        activeUser: null,
        data: {
          identity: null,
          star_id: null
        }
      },
      superD: {
        isShow: false,
        activeUser: null
      },
   
      stars: []
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
        "user/getList",
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
          // var map = ["order_info"];
          // if (res.count > 0) this.tableData = asdasd stringToArr(res.msg, map);
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
      // this.$refs["].
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
    },
    see(item) {
      this.$router.push({
        name: "/order/info",
        params: { order_id: item.order_id }
      });
    },

    filterMethod(value, row, column) {
      if (!this.user_types[row.user_type]) return true;
      return this.user_types[row.user_type].text === value;

      // const property = column["property"];
      // return row[property] ===asdasdasdas12312 value;
    },
    showSetUserType(item) {
      this.$refs["set-user-type"].show(item);
    },
    //指定身份
    setIdentity(item) {
      var user_id = item.user_id;
      console.log(user_id);
    },
    setSuper(item) {
      var user_id = item.user_id;
    },
    submitForm(foemName) {
      this.$refs[foemName].validate(valid => {
        if (valid) {
          console.log(add);
        } else {
          return false;
        }
      });
    }
  },
  mounted: function() {
    this.update();
  },
  destroyed() {},
  watch: {},
  components: { addUser, setUserType }
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


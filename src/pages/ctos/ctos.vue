<template>
  <div id="ctos">
    <div class="frame">
      <h1>Ctos</h1>
      <h3>创建管理账户</h3>
      <el-button @click='test()'>验证登录</el-button>
      <div>
        账户：
        <el-input v-model='add.admin_id'></el-input>
        密码：
        <el-input v-model='add.admin_pwd'></el-input>
        <p>
          <el-button @click='addAdmin'>添加</el-button>
        </p>
      </div>
      <h3>账户列表</h3>
      <el-button @click='getAdmin'>刷新</el-button>
      <ul>
        <li v-for='(item,index) in adminList' :key='index' style='padding:10px'>
          {{item.admin_id}}
          <el-button type='danger' size='mini' @click='del(item,index)'>x</el-button>
        </li>
      </ul>
    </div>
  </div>

</template>

<script>
export default {
  name: "ctos",
  data() {
    return {
      adminList: [],
      add: {
        admin_id: "",
        admin_pwd: ""
      }
    };
  },
  methods: {
    getAdmin: function() {
      this.$get(this.$serverAdmin + "Admin/getList", {}, res => {
        this.adminList = res.msg;
      });
    },
    addAdmin: function() {
      this.$post(this.$serverAdmin + "Admin/add", { add: this.add }, res => {
        if (res.res == 1) {
          this.$message({
            message: "添加成功",
            type: "success"
          });
          this.getAdmin();
        }
      });
    },
    del: function(item, index) {
      this.$post(
        this.$serverAdmin + "Admin/del",
        {
          where: {
            admin_id: item.admin_id
          }
        },
        res => {
          if (res.res == 1) {
            this.$message({
              message: "删除成功",
              type: "success"
            });
            // this.adminList.splice(index, 1);
            this.getAdmin();
          }
        }
      );
    },
    test: function() {
      this.$get(this.$serverAdmin + "index/isLogin", {}, res => {});
    }
  },
  watch: {},
  //启动函数
  mounted: function() {
    this.getAdmin();
  }
};
</script>

<style lang='scss'>
@import "../../pages.scss";
</style>

<style lang='scss' scoped>
@import "ctos.scss";
</style>


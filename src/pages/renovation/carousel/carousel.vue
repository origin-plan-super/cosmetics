<template>
  <div id="carousel" class="carousel">
    <div class="frame">
      <el-button @click="update" icon="el-icon-refresh" type="text"></el-button>
      <div class="text-size-xm text-info">只能上传jpg/png文件，且不超过500kb，可拖动排序。</div>

    </div>

    <el-card shadow="hover" class="box-card">
      <div slot="header" class="clearfix">
        <span>首页轮播配置</span>
        <div style="float: right;">
          <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-success="homeSuccess">
            <el-button type="text" size="mini">上传</el-button>
          </el-upload>
        </div>
      </div>
      <img-list v-model="home_img_list" @on-remove="remove" @drag-end="dragEnd"></img-list>
    </el-card>

    <el-card shadow="hover" class="box-card">
      <div slot="header" class="clearfix">
        <span>发现页轮播配置</span>
        <div style="float: right;">
          <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-success="fxSuccess">
            <el-button type="text" size="mini">上传</el-button>
          </el-upload>
        </div>
      </div>
      <img-list v-model="fx_img_list" @on-remove="remove" @drag-end="dragEnd"></img-list>
    </el-card>

    <el-card shadow="hover" class="box-card">
      <div slot="header" class="clearfix">
        <span>课堂页轮播配置</span>
        <div style="float: right;">
          <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-success="ktSuccess">
            <el-button type="text" size="mini">上传</el-button>
          </el-upload>
        </div>
      </div>
      <img-list v-model="kt_img_list" @on-remove="remove" @drag-end="dragEnd"></img-list>
    </el-card>

  </div>
</template>
<script>
import draggable from "vuedraggable";
// pages_id
// 轮播图发布到的页面
// 0：首页
// 1：发现（动态）
// 2：课堂
export default {
  name: "carousel",
  data() {
    return {
      home_img_list: [],
      fx_img_list: [],
      kt_img_list: [],
      parame: {
        token: localStorage.token,
        admin_id: localStorage.admin_id,
        src: "carousel/"
      },
      testValue: ""
    };
  },
  methods: {
    update() {
      this.$get("carousel/getList", {}, res => {
        if (res.res >= 1) {
          this.buliderList(res.msg);
        }
      });
    },
    buliderList(list) {
      this.home_img_list = [];
      this.fx_img_list = [];
      this.kt_img_list = [];

      for (let i = 0; i < list.length; i++) {
        if (list[i].pages_id == 0) {
          this.home_img_list.push(list[i]);
        }
        if (list[i].pages_id == 1) {
          this.fx_img_list.push(list[i]);
        }
        if (list[i].pages_id == 2) {
          this.kt_img_list.push(list[i]);
        }
      }
    },
    add(src, pages_id) {
      this.$post(
        "carousel/add",
        {
          add: {
            src: src,
            pages_id: pages_id
          }
        },
        res => {
          this.update();
          return;
          if (res.res >= 1) {
            if (pages_id == 0) {
              this.home_img_list.push(res.msg);
            }
            if (pages_id == 1) {
              this.fx_img_list.push(res.msg);
            }
            if (pages_id == 2) {
              this.kt_img_list.push(res.msg);
            }
            return;
          }
        }
      );
    },
    homeSuccess(res) {
      this.add(res.msg.src, 0);
    },
    fxSuccess(res) {
      this.add(res.msg.src, 1);
    },
    ktSuccess(res) {
      this.add(res.msg.src, 2);
    },

    upLoadSuccess(res) {
      this.add(res.msg.src);
    },
    remove(item, i, list, fun) {
      //删除
      this.$post(
        "carousel/del",
        { where: { carousel_id: item.carousel_id } },
        res => {
          if (res.res >= 1) {
            this.$message({ type: "success", message: "删除成功！" });
            list.splice(i, 1);
            fun();
            return;
          }
          this.$message({ type: "error", message: "删除失败！" });
        }
      );
    },
    // 保存 通用控制器
    save(item, saveName, isInfo, isValidate) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where["carousel" + "_id"] = item["carousel" + "_id"];
      this.$post("carousel" + "/save", { where: where, save: save }, res => {
        if (res.res >= 1 && isInfo) {
          this.$message({ message: "保存成功！", type: "success" });
        }
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
      });
    },
    //拖拽完成的时候
    dragEnd(list) {
      for (let i = 0; i < list.length; i++) {
        if (list[i].sort != i) {
          list[i].sort = i;
          this.save(list[i], "sort", false, false);
        }
      }
    }
  },
  computed: {},
  mounted() {
    this.update();
  },
  components: { draggable },
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "carousel.scss";
</style>
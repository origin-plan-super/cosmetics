<template>
  <div id="carousel" class="carousel">
    <div class="frame">
    </div>

    <div class="frame">
      <el-button @click="update" icon="el-icon-refresh" type="text"></el-button>

      <el-upload :action="$serverUpFile" ref="upload" :show-file-list="false" :data="parame" multiple :on-success="upLoadSuccess">
        <el-button @click="update" type="success" size="mini">上传图片</el-button>
        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb，可拖动排序。</div>
      </el-upload>

      <div class="img-list">
        <draggable v-model="img_list" :options="{group:'img_list'}" @end="dragEnd(img_list)">

          <div class="img-item" v-for="(item,i) in img_list" :key="item.carousel_id">
            <div class="del" @click="remove(item,i,img_list)">
              <i class="el-icon-close"></i>
            </div>
            <img :src="$getUrl(item.src)" alt="图片错误！">
          </div>
        </draggable>

      </div>

      <hr>
      <div class="img-list">

        <div class="img-item" v-for="(item,i) in img_list" :key="item.carousel_id">
          <div class="del" @click="remove(item,i,img_list)">
            <i class="el-icon-close"></i>
          </div>
          <o-img :src="item.src"></o-img>
        </div>
      </div>

    </div>

  </div>
</template>
<script>
import draggable from "vuedraggable";

export default {
  name: "carousel",
  data() {
    return {
      img_list: [],
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
          this.img_list = res.msg;
          return;
        }
      });
    },
    add(src) {
      this.$post("carousel/add", { add: { src: src } }, res => {
        console.log(res);
        if (res.res >= 1) {
          this.img_list.push(res.msg);
          return;
        }
      });
    },
    upLoadSuccess(res) {
      this.add(res.msg.src);
    },
    remove(item, i, list) {
      //删除
      this.$post(
        "carousel/del",
        { where: { carousel_id: item.carousel_id } },
        res => {
          if (res.res >= 1) {
            this.$message({ type: "success", message: "删除成功！" });
            list.splice(i, 1);
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
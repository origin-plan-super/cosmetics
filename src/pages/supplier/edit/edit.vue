<template>
    <div id="edit">

        <el-form :model="save" :rules="rules" ref="form" size="small" label-width="100px" class="form" v-if="save">

            <el-card shadow="hover" class="">

                <span slot="header">帐号信息</span>

                <el-form-item label="帐号登录名" prop="supplier_login_id">
                    <el-input v-model="save.supplier_login_id"></el-input>
                </el-form-item>

                <el-form-item label="供货商名称" prop="supplier_name">
                    <el-input v-model="save.supplier_name"></el-input>
                </el-form-item>

            </el-card>

            <el-card shadow="hover" class="el-card-v">
                <span slot="header">企业信息</span>

                <el-form-item label="公司名称" prop="company_name">
                    <el-input v-model="save.company_name"></el-input>
                </el-form-item>

                <el-form-item label="公司电话" prop="company_tel">
                    <el-input v-model="save.company_tel"></el-input>
                </el-form-item>

                <el-form-item label="公司地址" prop="">
                    <div class="area-select-box">
                        <area-select :level='2' type='text' v-model='address'></area-select>
                    </div>
                </el-form-item>

                <el-form-item label="详细地址" prop="company_address_detail">
                    <el-input v-model="save.company_address_detail"></el-input>
                </el-form-item>

            </el-card>

            <el-card shadow="hover">
                <span slot="header">联系人信息</span>

                <el-form-item label="姓名" prop="name">
                    <el-input v-model="save.name"></el-input>
                </el-form-item>

                <el-form-item label="性别" prop="gender">
                    <el-radio-group v-model="save.gender">
                        <el-radio label="1">男</el-radio>
                        <el-radio label="2">女</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="固定电话" prop="telephone">
                    <el-input v-model="save.telephone"></el-input>
                </el-form-item>

                <el-form-item label="手机" prop="phone">
                    <el-input v-model="save.phone"></el-input>
                </el-form-item>

                <el-form-item label="E-Mail" prop="email">
                    <el-input v-model="save.email"></el-input>
                </el-form-item>
                <el-form-item label="微信号" prop="weixin">
                    <el-input v-model="save.weixin"></el-input>
                </el-form-item>
                <el-form-item label="QQ" prop="qq">
                    <el-input v-model="save.qq"></el-input>
                </el-form-item>

            </el-card>
            <el-card shadow="hover">
                <span slot="header">资质</span>

                <el-form-item label="资质文件" prop="file_src">
                    <a :href="$getUrl(save.file_src)" v-if="save.file_src.length>0" target="_blank">查看文件</a>
                    <o-upload v-model="save.file_src" src="supplier/">
                        <el-button icon="el-icon-upload" size="mini">{{uploadText}}</el-button>
                    </o-upload>
                </el-form-item>

            </el-card>

            <el-card shadow="hover">
                <el-form-item>
                    <el-button type="primary" @click="submitForm()">保存</el-button>
                    <el-button @click="resetForm()">重置</el-button>
                </el-form-item>
            </el-card>

        </el-form>

    </div>
</template>
<script src="./edit.js"></script>
<style lang="scss" scoped>
@import "edit.scss";
</style>
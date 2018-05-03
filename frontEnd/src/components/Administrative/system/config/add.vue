<template>
	<div class="m-l-50 m-t-30 w-1000">
  <el-form ref="form" :model="form" :rules="rules" label-width="130px">
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="系统基础配置" name="first">
        
      <el-form-item label="系统名称" prop="SYSTEM_NAME">
        <el-input v-model.trim="form.SYSTEM_NAME" class="h-40 w-200"></el-input>
      </el-form-item>
      <el-form-item label="登录验证码">
        <el-radio-group v-model="form.IDENTIFYING_CODE">
          <el-radio label="1">打开</el-radio>
          <el-radio label="0">关闭</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="登录会话有效期" prop="LOGIN_SESSION_VALID">
        <el-input v-model.number="form.LOGIN_SESSION_VALID" class="h-40 w-200"></el-input>
      </el-form-item>
      
    <preview ref="preview" :url="propsImg"></preview>
    </el-tab-pane>
    <el-tab-pane label="可扩展" name="third">
      1


    </el-tab-pane>
    <el-tab-pane label="可扩展项" name="fourth">
      2




    </el-tab-pane>
  </el-tabs>
  <el-form-item>
        <el-button type="primary" @click="add()" :loading="isLoading">提交</el-button>
      </el-form-item>
</el-form>
    </div>
</template>
<script>
  import http from '../../../../assets/js/http'
  import preview from './preview.vue'

  export default {
    data() {
      return {
        activeName: 'first',
        isLoading: false,
        fileList: [],
        propsImg: '',
        form: [],
        uploadUrl: '',
        rules: {
          SYSTEM_NAME: [
            { required: true, message: '请输入系统名称' }
          ],
          LOGIN_SESSION_VALID: [
            { required: true, message: '请输入登录有效期' }
          ]
        }
      }
    },
    methods: {
      handleClick(tab, event) {
        // console.log(tab, event);
        // alert(tab.name)
        // active
      },
      add() {
        this.$refs.form.validate((pass) => {
          if (pass) {
            this.apiPost('admin/systemConfigs', this.form).then((res) => {
              this.handelResponse(res, (data) => {
                _g.toastMsg('success', '提交成功')
                this.isLoading = !this.isLoading
              }, () => {
              })
            })
          }
        })
      },
      uploadSuccess(res, file, fileList) {
        this.form.SYSTEM_LOGO = res.data
        let data = {
          name: '图片',
          url: window.HOST + res.data
        }
        if (this.fileList.length) {
          this.fileList[0] = data
        } else {
          this.fileList.push(data)
        }
      },
      uploadFail(err, res, file) {
        console.log('err = ', _g.j2s(err))
        console.log('res = ', _g.j2s(res))
      },
      handleRemove(file, fileList) {
        console.log('file = ', file)
        console.log('fileList = ', fileList)
      },
      viewPic() {
        this.propsImg = this.fileList[0].url
        this.$refs.preview.open()
      }
    },
    created() {
      this.uploadUrl = window.HOST + '/Upload'
      this.apiPost('admin/base/getConfigs').then((res) => {
        this.handelResponse(res, (data) => {
          this.form = data
        })
      })
    },
    components: {
      preview
    },
    mixins: [http]
  }
</script>
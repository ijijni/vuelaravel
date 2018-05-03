<template>
  <div>
  <h1>编辑模板</h1>
  标题：<el-input v-model="formInline.title" placeholder="请输入模板标题" style="width:40%"></el-input>
  <div style="margin: 20px 0;"></div>
  备注：<el-input
  type="textarea"
  :rows="2"
  placeholder="请输入模板备注"
  v-model="formInline.note" style="width:40%">
</el-input>
<div style="margin: 20px 0;"></div>
  <div style="margin-top:40px">
    <div class="editor-container">
      <UE :defaultMsg=defaultMsg :config=config ref="ue"></UE>
    </div>
  </div>
  <div style="margin: 20px 0;"></div>
  <el-button type="primary" @click="edit">保存</el-button>
  <el-button type="primary" @click="goback">返回</el-button>
  </div>
</template>

<script>
  import btnGroup from '../../Common/btn-group.vue'
  import http from '../../../assets/js/http'
  import UE from '../../Common/ue.vue'
  export default {
    data() {
      return {
        tableData1: [],
        loading: 'false',
        title: '',
        note: '',
        defaultMsg: '11',
        config: {
          initialFrameWidth: null,
          initialFrameHeight: 350
        }
      }
    },
    props: ['formInline', 'status'],
    methods: {
      shows() {
        this.$refs.ue.setUEContent(this.formInline.content)
      },
      getAlldata() {
        this.loading = true
      },
      goback() {
        this.$parent.firstShow = true
        this.$parent.thirdShow = false
        this.$parent.fouthShow = true
      },
      edit() {
        let content = this.$refs.ue.getUEContent()
        let data = {
          title: this.formInline.title,
          note: this.formInline.note,
          content: content,
          id: this.formInline.id
        }
        this.apiPut('vue/article/update/', this.formInline.id, data).then((res) => {
          // console.log('res = ', _g.j2s(res))
          this.handelResponse(res, (data) => {
            _g.toastMsg('success', '编辑成功')
            setTimeout(() => {
              this.$parent.getAlldata()
              this.goback()
            }, 1500)
          },
            () => {
              this.isLoading = !this.isLoading
            }
          )
        })
      },
      init() {
        // this.getAlldata()
      }
    },
    created() {
      this.init()
    },
    computed: {
      addShow() {
        return _g.getHasRule('users-save')
      },
      editShow() {
        return _g.getHasRule('users-update')
      },
      deleteShow() {
        return _g.getHasRule('users-delete')
      }
    },
    watch: {
      '$route' (to, from) {
        this.init()
      },
      status (newvalue, oldvalue) {
        this.$parent.status = 1
        this.shows()
      }
    },
    components: {
      btnGroup,
      UE
    },
    mixins: [http],
    filters: {
    }
  }
</script>
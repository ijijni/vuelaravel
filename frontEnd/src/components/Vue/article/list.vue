<template>
	<div>
    <div v-show="firstShow">
    <h4>模板列表</h4>
    <div style="margin: 15px 0 15px 0;">
    <router-link to="/vue/article/add">
    <el-button type="primary">新增</el-button>
    </router-link>
    <el-button type="primary" @click="init">刷新</el-button>
  <el-input placeholder="请输入模板标题" v-model="keywords" style="width:40%;float:right" @keyup.enter.native="getAlldata">
    <el-button slot="append" icon="el-icon-search" @click="getAlldata"></el-button>
  </el-input>
</div>
		<el-table
		    :data="tableData"
		    :row-class-name="tableRowClassName">
		    <el-table-column
		      prop="title"
		      label="标题"
		      width="180">
		    </el-table-column>
        <el-table-column
          prop="create_time"
          label="创建时间"
          width="180">
        <template slot-scope="scope">
        <span style="margin-left: 10px">{{ scope.row.create_time | formatDate }}</span>
      </template>
        </el-table-column>
        <el-table-column
          prop="update_time"
          label="更改时间"
          width="180">
        <template slot-scope="scope">
        <span style="margin-left: 10px">{{ scope.row.update_time | formatDate }}</span>
      </template>
        </el-table-column>
        <el-table-column
          label="操作"
          width="250">
          <template slot-scope="scope">
        <el-button
          size="mini"
          @click="handledisplay(scope.$index, scope.row)">查看</el-button>
        <el-button
          size="mini"
          type="danger"
          @click="handleedit(scope.$index, scope.row)">编辑</el-button>
          <el-button
          size="mini"
          type="danger"
          @click="handledelete(scope.$index, scope.row)">删除</el-button>
      </template>
        </el-table-column>
		  </el-table>
      </div>
      <div v-show="secondShow">
        <display ref="display" :formInline="formInline"></display>
      </div>
      <div v-show="thirdShow">
        <edit ref="edit" :formInline="formInline" :status="status"></edit>
      </div>
          <el-pagination v-show="fouthShow"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page.sync="currentPage2"
      :page-sizes="[1, 5, 10, 15, 20, 30]"
      :page-size="limit"
      layout="sizes, prev, pager, next, total"
      :total="datatotal">
    </el-pagination>
	</div>
</template>

<script>
  import btnGroup from '../../Common/btn-group.vue'
  import { formatDate } from '../../../assets/js/date.js'
  import http from '../../../assets/js/http'
  import display from './display.vue'
  import edit from './edit.vue'
  import add from './add.vue'
  export default {
    data() {
      return {
        tableData: [],
        device_type: '',
        sub_type: '',
        firstShow: true,
        secondShow: false,
        thirdShow: false,
        formInline: '',
        datatotal: 1,
        limit: 10,
        currentPage: 1,
        fouthShow: true,
        keywords: '',
        status: 1
      }
    },
    methods: {
      handleSizeChange(val) {
        this.limit = val
        this.getAlldata()
      },
      handleCurrentChange(val) {
        this.currentPage = val
        this.getAlldata()
      },
      handledisplay(index, row) {
        this.firstShow = false
        this.fouthShow = false
        this.secondShow = true
        this.formInline = this.tableData[index]
        // console.log(this.tableData[index])
      },
      handleedit(index, row) {
        this.firstShow = false
        this.fouthShow = false
        this.thirdShow = true
        this.status = 0
        this.formInline = this.tableData[index]
      },
      handledelete(index, row) {
        this.$confirm('确认删除吗?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消'
        }).then(() => {
          this.loading = true
          this.apiDelete('vue/article/delete/', row.id).then((res) => {
            // console.log('res = ', _g.j2s(res))
            this.handelResponse(res, (data) => {
              _g.toastMsg('success', '删除成功')
              this.getAlldata()
              // console.log(this.data)
            })
          })
        })
      },
      getAlldata() {
        this.loading = true
        const data = {
          params: {
            key: this.keywords,
            page: this.currentPage,
            pageSize: this.limit
          }
        }
        this.apiGet('vue/article/init', data).then((res) => {
          // console.log('res = ', _g.j2s(res))
          this.handelResponse(res, (data) => {
            this.tableData = data.data
            this.datatotal = data.total
            // console.log(this.data)
          })
        })
      },
      getKeywords() {
        this.keywords = ''
        this.currentPage = 1
      },
      init() {
        this.getKeywords()
        this.getAlldata()
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
      }
    },
    components: {
      btnGroup,
      display,
      edit,
      add,
      formatDate
    },
    mixins: [http],
    filters: {
      countchannel: function (value) {
        return value ? JSON.parse(value).length : 0
      },
      formatDate(time) {
        let times = parseInt(time + '000')
        let date = new Date(times)
        return formatDate(date, 'yyyy-MM-dd hh:mm')
      }
    }
  }
</script>
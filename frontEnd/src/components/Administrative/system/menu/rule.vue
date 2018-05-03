<template>
	<el-dialog ref="dialog" custom-class="w-900 h-480 ovf-auto" title="节点列表" :visible.sync="dialogVisible">
		<div class="pos-rel h-60">
		</div>
		<div>
			<el-table
			:data="tableData"
			stripe
			style="width: 100%">
				<el-table-column
				prop="type"
				label="类型"
				width="100">
				</el-table-column>
				<el-table-column
				prop="title"
				label="规则名称">
				</el-table-column>
				<el-table-column
				prop="name"
				label="规则标识"
				width="180">
				</el-table-column>
        <el-table-column
        label="规则标识"
        width="180">
        <template slot-scope="props">
        <el-button @click="selectRule(props.row)">选择</el-button>
        </template>
        </el-table-column>
			</el-table>
		</div>
	</el-dialog>
</template>
<style>
.search-btn {
	position: absolute;
	top: 0px;
	right: 0px;
}
</style>
<script>
  import http from '../../../../assets/js/http'

  export default {
    data() {
      return {
        tableData: [],
        dialogVisible: false
      }
    },
    methods: {
      open() {
        this.$refs.dialog.open()
      },
      closeDialog() {
        this.$parent.$refs.ruleList.dialogVisible = false
      },
      selectRule(item) {
        // alert(item.title)
        setTimeout(() => {
          this.$parent.form.rule_name = item.title
          this.$parent.form.rule_id = item.id
        }, 0)
        this.closeDialog()
      },
      getRules() {
        this.apiGet('admin/rules').then((res) => {
          this.handelResponse(res, (data) => {
            this.tableDataShow = _(data).forEach((ret) => {
              ret.showInput = false
            })
            this.tableData = this.tableDataShow
          })
        })
      }
    },
    created() {
      let data = store.state.rules
      if (data && data.length) {
        this.tableDataShow = _(data).forEach((res) => {
          res.showInput = false
        })
        this.tableData = this.tableDataShow
      } else {
        this.getRules()
      }
    },
    mixins: [http]
  }
</script>
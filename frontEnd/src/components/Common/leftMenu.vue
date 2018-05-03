<template>
<!-- <div>
<el-menu mode="vertical" default-active="/table" class="el-menu-vertical-demo" @select="handleselect" theme="dark" router>
<el-menu-item-group v-for="menu in menuData" :title="menu.title">
<el-menu-item v-for="item in menu.items" :index="item.path">&nbsp;&nbsp;&nbsp;&nbsp;{{item.name}}</el-menu-item>
</el-menu-item-group>
</el-menu>
</div> -->

	<div>  
		<div v-for="(secMenu, key) in menuData">
		<el-collapse v-model="activeName" accordion>
			<el-collapse-item :title="secMenu.title" :name="key">
			<div class="h-50" v-for="(item, key) in secMenu.child">
				<template v-if="item.menu == menu">
					<div class="w-100p h-50 p-l-40 left-menu pointer c-blue" @click="routerChange(item)">{{item.title}}</div>
				</template>
				<template v-else>
					<div class="w-100p h-50 p-l-40 left-menu pointer c-gra" @click="routerChange(item)">
						{{item.title}}
					</div>
				</template>
			</div>
			</el-collapse-item>
			</el-collapse>
		</div>
	</div>
</template>
<style>
cascader-menu, .el-cascader-menu__item.is-disabled:hover, .el-collapse-item__wrap {
    background-color: #324057;
}
 .el-collapse-item__header{
 	background-color: #324057;
 	color:white;
 	text-align:center;
 }
</style>
<script>
export default {
  props: ['menuData', 'menu'],
  data() {
    return {
      activeName: 0
    }
  },
  methods: {
    routerChange(item) 	{
      // 与当前页面路由相等则刷新页面
      if (item.url != this.$route.path) {
        router.push(item.url)
      } else {
        _g.shallowRefresh(this.$route.name)
      }
    }
  }
}
</script>
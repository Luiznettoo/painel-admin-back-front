<template>
	<div class="app">
		<AppHeader fixed>
			<SidebarToggler class="d-lg-none" display="md" mobile/>
            <span class="logo-lg"><b>Azul</b>Marketing</span>
			<SidebarToggler class="d-md-down-none" display="lg" style="float: left;"/>
		</AppHeader>
		<div class="app-body">
			<AppSidebar fixed>
				<SidebarHeader/>
				<SidebarForm/>
				<SidebarNav :navItems="nav"></SidebarNav>
				<SidebarFooter/>
				<SidebarMinimizer/>
			</AppSidebar>
			<main class="main">
				<Breadcrumb :list="list"/>
				<div class="container-fluid">
					<router-view></router-view>
				</div>
			</main>
		</div>
		<TheFooter>
			<!--footer-->
            &nbsp;
		</TheFooter>
	</div>
</template>

<script>
	import nav from '@/_nav';
	import {
		Aside as AppAside,
		AsideToggler,
		Breadcrumb,
		Footer as TheFooter,
		Header as AppHeader,
		Sidebar as AppSidebar,
		SidebarFooter,
		SidebarForm,
		SidebarHeader,
		SidebarMinimizer,
		SidebarNav,
		SidebarToggler
	} from '@coreui/vue';
	import DefaultAside from './DefaultAside';
	import DefaultHeaderDropdownAccnt from './DefaultHeaderDropdownAccnt';
	
	export default {
		name: 'DefaultContainer',
		components: {
			AsideToggler,
			AppHeader,
			AppSidebar,
			AppAside,
			TheFooter,
			Breadcrumb,
			DefaultAside,
			DefaultHeaderDropdownAccnt,
			SidebarForm,
			SidebarFooter,
			SidebarToggler,
			SidebarHeader,
			SidebarNav,
			SidebarMinimizer
		},
		data() {
			return {
				nav: nav.items
			};
		},
		computed: {
			name() {
				return this.$route.name;
			},
			list() {
				return this.$route.matched.filter((route) => route.name || route.meta.label);
			}
		}
	};
</script>

<style>
    .navbar {
        justify-content: left !important;
    }

    .logo-lg {
        -webkit-transition: width .3s ease-in-out;
        -o-transition: width .3s ease-in-out;
        transition: width .3s ease-in-out;
        display: block;
        float: left;
        height: 50px;
        font-size: 20px;
        line-height: 50px;
        text-align: center;
        width: 200px;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        padding: 0 15px;
        font-weight: 300;
        overflow: hidden;
    }
</style>

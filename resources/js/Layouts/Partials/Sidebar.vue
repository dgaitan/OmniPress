<template>
	<div>
		<div class="hidden lg:block navbar-menu relative z-40">
		    <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"></div>
	        <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pb-8 bg-white border-r overflow-y-auto">
	            <div class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-blue-50">
	              	<a class="text-xl font-semibold" href="#">
	                	<img class="h-8" src="artemis-assets/logos/artemis-logo-light.svg" alt="" width="auto">
	              	</a>
	            </div>
	            <div class="px-4 pb-6">
	            	<SidebarNav>
	            		<SidebarNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</SidebarNavLink>
	            	</SidebarNav>
	              	
	              	<SidebarNav>
	              		<template #title>Store</template>
	              		<SidebarNavLink :href="route('kinja.customers.index')" :active="route().current('kinja.customers.index')">Customers</SidebarNavLink>
	              		<SidebarNavLink :href="route('kinja.coupons.index')" :active="route().current('kinja.coupons.index')">Coupons</SidebarNavLink>
	              	</SidebarNav>

	              	<SidebarNav v-if="userCan(['run_sync'])">
	              		<template #title>Admin</template>
	              		<SidebarNavLink :href="route('kinja.sync.index')" :active="route().current('kinja.sync.index')">Sync</SidebarNavLink>
	              		<SidebarNavLink 
	              			:href="route('api-tokens.index')" 
	              			:active="route().current('api-tokens.index')" 
	              			v-if="userCan('manage_api_tokens')">
	              			Api Tokens
	              		</SidebarNavLink>
	              	</SidebarNav>

	              	<SidebarNav>
	              		<template #title>Account</template>
	              		<SidebarNavLink :href="route('profile.show')" :active="route().current('profile.show')">Profile</SidebarNavLink>
	              		
	              		<!-- Authentication -->
	              		<li>
                            <form @submit.prevent="logout">
                                <button type="button" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-cyan-400 hover:text-white transition-all rounded w-full">
                                	Log Out
                                </button>
                            </form>
	              		</li>
	              	</SidebarNav>
	            </div>
	        </nav>
	    </div>
	</div>
</template>

<script>
	import { defineComponent } from 'vue';
	import SidebarNav from '@/Layouts/Partials/SidebarNav.vue';
	import SidebarNavLink from '@/Layouts/Partials/SidebarNavLink.vue';

	export default defineComponent({
		components: {
			SidebarNav,
			SidebarNavLink
		},

		methods: {
			logout() {
                this.$inertia.post(route('logout'));
            },
		}
	})
</script>
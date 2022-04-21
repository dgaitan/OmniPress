<template>
	<div>
		<div class="hidden lg:block navbar-menu relative z-40">
		    <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-60"></div>
	        <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pb-8 border-r overflow-y-auto border-gray-300">
	            <div class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-blue-50">
	              	<a class="text-xl font-semibold" href="#">
	                	<img class="h-8" src="artemis-assets/logos/artemis-logo-light.svg" alt="" width="auto">
	              	</a>
	            </div>
	            <div class="px-4 pb-6">
	            	<SidebarNav>
	            		<SidebarNavLink :href="route('dashboard')" :active="route().current('dashboard')">
	            			<template #icon><HomeIcon class="w-5 h-5" /></template>
	            			Home
	            		</SidebarNavLink>
                        <SidebarNavLink :href="route('kinja.orders.index')" :active="route().current('kinja.orders.index')">
	            			<template #icon><InboxInIcon class="w-5 h-5" /></template>
	            			Orders
	            		</SidebarNavLink>
                        <SidebarNavLink :href="route('kinja.products.index')" :active="route().current('kinja.products.index')">
	              			<template #icon><ArchiveIcon class="w-5 h-5" /></template>
	              			Products
	              		</SidebarNavLink>
                        <SidebarNavLink :href="route('kinja.customers.index')" :active="route().current('kinja.customers.index')">
	              			<template #icon><UsersIcon class="w-5 h-5" /></template>
	              			Customers
	              		</SidebarNavLink>
                        <SidebarNavLink
                            :href="route('kinja.memberships.index')"
                            :active="route().current('kinja.memberships.index')">
	              			<template #icon><UserAddIcon class="w-5 h-5" /></template>
	              			Memberships
	              		</SidebarNavLink>
	            	</SidebarNav>

	              	<!-- Admin -->
	              	<SidebarNav v-if="userCan(['run_sync', 'add_user', 'admin_queues'])">
	              		<template #title>Admin</template>
                        <SidebarNavLink  v-if="userCan(['assign_roles'])" :href="route('kinja.users.index')" :active="route().current('kinja.users.index')">
	              			<template #icon><unicon name="user-square"></unicon></template>
	              			Users
	              		</SidebarNavLink>
	              		<SidebarNavLink  v-if="userCan(['run_sync'])" :href="route('kinja.sync.index')" :active="route().current('kinja.sync.index')">
	              			<template #icon><unicon name="sync"></unicon></template>
	              			Sync
	              		</SidebarNavLink>
                        <SidebarNavLink  v-if="userCan(['admin_queues'])" :href="route('kinja.queues.index')" :active="route().current('kinja.queues.index')">
	              			<template #icon><unicon name="clock"></unicon></template>
	              			Queues
	              		</SidebarNavLink>
	              		<SidebarNavLink
	              			:href="route('api-tokens.index')"
	              			:active="route().current('api-tokens.index')"
	              			v-if="userCan('manage_api_tokens')">
	              			<template #icon><unicon name="key-skeleton"></unicon></template>
	              			Api Tokens
	              		</SidebarNavLink>
	              	</SidebarNav>

	              	<!-- Account -->
	              	<SidebarNav>
	              		<template #title>Account</template>
	              		<SidebarNavLink :href="route('profile.show')" :active="route().current('profile.show')">
	              			<template #icon><unicon name="user-circle"></unicon></template>
	              			Profile
	              		</SidebarNavLink>

	              		<!-- Authentication -->
	              		<li>
                            <form @submit.prevent="logout">
                                <button type="button" class="logout flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-cyan-400 hover:text-white transition-all rounded w-full">
                                	<unicon name="signout" class="mr-3"></unicon>
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

<style type="text/css">
	.logout:hover svg { transition: all .4s; fill: #FFFFFF }
</style>

<script>
	import { defineComponent } from 'vue';
	import SidebarNav from '@/Layouts/Partials/SidebarNav.vue';
	import SidebarNavLink from '@/Layouts/Partials/SidebarNavLink.vue';
    import { HomeIcon, InboxInIcon, ArchiveIcon, UsersIcon, UserAddIcon } from '@heroicons/vue/solid'

	export default defineComponent({
		components: {
			SidebarNav,
			SidebarNavLink,
            HomeIcon,
            InboxInIcon,
            ArchiveIcon,
            UsersIcon,
            UserAddIcon
		},

		methods: {
			logout() {
                this.$inertia.post(route('logout'));
            },
		}
	})
</script>

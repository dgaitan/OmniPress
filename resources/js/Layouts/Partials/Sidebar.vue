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
	            <div class="pr-4 pb-6">
	            	<SidebarNav>
	            		<SidebarNavLink :href="route('dashboard')" :active="route().current('dashboard')">
	            			<template #icon><HomeIcon class="w-5 h-5" /></template>
	            			Home
	            		</SidebarNavLink>
                        <SidebarNavLink :href="route(routes.orders.index)" :active="isActive(routes.orders.activeIf)">
	            			<template #icon><InboxInIcon class="w-5 h-5" /></template>
	            			Orders

                            <template v-if="isActive(routes.orders.activeIf)" #subitems>
                                <SidebarNavSubLink
                                    :href="route('kinja.orders.printforiaOrders')" :active="route().current('kinja.orders.printforiaOrders')">
                                    Printforia
                                </SidebarNavSubLink>
                            </template>
	            		</SidebarNavLink>
                        <SidebarNavLink :href="routes.products.index" :active="isActive(routes.products.activeIf)">
	              			<template #icon><ArchiveIcon class="w-5 h-5" /></template>
	              			Products

							<template v-if="isActive(routes.products.activeIf)" #subitems>
                                <SidebarNavSubLink
                                    :href="route('kinja.products.inventory')" 
									:active="route().current('kinja.products.inventory')">
                                    Inventory
                                </SidebarNavSubLink>
                            </template>
	              		</SidebarNavLink>
                        <SidebarNavLink :href="route('kinja.customers.index')" :active="route().current('kinja.customers.index')">
	              			<template #icon><UsersIcon class="w-5 h-5" /></template>
	              			Customers
	              		</SidebarNavLink>
                        <SidebarNavLink
                            :href="routes.memberships.index"
                            :active="isActive(routes.memberships.activeIf)">
	              			<template #icon><UserAddIcon class="w-5 h-5" /></template>
	              			Memberships
	              		</SidebarNavLink>
                        <!-- <SidebarNavLink
                            :href="route('kinja.causes.index')"
                            :active="route().current('kinja.causes.index')">
	              			<template #icon><EmojiHappyIcon class="w-5 h-5" /></template>
	              			Causes
	              		</SidebarNavLink> -->
                        <SidebarNavLink
                            :href="routes.analytics.index"
                            :active="isActive(routes.analytics.activeIf)">
	              			<template #icon><ChartBarIcon class="w-5 h-5" /></template>
	              			Analytics

                            <template v-if="isActive(routes.analytics.activeIf)" #subitems>
                                <SidebarNavSubLink :href="route('kinja.analytics.causes')" :active="route().current('kinja.analytics.causes')">
                                    Causes
                                </SidebarNavSubLink>
                                <SidebarNavSubLink :href="route('kinja.analytics.userImpacts')" :active="route().current('kinja.analytics.userImpacts')">
                                    User Impacts
                                </SidebarNavSubLink>
                            </template>
	              		</SidebarNavLink>
	            	</SidebarNav>

	              	<!-- Admin -->
	              	<SidebarNav v-if="userCan(['run_sync', 'add_user', 'admin_queues'])">
	              		<template #title>Admin</template>
                        <SidebarNavLink  v-if="userCan(['assign_roles'])" :href="route('kinja.users.index')" :active="route().current('kinja.users.index')">
	              			<template #icon><UserGroupIcon class="w-5 h-5" /></template>
	              			Users
	              		</SidebarNavLink>
	              		<SidebarNavLink  v-if="userCan(['run_sync'])" :href="route('kinja.sync.index')" :active="route().current('kinja.sync.index')">
	              			<template #icon><RefreshIcon class="w-5 h-5" /></template>
	              			Sync
	              		</SidebarNavLink>
                        <SidebarNavLink  v-if="userCan(['admin_queues'])" :href="route('kinja.queues.index')" :active="route().current('kinja.queues.index')">
	              			<template #icon><ClockIcon class="w-5 h-5" /></template>
	              			Queues
	              		</SidebarNavLink>
	              		<SidebarNavLink
	              			:href="route('api-tokens.index')"
	              			:active="route().current('api-tokens.index')"
	              			v-if="userCan('manage_api_tokens')">
	              			<template #icon><KeyIcon class="w-5 h-5" /></template>
	              			Api Tokens
	              		</SidebarNavLink>
	              	</SidebarNav>

	              	<!-- Account -->
	              	<SidebarNav>
	              		<template #title>Account</template>
	              		<SidebarNavLink :href="route('profile.show')" :active="route().current('profile.show')">
	              			<template #icon><UserCircleIcon class="w-5 h-5" /></template>
	              			Profile
	              		</SidebarNavLink>

	              		<!-- Authentication -->
	              		<li>
                            <form @submit.prevent="logout">
                                <button type="button" class="flex text-md items-center py-2 px-7 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-all rounded w-full font-medium">
                                    <span class="mr-3">
                                	    <LogoutIcon class="w-5 h-5" />
                                    </span>
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
    import SidebarNavSubLink from '@/Layouts/Partials/SidebarNavSubLink.vue';
    import { HomeIcon, InboxInIcon, ArchiveIcon, UsersIcon, UserAddIcon, UserGroupIcon, RefreshIcon, ClockIcon, KeyIcon, UserCircleIcon, LogoutIcon, EmojiHappyIcon, ChartBarIcon } from '@heroicons/vue/solid'

	export default defineComponent({
		components: {
			SidebarNav,
			SidebarNavLink,
            SidebarNavSubLink,
            HomeIcon,
            InboxInIcon,
            ArchiveIcon,
            UsersIcon,
            UserAddIcon,
            UserGroupIcon,
            RefreshIcon,
            ClockIcon,
            KeyIcon,
            UserCircleIcon,
            LogoutIcon,
            EmojiHappyIcon,
            ChartBarIcon
		},

        data() {
            return {
                routes: {
                    orders: {
                        index: 'kinja.orders.index',
                        activeIf: [
                            'kinja.orders.index', 'kinja.orders.show',
                            'kinja.orders.printforiaOrders',
                            'kinja.orders.printforiaDetail'
                        ]
                    },
					products: {
						index: route('kinja.products.index'),
						activeIf: [
							'kinja.products.index',
							'kinja.products.inventory',
							'kinja.products.show'
						]
					},
                    memberships: {
                        index: route('kinja.memberships.index'),
                        activeIf: ['kinja.memberships.index', 'kinja.memberships.show']
                    },
                    analytics: {
                        index: route('kinja.analytics.index'),
                        activeIf: [
                            'kinja.analytics.index',
                            'kinja.analytics.causes',
                            'kinja.analytics.userImpacts'
                        ]
                    }
                }
            }
        },

		methods: {
			logout() {
                this.$inertia.post(route('logout'));
            },

            isActive(routes = []) {
                return routes.includes(route().current())
            }
		}
	})
</script>

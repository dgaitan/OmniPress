<template>
	<div>
		<Head :title="title" />

        <div class="min-h-screen bg-gray-100">
			<Header />
			<Sidebar />
	        <div class="mx-auto lg:ml-80 content">
                <jet-banner />
                <div class="content-wrapper">
	        	    <slot></slot>
                </div>
	        </div>
		</div>

	</div>
</template>

<style type="text/css">
    .content {
        padding-top: 65px;
    }

    .content-wrapper {
		padding-top: 2rem;
		padding-bottom: 6rem;
	}
</style>

<script >
	import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/inertia-vue3';
    import Header from '@/Layouts/Partials/Header.vue'
    import Sidebar from '@/Layouts/Partials/Sidebar.vue';
    import JetBanner from '@/Jetstream/Banner.vue'

    export default defineComponent({
        props: {
            title: String
        },

        components: {
        	JetBanner,
        	Header,
            Head,
            Sidebar
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    })
</script>

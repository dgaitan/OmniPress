<template>
	<table class="table-auto w-full">
		<thead>
			<tr class="text-xs text-gray-500 text-left">
              	<th
              		v-for="(column, index) in columns"
					:key="index"
					class="py-4 px-2 font-medium" v-bind:class="[index === 0 ? 'flex items-center pl-6' : '']">

                	<input v-if="index === 0" class="mr-3" type="checkbox" @click="(e) => selectIds(e.target.checked)">
                	<a
                        v-if="column.sortable"
                        @click="getOrderingLink($event, column.key)"
                        class="flex items-center cursor-pointer"
                        v-bind:class="[column.key === stateData.orderBy ? 'font-bold text-black' : '']">
	                  	<span>{{ column.name }}</span>
	                  	<span class="ml-2">
	                    	<svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
	                      		<path d="M7.85957 7.52667L4.99957 10.3933L2.13957 7.52667C2.01403 7.40114 1.84377 7.33061 1.66623 7.33061C1.4887 7.33061 1.31843 7.40114 1.1929 7.52667C1.06736 7.65221 0.996837 7.82247 0.996837 8.00001C0.996837 8.17754 1.06736 8.3478 1.1929 8.47334L4.52623 11.8067C4.65114 11.9308 4.82011 12.0005 4.99623 12.0005C5.17236 12.0005 5.34132 11.9308 5.46623 11.8067L8.79957 8.47334C8.86173 8.41118 8.91103 8.33739 8.94467 8.25617C8.97831 8.17496 8.99563 8.08791 8.99563 8.00001C8.99563 7.9121 8.97831 7.82505 8.94467 7.74384C8.91103 7.66262 8.86173 7.58883 8.79957 7.52667C8.73741 7.46451 8.66361 7.41521 8.5824 7.38157C8.50118 7.34793 8.41414 7.33061 8.32623 7.33061C8.23833 7.33061 8.15128 7.34793 8.07007 7.38157C7.98885 7.41521 7.91506 7.46451 7.8529 7.52667H7.85957ZM2.13957 4.47334L4.99957 1.60667L7.85957 4.47334C7.98447 4.59751 8.15344 4.6672 8.32957 4.6672C8.50569 4.6672 8.67466 4.59751 8.79957 4.47334C8.92373 4.34843 8.99343 4.17946 8.99343 4.00334C8.99343 3.82722 8.92373 3.65825 8.79957 3.53334L5.46623 0.200006C5.40426 0.137521 5.33052 0.0879247 5.24928 0.0540789C5.16804 0.0202331 5.08091 0.00280762 4.9929 0.00280762C4.90489 0.00280762 4.81775 0.0202331 4.73651 0.0540789C4.65527 0.0879247 4.58154 0.137521 4.51957 0.200006L1.18623 3.53334C1.06158 3.65976 0.992254 3.83052 0.993504 4.00805C0.994754 4.18559 1.06648 4.35535 1.1929 4.48001C1.31932 4.60466 1.49008 4.67398 1.66761 4.67273C1.84515 4.67148 2.01491 4.59976 2.13957 4.47334Z" fill="#67798E"></path>
	                    	</svg>
	                  	</span>
                	</a>
                	<span v-else class="flex items-center">
                		{{ column.name }}
                	</span>
            	</th>
            </tr>
		</thead>
		<tbody>
			<slot name="body"></slot>
		</tbody>
	</table>
</template>

<script>
	import { defineComponent } from 'vue';

	export default defineComponent({
		props: {
			columns: Array,
            stateData: Object,
            selectIds: Function
		},

		methods: {
			/**
			 * Trigger order link
			 *
			 * @param string link - the order by link
			 * @returns string
			 */
			getOrderingLink(e, key) {
				e.preventDefault();
				let currentUrl = window.location.href.split('?')[0];
                this.stateData.orderBy = key;
                this.stateData.order = this.stateData.order === 'desc' ? 'asc' : 'desc'

                this.$inertia.get(currentUrl, {
                    ...this.stateData
                }, { replace: true });
			}
		}

	})
</script>

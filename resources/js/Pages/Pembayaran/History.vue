<script setup>
	import { ref, watch } from 'vue'
	import { Head, Link, router } from '@inertiajs/vue3'
	import Layout from '../../Layout.vue'
	import Pagination from '../../components/Pagination.vue'
	import '../../../css/data-table.css'

	const limit = ref(10)
	const search = ref('')

	function reload() {
		router.reload({
			data: {
				limit: limit.value,
				search: search.value
			}
		})
	}

	watch(limit, value => reload())

	defineProps({pembayaran: Object})
</script>
<template>
	<Head title="History Pembayaran" />
	<Layout title="History Pembayaran">
		<div class="data-card">
			<div class="card-header">
				<div>Tampilkan : <select name="limit" id="limit" class="form-select" v-model="limit">
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select> dari {{ pembayaran.length }} Data</div>
				<form class="search-input" @submit.prevent="reload()">
					<div class="input-group-btn">
						<input type="text" name="search" class="form-input" v-model="search" placeholder="Nama / NIS / Kelas">
						<button>
							Cari
						</button>
					</div>
				</form>
			</div>
			<div class="card-body">
				<div class="overflow-x-auto">
					<table class="table">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nama Siswa</th>
								<th>NIS</th>
								<th>Kelas</th>
								<th>Bulan</th>
								<th>Tanggal Bayar</th>
								<th>Nominal</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(value, i) in pembayaran.data">
								<td>{{ i+1 }}</td>
								<td>{{ value.nama_siswa }}</td>
								<td>
									<Link class="link" :href="'/siswa/'+value.nis">{{ value.nis }}</Link>
								</td>
								<td>
									<Link class="link" :href="'/kelas/'+value.id_kelas">{{ value.kelas }}</Link>
								</td>
								<td>{{ value.bulan }}</td>
								<td>{{ value.tanggal }}</td>
								<td>{{ value.nominal }}</td>
							</tr>
						</tbody>
					</table>
					<div class="footer">
						<Pagination :links="pembayaran.meta.links" />
						<a href="history-pembayaran-alumni.php">History pembayaran alumni</a>
					</div>
				</div>
			</div>
		</div>
	</Layout>
</template>
<!-- <script>
	import { ref, watch } from 'vue'
	import { Head, Link, router } from '@inertiajs/vue3'
	import Layout from '../../Layout.vue'
	import Pagination from '../../components/Pagination.vue'
	import '../../../css/data-table.css'
	import throttle from 'lodash/throttle'

	// const limit = ref(10)
	// const search = ref("")

	// function reload(limit) {
	// 	router.reload({
	// 		data: {
	// 			limit,
	// 			search: search.value,
	// 		}
	// 	})
	// }

	// watch(limit, value => reload(value))
	// watch(search, value => reload(10))

	// defineProps({pembayaran: Object})

	export default {
		components: { Head, Link, Layout, Pagination },
		props: {
			pembayaran: Object
		},
		data() {
			return {
				form: {
					limit: 10,
					search: ''
				}
			}
		},
		watch: {
			form: {
				deep: true,
				handler: throttle( function() {
					this.$inertia.get('/pembayaran/history', this.form, {preserveState: true})
				}, 150)
			}
		}
	}
</script> -->
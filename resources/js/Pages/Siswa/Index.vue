<script setup>
	import { ref, watch,computed } from 'vue'
	import { Head, Link, router } from '@inertiajs/vue3'
	import Layout from '../../Layout.vue'
	import Pagination from '../../components/Pagination.vue'
	import '../../../css/data-table.css'
	import Swal from 'sweetalert2'

	const limit = ref(10)
	const search = ref('')
	const dropdownActive = ref(null)
	const selected = ref([])

	function reload() {
		console.log(search.value)
		router.reload({
			data: {
				limit: limit.value,
				search: search.value
			}
		})
	}
	
	function dropdown(id) {
		console.log(id)
		dropdownActive.value = dropdownActive.value == id ? null : id
	}

	function confirmDelete(nis) {
		Swal.fire({
			title: 'Anda yakin ingin menghapus data tersebut?',
			text: 'Menghapusnya juga akan menghapus pembayaran siswa tersebut',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			showLoaderOnConfirm: true,
			preConfirm: () => new Promise(resolve => router.delete('/siswa/'+nis, {
				preserveState: true,
				preserveScroll: true,
				onSuccess: page => {
					resolve()
					Swal.fire({
						title: page.props.flash.swal[1],
						icon: page.props.flash.swal[0],
					})
				}
			})),
			allowOutsideClick: () => !Swal.isLoading()
		})
	}

	function confirmMultipleDelete() {
		Swal.fire({
			title: `Anda yakin ingin menghapus ${selected.value.length} data tersebut?`,
			text: 'Menghapusnya juga akan menghapus pembayaran siswa tersebut',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			showLoaderOnConfirm: true,
			preConfirm: () => new Promise(resolve => router.post('/siswa/delete', {nis: selected.value}, {
				preserveState: true,
				preserveScroll: true,
				onSuccess: page => {
					resolve()
					Swal.fire({
						title: page.props.flash.swal[1],
						icon: page.props.flash.swal[0],
					})
				}
			})),
			allowOutsideClick: () => !Swal.isLoading()
		})
	}

	watch(limit, value => reload())
	// watch(dropdownActive, (newVal, oldVal) => {
	// 	dropdownActive.value = oldVal == newVal ? null : newVal
	// 	console.log(dropdownActive.value)
	// })

	const props = defineProps({data: Object, count: Number})

	const selectAll = ref(false)
	// watch(selected, value => selectAll.value = selected.value.length == Object.values(props.data.data).length)
	watch(selectAll, value => selected.value = value ? Object.values(props.data.data).map(siswa => siswa.nis) : [])
</script>
<template>
	<Head title="Data Siswa" />
	<Layout title="Data Siswa">
		<div class="data-card">
			<div class="card-header">
				<Link href="/siswa/create" class="btn btn-blue">Tambah Siswa</Link>
				<form class="search-input" @submit.prevent="reload">
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
								<th>
									<input type="checkbox" name="select-all" id="select-all" v-model="selectAll">
								</th>
								<th>No.</th>
								<th>Nama Siswa</th>
								<th>NIS</th>
								<th>Kelas</th>
								<th>No. Telp.</th>
								<th>Alamat</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(siswa, i) in Object.values(data.data)">
								<td><input type="checkbox" class="delete-checkbox" :value="siswa.nis" v-model="selected"></td>
								<td>{{ i + 1 }}</td>
								<td>{{ siswa.nama }}</td>
								<td>{{ siswa.nis }}</td>
								<td>
									<Link class="link" :href="'/kelas/'+siswa.id_kelas">{{siswa.kelas}}</Link>
								</td>
								<td>{{ siswa.no_telp }}</td>
								<td>{{ siswa.alamat }}</td>
								<td>
									<!-- <div class="action">
										<a href="../pembayaran/?nis=<?= $value['nis']; ?>" class="btn btn-blue">Pembayaran</a>
										<a href="edit.php?nis=<?= $value['nis']; ?>" class="btn btn-yellow">Edit</a>
										<button type="button" class="btn btn-red" onclick="confirmDelete(event, '<?= $value['nis'] ?>')">Hapus</button>
									</div> -->
									<div class="action-dropdown">
										<button data-toggle="action-dropdown" @click="dropdown(siswa.nis)">
											<svg xmlns="http://www.w3.org/2000/svg" width="6px" viewBox="0 0 128 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"/></svg>
										</button>
										<div class="dropdown-list" v-if="dropdownActive == siswa.nis">
											<ul>
												<li>
													<Link class="dropdown-item" :href="'/pembayaran?nis='+siswa.nis">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M96 96V320c0 35.3 28.7 64 64 64H576c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H160c-35.3 0-64 28.7-64 64zm64 160c35.3 0 64 28.7 64 64H160V256zM224 96c0 35.3-28.7 64-64 64V96h64zM576 256v64H512c0-35.3 28.7-64 64-64zM512 96h64v64c-35.3 0-64-28.7-64-64zM448 208c0 44.2-35.8 80-80 80s-80-35.8-80-80s35.8-80 80-80s80 35.8 80 80zM48 120c0-13.3-10.7-24-24-24S0 106.7 0 120V360c0 66.3 53.7 120 120 120H520c13.3 0 24-10.7 24-24s-10.7-24-24-24H120c-39.8 0-72-32.2-72-72V120z"/></svg>
														Pembayaran
													</Link>
												</li>
												<li>
													<Link class="dropdown-item" :href="'/siswa/'+siswa.nis+'/edit'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>Edit</Link>
												</li>
												<li><button class="dropdown-item" type="button" @click="confirmDelete(siswa.nis)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>Hapus</button></li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
						<div class="footer" style="margin-bottom: 0.75rem;">
							<a href="#" @click.prevent="confirmMultipleDelete()">Hapus data terpilih</a>
							<Pagination :links="data.links" />
						</div>
						<div class="footer">
							<div style="display: flex; align-items: center; grid-gap: 0.5rem;">
								<label for="limit">Tampilkan : </label>
									<select name="limit" id="limit" class="form-select" v-model="limit">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> dari {{ count }} Data
							</div>
							<Link href="/siswa/alumni">Lihat siswa alumni</Link>
						</div>
				</div>
			</div>
		</div>
	</Layout>
</template>
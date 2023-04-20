<script setup>
	import { ref, watch, computed } from 'vue'
	import { Head, Link, router, usePage } from '@inertiajs/vue3'
	import Layout from '../../Layout.vue'
	import '../../../css/pembayaran.css'
	import Swal from 'sweetalert2'

	const props = defineProps(['siswa', 'semester'])

	const user = computed(() => usePage().props.auth.user)
	const nis = ref(props.siswa ? props.siswa.nis : '')
	const semester = ref(props.semester ?? '')
	
	watch(semester, value => getPembayaran())

	function getPembayaran() {
		router.get('/pembayaran', {
			nis: nis.value,
			semester: semester.value,
		}, {preserveScroll: true})
	}

	function confirmBayar(nis, bulan, tahun) {
		Swal.fire({
			title: 'Anda yakin ingin membayar?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Bayar',
			showLoaderOnConfirm: true,
			preConfirm: () => new Promise(resolve => router.post('/pembayaran/bayar', {nis, bulan, tahun}, {
				preserveState: true,
				preserveScroll: true,
				onSuccess: page => {
					resolve()
				}
			})),
			allowOutsideClick: () => !Swal.isLoading()
		}).then(result => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Pembayaran berhasil dilakukan',
					icon: 'success',
				})
			}
		})
	}

</script>
<template>
	<Head title="Pembayaran" />
	<Layout title="Pembayaran">
		<form action="" id="form-inline" @submit.prevent="getPembayaran">
			<div class="input-wrapper">
				<div>
					<label for="" class="form-label">Cari NIS</label>
					<div class="input-group-btn">
						<input type="number" name="nis" class="form-input" v-model="nis">
						<button>
							Cari
						</button>
					</div>
				</div>
			</div>
		</form>
		<div v-if="siswa">
			<div class="data-siswa">
				<table class="table table-border-dark">
					<tbody>
						<tr>
							<td>Nama</td>
							<td>{{ siswa.nama }}</td>
						</tr>
						<tr>
							<td>NIS</td>
							<td>{{ siswa.nis }}</td>
						</tr>
						<tr>
							<td>Kelas</td>
							<td>{{ siswa.kelas }}</td>
						</tr>
						<tr>
							<td>Angkatan</td>
							<td>{{ siswa.tahun_masuk }}</td>
						</tr>
						<!-- <tr>
							<td>NISN</td>
							<td><?= $siswa['nisn']; ?></td>
						</tr> -->
						<!-- <tr>
							<td>Email</td>
							<td>imadeaditya4@gmail.com</td>
						</tr> -->
						<tr>
							<td>No. Telp.</td>
							<td>{{ siswa.no_telp }}</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>{{ siswa.alamat }}</td>
						</tr>
					</tbody>
				</table>
				<div style="display: flex; justify-content: space-between; align-items: center;">
					<div>
						<label for="semester">Semester</label>
						<select name="semester" id="semester" class="form-select" v-model="semester">
							<option v-for="i in 6" :value="i">Semester {{ i }}</option>
							<option value="all">Semua Semester</option>
						</select>
					</div>
					<a v-if="user.level_user == 'admin'" href="" class="btn btn-blue btn-icon"><svg width="14px" fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>Cetak</a>
				</div>
			</div>
			<div class="data-pembayaran">
				<table class="table">
					<thead>
						<tr>
							<th>Bulan</th>
							<th>Batas Pembayaran</th>
							<th>Tanggal Bayar</th>
							<th>Nominal</th>
							<th>Keterangan</th>
							<th></th>
						</tr>
					</thead>
					<tbody v-for="pembayaran in siswa.pembayaran">
						<tr>
							<td>{{ pembayaran.bulan_dibayar }}</td>
							<td>{{ pembayaran.batas_waktu }}</td>
							<td>{{ pembayaran.tanggal_bayar }}</td>
							<td>{{ pembayaran.nominal }}</td>
							<td><span class="badge" :class="pembayaran.status_badge">{{ pembayaran.keterangan }}</span></td>
							<td>
								<button v-if="!pembayaran.lunas" class="btn btn-blue" @click="confirmBayar(siswa.nis, pembayaran.bulan, pembayaran.tahun)">Bayar</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</Layout>
</template>
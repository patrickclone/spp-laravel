<script setup>
	import { ref, watch } from 'vue'
	import { Head, Link, router, useForm } from '@inertiajs/vue3'
	import Layout from '../../Layout.vue'
	import PasswordInput from '../../components/PasswordInput.vue'
	// import '../../../css/data-table.css'

	const props = defineProps(['edit', 'siswa', 'kelas'])
	
	const form = useForm(props.siswa ?? {
		nis: null,
		nama: null,
		nisn: null,
		password: null,
		id_kelas: '',
		no_telp: null,
		alamat: null,
	})
</script>
<template>
	<Head :title="(edit ? 'Edit' : 'Tambah') + ' Data Siswa'" />
	<Layout :title="(edit ? 'Edit' : 'Tambah') + ' Data Siswa'">
		<form action="" method="post" class="form-half" @submit.prevent="form.post('/siswa')">
			<div class="mb-3">
				<label for="nis" class="form-label">NIS</label>
				<input type="number" id="nis" name="nis" class="form-input" :class="{'invalid': form.errors.nis}" placeholder="Masukkan NIS" v-model="form.nis">
				<div class="input-error" v-if="form.errors.nis">{{ form.errors.nis }}</div>
			</div>
			<div class="mb-3">
				<label for="nama" class="form-label">Nama Siswa</label>
				<input type="text" name="nama" id="nama" autocomplete="off" class="form-input" :class="{'invalid': form.errors.nama}" placeholder="Masukkan nama" v-model="form.nama">
				<div class="input-error" v-if="form.errors.nama">{{ form.errors.nama }}</div>
			</div>
			<div class="mb-3">
				<label for="nisn" class="form-label">NISN</label>
				<input type="number" name="nisn" id="nisn" class="form-input" :class="{'invalid': form.errors.nisn}" placeholder="Masukkan NISN" v-model="form.nisn">
				<div class="input-error" v-if="form.errors.nisn">{{ form.errors.nisn }}</div>
			</div>
			<div class="mb-3">
				<label for="kelas" class="form-label">Kelas</label>
				<select name="kelas" id="kelas" :class="{'invalid': form.errors.id_kelas}" v-model="form.id_kelas">
					<option disabled value="">Pilih Kelas</option>
					<option :value="k.id" v-for="k in kelas">{{ k.kelas }}</option>
				</select>
				<div class="input-error" v-if="form.errors.id_kelas">{{ form.errors.id_kelas }}</div>
			</div>
			<PasswordInput v-model="form.password" :error="form.errors.password" />
			<div class="mb-3">
				<label for="no_telp" class="form-label">No. Telp.</label>
				<input type="text" name="no_telp" id="no_telp" class="form-input" :class="{'invalid': form.errors.no_telp}" placeholder="Masukkan nomor telepon Indonesia valid" v-model="form.no_telp">
				<div class="input-error" v-if="form.errors.no_telp">{{ form.errors.no_telp }}</div>
			</div>
			<div class="mb-3">
				<label for="alamat" class="form-label">Alamat</label>
				<textarea name="alamat" id="alamat" rows="3" :class="{'invalid': form.errors.alamat}" v-model="form.alamat"></textarea>
				<div class="input-error" v-if="form.errors.alamat">{{ form.errors.alamat }}</div>
			</div>
			<div class="submit">
				<button name="submit" class="btn btn-blue">Tambah</button>
			</div>
		</form>
	</Layout>
</template>
<?php

namespace App\Http\Requests;

use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->level_user == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
           'nis' => 'required|numeric|unique:siswa',
           'nama' => 'required',
           'nisn' => 'required|numeric|unique:siswa',
           'password' => 'required|min:5',
           'id_kelas' => 'required|exists:kelas,id',
           'no_telp' => 'required|numeric',
           'alamat' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'nis' => 'NIS',
            'nisn' => 'NISN',
            'id_kelas' => 'kelas'
        ];
    }

    public function validated($key = null, $default = null): array
    {
        if ($this->has('password')) {
            return array_merge(parent::validated(), [
                'password' => $this->input('password'),
                'tahun_masuk' => Kelas::find($this->input('id_kelas'))->only(['tahun_angkatan'])['tahun_angkatan']
            ]);
        }
        return parent::validated();
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->input('password')),
            'tahun_masuk' => Kelas::find($this->input('id_kelas'))->only(['tahun_angkatan'])['tahun_angkatan']
        ]);
    }
}

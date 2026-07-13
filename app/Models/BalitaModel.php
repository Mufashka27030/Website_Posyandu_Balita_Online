<?php

namespace App\Models;

use CodeIgniter\Model;

class BalitaModel extends Model
{
    protected $table          = 'balita';
    protected $primaryKey     = 'id_balita';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'id_posyandu',
        'id_user',
        'nama_balita',
        'nama_ibu',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'latitude',
        'longitude',
    ];

    protected $validationRules = [
        'id_user'       => 'required|integer',
        'nama_balita'   => 'required|min_length[2]|max_length[100]',
        'nama_ibu'      => 'required|min_length[2]|max_length[100]',
        'jenis_kelamin'  => 'required|in_list[L,P]',
        'tanggal_lahir'  => 'required|valid_date[Y-m-d]',
        'alamat'         => 'permit_empty|max_length[255]',
        'latitude'       => 'permit_empty|numeric',
        'longitude'      => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'nama_balita' => [
            'required'   => 'Nama balita wajib diisi',
            'min_length' => 'Nama balita minimal 2 karakter',
        ],
        'nama_ibu' => [
            'required'   => 'Nama ibu wajib diisi',
            'min_length' => 'Nama ibu minimal 2 karakter',
        ],
        'jenis_kelamin' => [
            'required'  => 'Jenis kelamin wajib dipilih',
            'in_list'   => 'Jenis kelamin harus L atau P',
        ],
        'tanggal_lahir' => [
            'required'    => 'Tanggal lahir wajib diisi',
            'valid_date'  => 'Format tanggal lahir tidak valid',
        ],
    ];

    /**
     * Ambil balita milik user tertentu (ownership query).
     */
    public function getByUserId(int $idUser): array
    {
        return $this
            ->where('id_user', $idUser)
            ->orderBy('nama_balita', 'ASC')
            ->findAll();
    }

    /**
     * Ambil balita berdasarkan ID dengan validasi kepemilikan (IDOR protection).
     */
    public function findByIdOwned(int $id, ?int $idUser = null): ?array
    {
        if ($idUser !== null) {
            return $this
                ->where('id_balita', $id)
                ->where('id_user', $idUser)
                ->first();
        }

        return $this->find($id);
    }

    /**
     * Ambil semua balita dengan status gizi terbaru (batch query — eliminasi N+1).
     * Mengembalikan array dengan key = id_balita, value = status_gizi terbaru.
     */
    public function getLatestStatusByBalitaIds(array $balitaIds): array
    {
        if (empty($balitaIds)) {
            return [];
        }

        $db = \Config\Database::connect();
        $rows = $db->table('pengukuran')
            ->select('id_balita, status_gizi')
            ->whereIn('id_balita', $balitaIds)
            ->orderBy('id_balita', 'ASC')
            ->orderBy('tanggal_ukur', 'DESC')
            ->get()
            ->getResultArray();

        $latest = [];
        foreach ($rows as $row) {
            $idBalita = $row['id_balita'];
            if (! isset($latest[$idBalita])) {
                $latest[$idBalita] = $row['status_gizi'];
            }
        }

        return $latest;
    }
}
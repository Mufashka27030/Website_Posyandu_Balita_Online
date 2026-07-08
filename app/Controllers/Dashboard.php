<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;

class Dashboard extends BaseController
{
    public function index()
{
    $balitaModel = new BalitaModel();
    $pengukuranModel = new PengukuranModel();

    $role = strtolower((string) session()->get('role'));

    if ($role === 'orangtua') {

        $balita = $balitaModel
            ->where('id_user', session()->get('id'))
            ->findAll();

    } else {

        $balita = $balitaModel->findAll();

    }

    $totalBalita = count($balita);
    $normal = 0;
    $stunting = 0;
    $berat = 0;

    foreach($balita as $b){

        $ukur = $pengukuranModel
            ->where('id_balita',$b['id_balita'])
            ->orderBy('tanggal_ukur','DESC')
            ->first();

        if(!$ukur){
            continue;
        }

        switch($ukur['status_gizi']){

            case 'Normal':
                $normal++;
            break;

            case 'Stunting':
                $stunting++;
            break;

            case 'Stunting Berat':
                $berat++;
            break;

        }

    }

    return view('dashboard/index',[

        'total_balita'=>$totalBalita,

        'total_normal'=>$normal,

        'total_stunting'=>$stunting,

        'stunting_berat'=>$berat

    ]);

    }

    public function statistik()
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

        $role = strtolower((string) session()->get('role'));

        // Scope orangtua: hanya balita milik user login
        if ($role === 'orangtua') {
            $idUser = (int) session()->get('id');
            $balita = $balitaModel
                ->where('id_user', $idUser)
                ->findAll();
        } else {
            $balita = $balitaModel->findAll();
        }

        $totalBalita = count($balita);
        $totalNormal = 0;
        $totalStunting = 0;
        $stuntingBerat = 0;

        foreach ($balita as $row) {
            $pengukuran = $pengukuranModel
                ->where('id_balita', $row['id_balita'])
                ->orderBy('tanggal_ukur', 'DESC')
                ->first();

            if (! $pengukuran) {
                continue;
            }

            if ($pengukuran['status_gizi'] === 'Normal') {
                $totalNormal++;
            } elseif ($pengukuran['status_gizi'] === 'Stunting') {
                $totalStunting++;
            } elseif ($pengukuran['status_gizi'] === 'Stunting Berat') {
                $stuntingBerat++;
            }
        }

        $persentase = $totalBalita > 0
            ? (($totalStunting + $stuntingBerat) / $totalBalita) * 100
            : 0;

        return view('dashboard/statistik', [
            'total_balita' => $totalBalita,
            'total_stunting' => $totalStunting,
            'stunting_berat' => $stuntingBerat,
            'total_normal' => $totalNormal,
            'persentase' => round($persentase, 2),
            'warning' => $stuntingBerat,
        ]);
    }

}

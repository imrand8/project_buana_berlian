<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Notifikasi
        $travelMenunggu = PesananTravel::where('status_pesanan', 'menunggu_verifikasi')->count();
        $kargoMenunggu = PesananKargo::where('status_pesanan', 'menunggu_verifikasi')->count();
        $menungguValidasi = $travelMenunggu + $kargoMenunggu;
        $ktmMenunggu = User::where('status_mahasiswa', 'menunggu_verifikasi')->count();

        // 2. Statistik Pesanan Hari Ini
        $travelHariIni = PesananTravel::whereDate('created_at', $today)->count();
        $kargoHariIni = PesananKargo::whereDate('created_at', $today)->count();
        $pesananHariIni = $travelHariIni + $kargoHariIni;

        // 3. Keuangan Omzet Lunas
        $omzetTravel = PesananTravel::whereDate('created_at', $today)->where('status_pesanan', 'lunas')->sum('total_harga');
        $omzetKargo = PesananKargo::whereDate('created_at', $today)->where('status_pesanan', 'lunas')->sum('total_harga');
        $omzetHariIni = $omzetTravel + $omzetKargo;

        // 4. Operasional Booking Aktif
        $bookingAktif = PesananTravel::whereIn('status_pesanan', ['menunggu_verifikasi', 'lunas'])->count() + 
                        PesananKargo::whereIn('status_pesanan', ['menunggu_verifikasi', 'lunas'])->count();

        // 5. Berangkat Hari Ini
        $berangkatTravel = PesananTravel::whereHas('jadwal', function($q) use ($today) {
            $q->whereDate('tanggal_berangkat', $today);
        })->where('status_pesanan', 'lunas')->count();
        $berangkatKargo = PesananKargo::whereDate('tanggal_berangkat', $today)->where('status_pesanan', 'lunas')->count();
        $berangkatHariIni = $berangkatTravel + $berangkatKargo;

        // 6. Data Grafik Trend Dinamis (Mingguan, Bulanan, Tahunan)
        $tahunIni = Carbon::now()->year;
        $bulanIni = Carbon::now()->month;

        // Tarik semua data tahun ini (Biar nggak berat query berulang kali)
        $travelTahunIni = PesananTravel::whereYear('created_at', $tahunIni)->get();
        $kargoTahunIni = PesananKargo::whereYear('created_at', $tahunIni)->get();
        $semuaPesanan = $travelTahunIni->concat($kargoTahunIni);

        // A. Tahunan (Jan - Des)
        $labelTahunan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataTahunan = array_fill(0, 12, 0); // Bikin array isi 12 angka nol
        foreach($semuaPesanan as $trx) {
            $bulanIndex = (int)Carbon::parse($trx->created_at)->format('m') - 1;
            $dataTahunan[$bulanIndex]++;
        }

        // B. Bulanan (Tanggal 1 s/d Akhir Bulan)
        $jumlahHari = Carbon::now()->daysInMonth;
        $labelBulanan = [];
        $dataBulanan = array_fill(0, $jumlahHari, 0);
        for($i = 1; $i <= $jumlahHari; $i++) { $labelBulanan[] = (string)$i; }
        
        $pesananBulanIni = $semuaPesanan->filter(function($trx) use ($bulanIni) {
            return Carbon::parse($trx->created_at)->month == $bulanIni;
        });
        foreach($pesananBulanIni as $trx) {
            $hariIndex = (int)Carbon::parse($trx->created_at)->format('d') - 1;
            $dataBulanan[$hariIndex]++;
        }

        // C. Mingguan (7 Hari Terakhir)
        $labelMingguan = [];
        $dataMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labelMingguan[] = $date->translatedFormat('D'); // Sen, Sel, Rab
            
            $count = $semuaPesanan->filter(function($trx) use ($date) {
                return Carbon::parse($trx->created_at)->format('Y-m-d') == $date->format('Y-m-d');
            })->count();
            $dataMingguan[] = $count;
        }

        return view('admin.dashboard', compact(
            'menungguValidasi', 'pesananHariIni', 'omzetHariIni', 
            'bookingAktif', 'berangkatHariIni', 'ktmMenunggu',
            'labelMingguan', 'dataMingguan',
            'labelBulanan', 'dataBulanan',
            'labelTahunan', 'dataTahunan'
        ));
    }
}
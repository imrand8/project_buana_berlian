<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesananTravel;
use App\Models\PesananKargo;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Set default filter: Awal bulan sampai Akhir bulan ini
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $layanan = $request->layanan ?? 'semua';

        $travels = collect();
        $kargos = collect();

        // Tarik data Travel LUNAS jika filter sesuai
        if ($layanan == 'semua' || $layanan == 'travel') {
            $travels = PesananTravel::where('status_pesanan', 'lunas')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get();
        }

        // Tarik data Kargo LUNAS jika filter sesuai
        if ($layanan == 'semua' || $layanan == 'kargo') {
            $kargos = PesananKargo::where('status_pesanan', 'lunas')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get();
        }

        // Gabungkan dan urutkan dari yang terbaru
        $semuaTransaksi = $travels->concat($kargos)->sortByDesc('created_at');

        // Hitung Total Uang dan Item
        $totalPendapatan = $semuaTransaksi->sum('total_harga');
        $totalItem = $semuaTransaksi->count();

        // Siapkan Data untuk Grafik (Dikelompokkan per tanggal)
        $chartData = $semuaTransaksi->groupBy(function($item) {
            return Carbon::parse($item->created_at)->format('d M');
        })->map(function($row) {
            return $row->sum('total_harga');
        })->sortKeys();

        $labels = $chartData->keys()->toJson();
        $data = $chartData->values()->toJson();

        return view('admin.laporan.index', compact(
            'semuaTransaksi', 'totalPendapatan', 'totalItem', 'startDate', 'endDate', 'layanan', 'labels', 'data'
        ));
    }

// --- FUNGSI EXPORT REKAP (SUMMARY) SUPER RAPI ---
    public function exportRekap(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();

        $travels = PesananTravel::where('status_pesanan', 'lunas')->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        $kargos = PesananKargo::where('status_pesanan', 'lunas')->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        $semua = $travels->concat($kargos);

        $rekapHarian = $semua->groupBy(function($item) { return Carbon::parse($item->created_at)->format('d F Y'); })->sortKeys();

        $fileName = "Rekap_Omzet_{$startDate}_sd_{$endDate}.xls";

        // Desain Tabel Excel dengan HTML
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><td colspan="3" align="center" style="font-size: 16px; font-weight: bold;">REKAPITULASI OMZET BUANA BERLIAN</td></tr>';
        $html .= '<tr><td colspan="3" align="center">Periode: ' . $startDate . ' s/d ' . $endDate . '</td></tr>';
        $html .= '<tr><td colspan="3"></td></tr>'; // Spasi
        
        // Header Tabel (Warna Hijau)
        $html .= '<tr style="background-color: #22c55e; color: #ffffff; font-weight: bold; text-align: center;">
                    <th width="150">Tanggal Transaksi</th>
                    <th width="150">Jumlah Transaksi</th>
                    <th width="200">Total Pendapatan (Rp)</th>
                  </tr>';

        $totalSemua = 0;
        foreach ($rekapHarian as $tanggal => $transaksis) {
            $jumlahTrx = $transaksis->count();
            $pendapatan = $transaksis->sum('total_harga');
            $totalSemua += $pendapatan;
            
            $html .= "<tr>
                        <td align='center'>{$tanggal}</td>
                        <td align='center'>{$jumlahTrx} Item</td>
                        <td align='right'>" . number_format($pendapatan, 0, ',', '.') . "</td>
                      </tr>";
        }

        // Footer Total
        $html .= "<tr style='font-weight: bold; background-color: #e2e8f0;'>
                    <td colspan='2' align='right'>TOTAL KESELURUHAN OMZET</td>
                    <td align='right' style='color: #22c55e;'>Rp " . number_format($totalSemua, 0, ',', '.') . "</td>
                  </tr>";
        $html .= '</table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    // --- FUNGSI EXPORT DETAIL SUPER RAPI ---
    public function exportDetail(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $layanan = $request->layanan ?? 'semua';

        $travels = collect(); $kargos = collect();

        if ($layanan == 'semua' || $layanan == 'travel') {
            $travels = PesananTravel::where('status_pesanan', 'lunas')->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        }
        if ($layanan == 'semua' || $layanan == 'kargo') {
            $kargos = PesananKargo::where('status_pesanan', 'lunas')->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        }

        $semuaTransaksi = $travels->concat($kargos)->sortByDesc('created_at');

        $fileName = "Detail_Transaksi_{$startDate}_sd_{$endDate}.xls";

        // Desain Tabel Excel dengan HTML
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<tr><td colspan="7" align="center" style="font-size: 16px; font-weight: bold;">LAPORAN RINCIAN TRANSAKSI MASUK</td></tr>';
        $html .= '<tr><td colspan="7" align="center">Periode: ' . $startDate . ' s/d ' . $endDate . '</td></tr>';
        $html .= '<tr><td colspan="7"></td></tr>'; // Spasi
        
        // Header Tabel (Warna Ungu Buana Berlian)
        $html .= '<tr style="background-color: #352877; color: #ffffff; font-weight: bold; text-align: center;">
                    <th width="120">Tanggal</th>
                    <th width="80">Jam</th>
                    <th width="100">Tipe</th>
                    <th width="150">Kode Transaksi</th>
                    <th width="200">Nama Pelanggan</th>
                    <th width="250">Rute Perjalanan</th>
                    <th width="150">Harga (Rp)</th>
                  </tr>';

        $totalKeseluruhan = 0;
        foreach ($semuaTransaksi as $trx) {
            $isTravel = isset($trx->kode_booking);
            $tipe = $isTravel ? 'Travel' : 'Kargo';
            $kode = $isTravel ? $trx->kode_booking : $trx->kode_resi;
            $pelanggan = $isTravel ? $trx->nama_penumpang : $trx->nama_pengirim;
            $rute = $isTravel 
                ? explode(' (', $trx->titik_jemput)[0] . ' - ' . explode(' (', $trx->titik_antar)[0]
                : explode(' (', $trx->kota_asal)[0] . ' - ' . explode(' (', $trx->kota_tujuan)[0];
            
            $totalKeseluruhan += $trx->total_harga;

            $warnaTipe = $isTravel ? 'color: #352877; font-weight:bold;' : 'color: #d97706; font-weight:bold;';

            $html .= "<tr>
                        <td align='center'>" . Carbon::parse($trx->created_at)->format('d-m-Y') . "</td>
                        <td align='center'>" . Carbon::parse($trx->created_at)->format('H:i') . "</td>
                        <td align='center' style='{$warnaTipe}'>{$tipe}</td>
                        <td align='center' style='font-weight:bold;'>{$kode}</td>
                        <td>{$pelanggan}</td>
                        <td>{$rute}</td>
                        <td align='right'>" . number_format($trx->total_harga, 0, ',', '.') . "</td>
                      </tr>";
        }

        // Footer Total
        $html .= "<tr style='font-weight: bold; background-color: #e2e8f0;'>
                    <td colspan='6' align='right'>TOTAL PENDAPATAN</td>
                    <td align='right' style='color: #352877;'>Rp " . number_format($totalKeseluruhan, 0, ',', '.') . "</td>
                  </tr>";
        $html .= '</table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
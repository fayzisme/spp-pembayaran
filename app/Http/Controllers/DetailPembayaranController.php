<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranBulananExport;
use App\Exports\PembayaranLainnyaExport;
use App\Models\DetailPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Scopes\LulusScope;
use Dompdf\Dompdf;

class DetailPembayaranController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // public function getSiswa(Request $request)
    // {
    //     // Mengambil semua siswa termasuk yang sudah lulus (status 1)
    //     $siswa = Siswa::with(['kelas', 'tahunAjaran', 'transaksi' => function ($query) {
    //         $query->where('status', '!=', '1','0');
    //     }])
    //     ->orWhere('status', '1','0') // Menyertakan siswa yang sudah lulus (status 1)
    //     ->get();
    
    //     return response()->json(['siswa' => $siswa]);
    // }

    public function createPaymentMonthly(Request $request)
    {
       // Ambil data petugas untuk dropdown
       $petugas = Petugas::all();

       if ($request->metode_transaksi == "Online") {
           try {
               $request->validate([
                   'id_transaksi' => 'required|exists:transaksi,id_transaksi',
                   'bulan' => 'required|array|min:1',
                   'bulan.*' => 'required|string',
               ]);

               DB::beginTransaction();
               $transaksi = Pembayaran::findOrFail($request->id_transaksi);
               $totalAmount = 0;
               $bulanSelected = $request->bulan;
               $detailPembayaranList = [];

               foreach ($bulanSelected as $bulan) {

                   if (DetailPembayaran::where('id_transaksi', $transaksi->id_transaksi)->where('bulan', $bulan)->exists()) {
                       return response()->json([
                           'status' => 'error',
                           'message' => "Pembayaran bulan $bulan sudah dilakukan. Silahkan pilih bulan lain.",
                       ], 400);
                   }

                   $idDetailPembayaran = DB::table('detail_transaksi')->insertGetId([
                       'id_transaksi' => $transaksi->id_transaksi,
                       'id_siswa' => $transaksi->id_siswa,
                       'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                       'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                       // 'id_kelas' => $transaksi->id_kelas,
                       'id_tarif' => $transaksi->id_tarif,
                       'tgl_transaksi' => now(),
                       'bulan' => $bulan,
                       'jumlah_transaksi' => $transaksi->tarif->tarif,
                       'status_transaksi' => 'Pending',
                       'metode_transaksi' => 'Online',
                       'created_at' => now()
                   ]);
                   $detailPembayaran = DetailPembayaran::findOrFail($idDetailPembayaran);
                   // $detailPembayaran = DetailPembayaran::create([
                   //     'id_transaksi' => $transaksi->id_transaksi,
                   //     'id_siswa' => $transaksi->id_siswa,
                   //     'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                   //     'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                   //     'id_kelas' => $transaksi->id_kelas,
                   //     'id_tarif' => $transaksi->id_tarif,
                   //     'tgl_transaksi' => now(),
                   //     'bulan' => $bulan,
                   //     'jumlah_transaksi' => $transaksi->tarif->tarif,
                   //     'status_transaksi' => 'Pending',
                   //     'metode_transaksi' => 'online',
                   // ]);

                   $detailPembayaranList[] = $detailPembayaran;
                   $totalAmount += $transaksi->tarif->tarif;
               }

               $siswa = Siswa::findOrFail($transaksi->id_siswa);
               $namaArray = explode(' ', $siswa->nama);
               $siswa->first_name = $namaArray[0];
               $siswa->last_name = implode(' ', array_slice($namaArray, 1));

               $params = [
                   'transaction_details' => [
                       'order_id' => implode('_', array_column($detailPembayaranList, 'id_detail_transaksi')),
                       'gross_amount' => $totalAmount,
                   ],
                   'customer_details' => [
                       'first_name' => $siswa->first_name,
                       'last_name' => $siswa->last_name,
                       'email' => $siswa->user->email,
                       'phone' => $siswa->no_hp,
                   ],
               ];

               $snapToken = $this->midtrans->getSnapToken($params);

               if (!$snapToken) {
                   throw new \Exception('Error generating Snap Token. Please try again.');
               }

               foreach ($detailPembayaranList as $detailPembayaran) {
                   $detailPembayaran->update(['snap_token' => $snapToken]);
               }

               DB::commit();
               return response()->json([
                   'snap_token' => $snapToken,
               ]);
           } catch (\Exception $e) {
               DB::rollback();
               Log::error('Error creating payment: ' . $e->getMessage());

               return response()->json([
                   'status' => 'error',
                   'message' => 'Gagal membuat transaksi. Silahkan coba lagi.',
                   'data' => $e->getMessage(),
               ], 500);
           }
       } else {
           // dd($request->all());
           $request->validate([
               'id_transaksi' => 'required|exists:transaksi,id_transaksi',
               'bulan' => 'required|array|min:1',
               'bulan.*' => 'required|string',
               'id_petugas' => 'required|exists:petugas,id_petugas', // Validasi untuk id_petugas
           ]);
    
            $transaksi = Pembayaran::findOrFail($request->id_transaksi);
            // $transaksi->id_petugas = $request->id_petugas;
            // $transaksi->save();
    
            foreach ($request->bulan as $bulan) {
                $detailPembayaran = DetailPembayaran::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_siswa' => $transaksi->id_siswa,
                    'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                    'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                    'id_tarif' => $transaksi->id_tarif,
                    'tgl_transaksi' => now(),
                    'bulan' => $bulan,
                    'jumlah_transaksi' => $transaksi->tarif->tarif,
                    'status_transaksi' => 'Sukses',
                    'metode_transaksi' => 'Tunai',
                    'id_petugas' => $request->id_petugas, // Menyimpan id_petugas
                ]);
            }
    
            Alert::success('Berhasil', 'Pembayaran berhasil dilakukan.');
            return redirect()->route('transaksi.show', $transaksi->id_transaksi);
        }

    }

    public function showPaymentForm()
{
    $petugas = Petugas::all(); // Mengambil semua data 'Petugas'
    return view('pages.transaksi.show', compact('petugas'));
}


    public function createPayment(Request $request)
    {
        if ($request->metode_transaksi == "Online") {
            try {
                $request->validate([
                    'id_transaksi' => 'required|exists:transaksi,id_transaksi',
                    'jumlah_transaksi' => 'required|numeric',
                ]);

                $transaksi = Pembayaran::findOrFail($request->id_transaksi);
                
                // cek jumlah_transaksi apakah lebih dari sisa tarif
                $sisaTarif = $transaksi->tarif->tarif - $transaksi->detailPembayaran->sum('jumlah_transaksi');
                if ($request->jumlah_transaksi > $sisaTarif) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah pembayaran melebihi sisa tagihan.',
                    ], 400);
                }

                DB::beginTransaction();
                $idDetailPembayaran = DB::table('detail_transaksi')->insertGetId([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_siswa' => $transaksi->id_siswa,
                    'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                    'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                    // 'id_kelas' => $transaksi->id_kelas,
                    'id_tarif' => $transaksi->id_tarif,
                    'tgl_transaksi' => now(),
                    'bulan' => null,
                    'jumlah_transaksi' => $request->jumlah_transaksi,
                    'status_transaksi' => 'Pending',
                    'metode_transaksi' => 'Online',
                    'created_at' => now()
                ]);
                $detailPembayaran = DetailPembayaran::findOrFail($idDetailPembayaran);

                // $detailPembayaran = DetailPembayaran::create([
                //     'id_transaksi' => $transaksi->id_transaksi,
                //     'id_siswa' => $transaksi->id_siswa,
                //     'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                //     'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                //     // 'id_kelas' => $transaksi->id_kelas,
                //     'id_tarif' => $transaksi->id_tarif,
                //     'tgl_transaksi' => now(),
                //     'bulan' => null,
                //     'jumlah_transaksi' => $request->jumlah_transaksi,
                //     'status_transaksi' => 'Pending',
                //     'metode_transaksi' => 'online',
                // ]);


                $siswa = Siswa::findOrFail($transaksi->id_siswa);
                $namaArray = explode(' ', $siswa->nama);
                $siswa->first_name = $namaArray[0];
                $siswa->last_name = implode(' ', array_slice($namaArray, 1));

                $params = [
                    'transaction_details' => [
                        'order_id' => $detailPembayaran->id_detail_transaksi . '_BEBAS',
                        'gross_amount' => $request->jumlah_transaksi,
                    ],
                    'customer_details' => [
                        'first_name' => $siswa->first_name,
                        'last_name' => $siswa->last_name,
                        'email' => $siswa->user->email,
                        'phone' => $siswa->no_hp,
                    ],
                ];

                $snapToken = $this->midtrans->getSnapToken($params);

                $detailPembayaran->update(['snap_token' => $snapToken]);
                DB::commit();
                return response()->json([
                    'snap_token' => $snapToken,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating payment: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat transaksi. Silahkan coba lagi.',
                    'data' => $e->getMessage(),
                ], 500);
            }
        } else {
            $request->validate([
                'id_transaksi' => 'required|exists:transaksi,id_transaksi',
                'jumlah_transaksi' => 'required|numeric',
                'id_petugas' => 'required|exists:petugas,id_petugas', // Validasi untuk id_petugas
            ]);

            $transaksi = Pembayaran::findOrFail($request->id_transaksi);

            $detailPembayaran = DetailPembayaran::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_siswa' => $transaksi->id_siswa,
                'id_jenis_pembayaran' => $transaksi->id_jenis_pembayaran,
                'id_thn_ajaran' => $transaksi->id_thn_ajaran,
                'id_kelas' => $transaksi->id_kelas,
                'id_tarif' => $transaksi->id_tarif,
                'tgl_transaksi' => now(),
                'bulan' => null,
                'jumlah_transaksi' => $request->jumlah_transaksi,
                'status_transaksi' => 'Sukses',
                'metode_transaksi' => 'Tunai',
                'id_petugas' => $request->id_petugas, // Menyimpan id_petugas
            ]);

            Alert::success('Berhasil', 'Pembayaran berhasil dilakukan.');
            return redirect()->route('transaksi.showLain', $transaksi->id_transaksi);
        }

    }

    public function repayMonthly(Request $request)
    {
        try {
        $request->validate([
                'id_detail_transaksi' => 'required|exists:detail_transaksi,id_detail_transaksi',
            ]);

            $detailPembayaran = DetailPembayaran::findOrFail($request->id_detail_transaksi);

            $transaksi = Pembayaran::findOrFail($detailPembayaran->id_transaksi);

            $siswa = Siswa::findOrFail($transaksi->id_siswa);
            $namaArray = explode(' ', $siswa->nama);
            $siswa->first_name = $namaArray[0];
            $siswa->last_name = implode(' ', array_slice($namaArray, 1));

            $snapToken = $detailPembayaran->snap_token;

            return response()->json([
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating payment: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi. Silahkan coba lagi.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
    public function repay(Request $request)
    {
        try {
            $request->validate([
                'id_detail_transaksi' => 'required|exists:detail_transaksi,id_detail_transaksi',
            ]);

            $detailPembayaran = DetailPembayaran::findOrFail($request->id_detail_transaksi);

            $transaksi = Pembayaran::findOrFail($detailPembayaran->id_transaksi);

            $siswa = Siswa::findOrFail($transaksi->id_siswa);
            $namaArray = explode(' ', $siswa->nama);
            $siswa->first_name = $namaArray[0];
            $siswa->last_name = implode(' ', array_slice($namaArray, 1));

            $snapToken = $detailPembayaran->snap_token;

            return response()->json([
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating payment: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat transaksi. Silahkan coba lagi.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        try {
            $transactionStatus = $request->transaction_status;
            $orderId = $request->order_id;
            $orderIds = explode('_', $orderId);
            if (end($orderIds) == 'BEBAS') {
                array_pop($orderIds);
            }

            foreach ($orderIds as $id) {
                $detailPembayaran = DetailPembayaran::findOrFail($id);

                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $detailPembayaran->status_transaksi = 'Sukses';
                } elseif ($transactionStatus == 'pending') {
                    $detailPembayaran->status_transaksi = 'Pending';
                } else {
                    $detailPembayaran->status_transaksi = 'Gagal';
                }

                $detailPembayaran->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil diproses.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing notification: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses notifikasi. Silahkan coba lagi.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateInvoice($id)
    {
        $detailPembayaran = DetailPembayaran::with('siswa', 'transaksi')->findOrFail($id);
        
        $namaFile = $detailPembayaran->transaksi->invoice . '-' . $detailPembayaran->created_at->format('YmdHis') . '.pdf';
        $pdf = Pdf::loadView('invoices.invoice', compact('detailPembayaran'));
        return $pdf->stream($namaFile);
        
    }

    public function exportMonthly($id)
    {
        $transaksi = Pembayaran::findOrFail($id);

        $namaFile = 'transaksi_bulanan_' . strtolower($transaksi->jenisPembayaran->nama_pembayaran) . '_' . $transaksi->siswa->nis. '_' . strtolower($transaksi->invoice) . '.xlsx';
        return Excel::download(new PembayaranBulananExport($id), $namaFile);
    }

    public function export($id)
    {
        $transaksi = Pembayaran::findOrFail($id);

        $namaFile = 'transaksi_lain_' . strtolower($transaksi->jenisPembayaran->nama_pembayaran) . '_' . $transaksi->siswa->nis. '_' . strtolower($transaksi->invoice) . '.xlsx';
        return Excel::download(new PembayaranLainnyaExport($id), $namaFile);
    }

    public function exportBulanan($id)
    {
        $pembayaran = Pembayaran::with([
            'tahunAjaran',
            'kelas',
            'siswa',
            'jenisPembayaran' => function ($query) {
                $query->where('tipe_bayar', 'Bulanan');
            },
            'detailPembayaran'
        ])
        ->where('id_transaksi', $id)
        ->get()
        ->map(function ($item) {
            return $item->detailPembayaran->map(function ($detail) use ($item) {
                return [
                    'NIS' => $item->siswa->nis,
                    'Nama Siswa' => $item->siswa->nama,
                    'Kelas' => $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas,
                    'Tahun Ajaran' => $item->tahunAjaran->thn_ajaran,
                    'Semester' => $item->tahunAjaran->semester,
                    'Jenis Pembayaran' => $item->jenisPembayaran->nama_pembayaran,
                    'Bulan' => $detail->bulan,
                    'Dibayar' => $detail->jumlah_transaksi ? 'Rp. ' . number_format($detail->jumlah_transaksi, 0, ',', '.') : 'Rp. 0',
                    'Status Bayar' => $detail->status_transaksi,
                    'Metode Transaksi' => $detail->metode_transaksi,
                    'Tanggal' => $detail->tgl_transaksi, // Ensure this key is present
                    'Nama Petugas' => $detail->metode_transaksi == 'Tunai' ? ($detail->petugas ? $detail->petugas->nama : 'Tidak ada petugas') : 'Tidak ada petugas',
    ];
            });
        })->collapse();

        $pdf = PDF::loadView('eksporpdf.pembayaran_bulanan', ['pembayaran' => $pembayaran]);

        return $pdf->stream('Riwayat Pembayaran Bulanan.pdf');
    }
    public function exportPdf($id)
    {
        $details = DetailPembayaran::withoutGlobalScope(LulusScope::class);
        // Ambil data transaksi dengan relasi yang diperlukan
        $transaksi = Pembayaran::with([
            'siswa',
            'tahunAjaran',
            'kelas',
            'jenisPembayaran' => function ($query) {
                $query->where('tipe_bayar', 'Bebas');
            },
            'detailPembayaran.petugas' // Include petugas relationship
        ])->findOrFail($id);

        // Log data siswa untuk debugging
        Log::info('Data Siswa:', ['siswa' => $transaksi->siswa]);
        
        // Proses detail pembayaran untuk PDF
        $pembayaran = collect($transaksi->detailPembayaran)->map(function ($detail) use ($transaksi) {
            return [
                'NIS' => $transaksi->siswa->nis ?? 'N/A',
                'Nama Siswa' => $transaksi->siswa->nama ?? 'N/A',
                'Kelas' => isset($transaksi->kelas) ? ($transaksi->kelas->tingkat . ' ' . $transaksi->kelas->nama_kelas) : 'N/A',
                'Tahun Ajaran' => $transaksi->tahunAjaran->thn_ajaran ?? 'N/A',
                'Semester' => $transaksi->tahunAjaran->semester ?? 'N/A',
                'Jenis Pembayaran' => $transaksi->jenisPembayaran->nama_pembayaran ?? 'N/A',
                'Dibayar' => $detail->jumlah_transaksi ? 'Rp. ' . number_format($detail->jumlah_transaksi, 0, ',', '.') : 'Rp. 0',
                'Status Bayar' => $detail->status_transaksi ?? 'N/A',
                'Metode Transaksi' => $detail->metode_transaksi ?? 'N/A',
                'Tanggal' => $detail->tgl_transaksi ? \Carbon\Carbon::parse($detail->tgl_transaksi)->format('d-m-Y') : 'N/A',
                'Nama Petugas' => $detail->metode_transaksi == 'Tunai' ? ($detail->petugas->nama ?? 'Tidak ada petugas') : '--',
            ];
        });
    
        // Buat PDF dengan view yang sesuai
        $pdf = PDF::loadView('eksporpdf.pembayaran_bebas', ['pembayaran' => $pembayaran])
                  ->setPaper('a4', 'landscape');
        
        // Stream PDF ke browser
        return $pdf->stream('Riwayat_Pembayaran_Bebas.pdf');
    }
    
     
    


}

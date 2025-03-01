<?php

namespace App\Http\Controllers;

use App\Models\Spt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;


class SptController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $user = Auth::user();
        
        // Karyawan hanya bisa melihat SPT miliknya
        if ($user->hasRole('karyawan')) {
            $query = Spt::with('users')
                      ->whereHas('users', function($q) use ($user) {
                          $q->where('users.id', $user->id);
                      });
        } else {
            // Operator, Admin, dan Atasan bisa melihat semua
            $query = Spt::with('users');
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_anggota', function($row) {
                return $row->users->pluck('nama')->implode(', ');
            })
            ->addColumn('action', function($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<a href="'.route('spts.show', $row->id).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                
                // Karyawan hanya bisa edit jika status Dikembalikan
                if (Auth::user()->hasRole('karyawan') && $row->status == 'Dikembalikan') {
                    $btn .= '<a href="'.route('spts.edit', $row->id).'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                }
                
                // Operator hanya bisa verifikasi jika status Menunggu Verifikasi
                if (Auth::user()->hasRole('operator') && $row->status == 'Menunggu Verifikasi') {
                    $btn .= '<a href="'.route('spts.verify', $row->id).'" class="btn btn-sm btn-warning">Verifikasi</a>';
                }
                
                // Atasan hanya bisa approve jika status Diverifikasi Oleh Operator
                if (Auth::user()->hasRole('atasan') && $row->status == 'Diverifikasi Oleh Operator') {
                    $btn .= '<a href="'.route('spts.approve', $row->id).'" class="btn btn-sm btn-success">Setujui</a>';
                }
                
                // Tombol cetak hanya muncul jika status Selesai
                if ($row->status == 'Selesai') {
                    $btn .= '<a href="'.route('spts.print', $row->id).'" class="btn btn-sm btn-secondary" target="_blank">Cetak</a>';
                }
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    return view('spts.index');
}

    public function create()
    {
        $users = User::role('karyawan')->get();
        return view('spts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1',
            'users.*.id' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dasar' => 'required',
            'tujuan' => 'required',
        ]);

        $spt = new Spt();
        $spt->nomor_surat = Spt::generateNomorSurat();
        $spt->tanggal_pengajuan = now();
        $spt->dasar = $request->dasar;
        $spt->user_id = Auth::id(); // ID pembuat SPT
        $spt->tanggal_mulai = $request->tanggal_mulai;
        $spt->tanggal_selesai = $request->tanggal_selesai;
        $spt->tujuan = $request->tujuan;
        $spt->status = 'Menunggu Verifikasi';
        $spt->save();

        // Attach users
        $userIds = collect($request->users)->pluck('id')->toArray();
        $spt->users()->attach($userIds);

        return redirect()->route('spts.index')->with('success', 'SPT berhasil diajukan dan menunggu verifikasi.');
    }

    public function show(Spt $spt)
    {
        // Cek apakah user adalah pemilik SPT, anggota SPT, atau memiliki role yang bisa melihat semua
        $userCanView = Auth::user()->hasRole(['admin', 'operator', 'atasan']) ||
            $spt->user_id == Auth::id() ||
            $spt->users->contains(Auth::id());

        if (!$userCanView) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load relasi yang dibutuhkan
        $spt->load(['users', 'creator']);

        // Data untuk status badge
        $statusBadgeClass = [
            'Menunggu Verifikasi' => 'bg-warning',
            'Diverifikasi Oleh Operator' => 'bg-info',
            'Dikembalikan' => 'bg-danger',
            'Selesai' => 'bg-success'
        ];

        return view('spts.show', compact('spt', 'statusBadgeClass'));
    }

    public function verify(Spt $spt)
    {
        // Cek apakah user memiliki role operator
        if (!Auth::user()->hasRole('operator')) {
            abort(403, 'Unauthorized action.');
        }

        return view('spts.verify', compact('spt'));
    }

    public function verifyUpdate(Request $request, Spt $spt)
    {
        // Cek apakah user memiliki role operator
        if (!Auth::user()->hasRole('operator')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:Diverifikasi Oleh Operator,Dikembalikan',
            'catatan' => 'required_if:status,Dikembalikan',
        ]);

        $spt->status = $request->status;
        $spt->catatan = $request->catatan;
        $spt->save();

        return redirect()->route('spts.index')->with('success', 'SPT berhasil diverifikasi.');
    }

    public function approve(Spt $spt)
    {
        // Cek apakah user memiliki role atasan
        if (!Auth::user()->hasRole('atasan')) {
            abort(403, 'Unauthorized action.');
        }

        return view('spts.approve', compact('spt'));
    }

    public function approveUpdate(Request $request, Spt $spt)
    {
        // Cek apakah user memiliki role atasan
        if (!Auth::user()->hasRole('atasan')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:Selesai,Dikembalikan',
            'catatan' => 'required_if:status,Dikembalikan',
        ]);

        $spt->status = $request->status;
        $spt->catatan = $request->catatan;
        $spt->save();

        return redirect()->route('spts.index')->with('success', 'SPT berhasil diproses.');
    }

    public function print(Spt $spt)
    {
        // Cek apakah user adalah pemilik SPT atau memiliki role yang bisa melihat semua
        if (Auth::user()->hasRole('karyawan') && Auth::id() != $spt->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah status SPT adalah Selesai
        if ($spt->status != 'Selesai') {
            return redirect()->route('spts.index')->with('error', 'SPT tidak dapat dicetak karena status belum Selesai.');
        }

        // Bersihkan nomor surat untuk nama file
        $filename = 'SPT-' . str_replace(['/', '\\'], '-', $spt->nomor_surat) . '.pdf';

        $pdf = Pdf::loadView('spts.print', compact('spt'));
        return $pdf->stream($filename);
    }

    // API untuk mendapatkan NIP berdasarkan nama
    public function getNip(Request $request)
    {
        $user = User::where('nama', $request->nama)->first();

        if ($user) {
            return response()->json(['nip' => $user->nip]);
        }

        return response()->json(['nip' => '']);
    }
}

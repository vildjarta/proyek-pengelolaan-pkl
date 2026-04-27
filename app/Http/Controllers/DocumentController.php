<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display document management dashboard
     */
    public function index()
    {
        // Get all templates from each category
        $proposalTemplates = $this->getTemplatesFromFolder('proposal');
        $undanganTemplates = $this->getTemplatesFromFolder('undangan');
        $laporanTemplates = $this->getTemplatesFromFolder('laporan-pkl');

        // Count templates
        $proposalCount = count($proposalTemplates);
        $undanganCount = count($undanganTemplates);
        $laporanCount = count($laporanTemplates);

        return view('documents.index', compact(
            'proposalTemplates',
            'undanganTemplates',
            'laporanTemplates',
            'proposalCount',
            'undanganCount',
            'laporanCount'
        ));
    }

    /**
     * Helper: Get templates from folder
     */
    private function getTemplatesFromFolder($folder)
    {
        $path = public_path("documents/templates/{$folder}");
        $templates = [];

        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $displayName = $this->getDisplayName($file);
                    $templates[$file] = [
                        'name' => $displayName,
                        'type' => $extension,
                        'size' => filesize($path . '/' . $file)
                    ];
                }
            }
        }

        return $templates;
    }

    /**
     * Helper: Get display name for file
     */
    private function getDisplayName($filename)
    {
        $displayName = str_replace(['-', '_'], ' ', $filename);
        $displayName = str_replace('.docx', '', $displayName);
        $displayName = str_replace('.pdf', '', $displayName);
        return ucwords($displayName);
    }

    // ==================== PROPOSAL ====================

    /**
     * Display proposal templates
     */
    public function proposalIndex()
    {
        $templates = [
            'template-proposal.docx' => 'Template Proposal PKL'
        ];

        return view('documents.proposal.index', compact('templates'));
    }

    /**
     * Download proposal template
     */
    public function downloadProposalTemplate($filename)
    {
        $path = "documents/templates/proposal/{$filename}";

        // Cek file di public folder langsung
        $fullPath = public_path($path);
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'Template tidak ditemukan: ' . $filename);
        }

        return response()->download($fullPath);
    }

    /**
     * Upload proposal document
     */
    public function uploadProposal(Request $request)
    {
        $request->validate([
            'proposal_file' => 'required|mimes:docx|max:10240',
            'nim' => 'required|string',
            'judul' => 'required|string'
        ]);

        $file = $request->file('proposal_file');
        $nim = $request->nim;
        $judul = $request->judul;

        $filename = 'proposal_' . $nim . '_' . time() . '.docx';
        $path = "documents/uploads/proposal/{$filename}";

        Storage::disk('public')->put($path, file_get_contents($file));

        return redirect()->back()->with('success', 'Proposal berhasil diupload');
    }

    // ==================== UNDANGAN ====================

    /**
     * Display undangan templates
     */
    public function undanganIndex()
    {
        $templates = [
            'undangan-seminar.docx' => 'Undangan Seminar Proposal'
        ];

        return view('documents.undangan.index', compact('templates'));
    }

    /**
     * Download undangan template
     */
    public function downloadUndanganTemplate($filename)
    {
        $path = "documents/templates/undangan/{$filename}";

        // Cek file di public folder langsung
        $fullPath = public_path($path);
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'Template tidak ditemukan: ' . $filename);
        }

        return response()->download($fullPath);
    }

    /**
     * Upload undangan document
     */
    public function uploadUndangan(Request $request)
    {
        $request->validate([
            'undangan_file' => 'required|mimes:docx|max:10240',
            'jenis' => 'required|string',
            'nama' => 'required|string'
        ]);

        $file = $request->file('undangan_file');
        $jenis = $request->jenis;
        $nama = $request->nama;

        $filename = 'undangan_' . $jenis . '_' . $nama . '_' . time() . '.docx';
        $path = "documents/uploads/undangan/{$filename}";

        Storage::disk('public')->put($path, file_get_contents($file));

        return redirect()->back()->with('success', 'Undangan berhasil diupload');
    }

    // ==================== LAPORAN PKL ====================

    /**
     * Display laporan PKL templates
     */
    public function laporanIndex()
    {
        $templates = [
            'pedoman-laporan.pdf' => 'Pedoman Penulisan Laporan PKL (PDF)'
        ];

        return view('documents.laporan.index', compact('templates'));
    }

    /**
     * Download laporan template
     */
    public function downloadLaporanTemplate($filename)
    {
        $path = "documents/templates/laporan-pkl/{$filename}";

        // Cek file di public folder langsung
        $fullPath = public_path($path);
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'Template tidak ditemukan: ' . $filename);
        }

        return response()->download($fullPath);
    }

    /**
     * Upload laporan PKL
     */
    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'laporan_file' => 'required|mimes:docx|max:20480',
            'nim' => 'required|string',
            'judul_laporan' => 'required|string'
        ]);

        $file = $request->file('laporan_file');
        $nim = $request->nim;
        $judul = $request->judul_laporan;

        $filename = 'laporan_' . $nim . '_' . time() . '.docx';
        $path = "documents/uploads/laporan/{$filename}";

        Storage::disk('public')->put($path, file_get_contents($file));

        return redirect()->back()->with('success', 'Laporan PKL berhasil diupload');
    }

    /**
     * List uploaded documents
     */
    public function listUploads($type = null)
    {
        $path = "documents/uploads";
        if ($type) {
            $path .= "/{$type}";
        }

        $files = Storage::disk('public')->files($path);

        return view('documents.uploads', compact('files', 'type'));
    }
}

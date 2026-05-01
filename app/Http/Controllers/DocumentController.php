<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display document management dashboard
     */
    public function index()
    {
        // Check if user has permission (koordinator or staff)
        if (!Auth::check() || !in_array(Auth::user()->role, ['koordinator', 'staff'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        // Get document statistics
        $documents = $this->getDocumentsList();

        return view('documents.index', compact('documents'));
    }

    /**
     * Get documents list from storage
     */
    private function getDocumentsList()
    {
        $documents = [];
        $base_path = public_path('documents/templates');

        // Check each category
        $categories = [
            'proposal' => 'Proposal PKL',
            'undangan' => 'Undangan',
            'laporan-pkl' => 'Laporan PKL'
        ];

        foreach ($categories as $folder => $label) {
            $path = $base_path . '/' . $folder;
            if (is_dir($path)) {
                $files = scandir($path);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $documents[] = [
                            'filename' => $file,
                            'display_name' => $this->getDisplayName($file),
                            'category' => $folder,
                            'label' => $label,
                            'path' => 'documents/templates/' . $folder . '/' . $file,
                            'size' => filesize($path . '/' . $file),
                            'type' => pathinfo($file, PATHINFO_EXTENSION)
                        ];
                    }
                }
            }
        }

        return $documents;
    }

    /**
     * Download document
     */
    public function download($category, $filename)
    {
        // Check permission
        if (!Auth::check() || !in_array(Auth::user()->role, ['koordinator', 'staff'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }

        $filePath = public_path('documents/templates/' . $category . '/' . $filename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($filePath);
    }

    /**
     * Upload document
     */
    public function upload(Request $request)
    {
        // Check permission
        if (!Auth::check() || !in_array(Auth::user()->role, ['koordinator', 'staff'])) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan tindakan ini');
        }

        $request->validate([
            'category' => 'required|in:proposal,undangan,laporan-pkl',
            'document' => 'required|file|mimes:docx,doc,pdf|max:10240'
        ]);

        $category = $request->category;
        $file = $request->file('document');

        $filename = time() . '_' . $file->getClientOriginalName();
        $path = public_path('documents/templates/' . $category);

        // Create directory if not exists
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $file->move($path, $filename);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload');
    }

    /**
     * Delete document
     */
    public function delete($category, $filename)
    {
        // Check permission
        if (!Auth::check() || !in_array(Auth::user()->role, ['koordinator', 'staff'])) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan tindakan ini');
        }

        $filePath = public_path('documents/templates/' . $category . '/' . $filename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        if (unlink($filePath)) {
            return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus dokumen');
        }
    }

    /**
     * Proposal page (download only)
     */
    public function proposal()
    {
        $documents = $this->getDocumentsByCategory('proposal');
        return view('documents.proposal.index', compact('documents'));
    }

    /**
     * Undangan page (download only)
     */
    public function undangan()
    {
        $documents = $this->getDocumentsByCategory('undangan');
        return view('documents.undangan.index', compact('documents'));
    }

    /**
     * Laporan page (download only)
     */
    public function laporan()
    {
        $documents = $this->getDocumentsByCategory('laporan-pkl');
        return view('documents.laporan.index', compact('documents'));
    }

    /**
     * Get documents by category
     */
    private function getDocumentsByCategory($category)
    {
        $documents = [];
        $path = public_path('documents/templates/' . $category);

        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $documents[$file] = $this->getDisplayName($file);
                }
            }
        }

        return $documents;
    }

    /**
     * Get display name for file
     */
    private function getDisplayName($filename)
    {
        // Hapus unix timestamp dari nama file (format: 10 angka diikuti underscore)
        $displayName = preg_replace('/^\d{10}_/', '', $filename);
        
        // Hapus ekstensi
        $displayName = str_replace(['.docx', '.doc', '.pdf'], '', $displayName);
        
        // Ganti underscore dan strip dengan spasi
        $displayName = str_replace(['-', '_'], ' ', $displayName);
        
        return ucwords(trim($displayName));
    }
}

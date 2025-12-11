<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Rating & Review - PKL JOZZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS Global Layout -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-pkl.css') }}">
    <!-- CSS Halaman Ini -->
    <link rel="stylesheet" href="{{ asset('assets/css/lihatratingdanreview.css') }}">
</head>
<body>
    @include('layout.header')
    @include('layout.sidebar')

    <div class="main-content-wrapper">
        <div class="content container-fluid">

            <!-- Tombol Kembali -->
            <a href="{{ route('ratingperusahaan') }}" class="btn-back" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="table-header">
                <h2 class="title">Rating & Review Perusahaan</h2>
            </div>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-custom">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if($errors->any())
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @php
                use Illuminate\Support\Facades\Auth;
                use Illuminate\Support\Facades\Storage;
                use App\Models\Mahasiswa;

                $authMahasiswa = null;
                $isKoordinator = false;

                if (Auth::check()) {
                    $user = Auth::user();
                    $isKoordinator = isset($user->role) && $user->role === 'koordinator';
                    try {
                        $authMahasiswa = Mahasiswa::where('email', $user->email)->first();
                    } catch (\Throwable $e) {
                        $authMahasiswa = null;
                    }
                }

                /**
                 * Helper kecil (di-blade) untuk resolve foto:
                 * - jika $path mulai dengan "data:" maka treat as data URL
                 * - jika full http(s) maka gunakan langsung
                 * - jika file path relatif ke disk public, cek Storage::disk('public')->exists()
                 */
                function resolveAvatarUrl($path) {
                    if (!$path) return null;
                    // already data URL
                    if (preg_match('/^data:/i', $path)) {
                        return $path;
                    }
                    // absolute URL (http/https or protocol-relative)
                    if (preg_match('/^(https?:)?\\/\\//i', $path)) {
                        return $path;
                    }
                    // treat as storage path relative to disk 'public'
                    try {
                        if (Storage::disk('public')->exists(ltrim($path, '/'))) {
                            return asset('storage/' . ltrim($path, '/'));
                        }
                    } catch (\Throwable $e) {
                        // jika storage tidak bisa diakses, fallback null
                    }
                    return null;
                }
            @endphp

            {{-- LIST REVIEW --}}
            <div class="review-list">
                @forelse ($reviews as $review)
                    @php
                        $nama = $review->nama_mahasiswa ?? 'Mahasiswa';
                        $inisial = strtoupper(substr($nama, 0, 1));
                        $namaTersembunyi = substr($nama, 0, 2) . str_repeat('*', max(3, strlen($nama) - 2));

                        $reviewMahasiswaId = $review->id_mahasiswa ?? null;

                        $isOwner = false;
                        if ($authMahasiswa && $reviewMahasiswaId) {
                            $isOwner = ((int)$authMahasiswa->id_mahasiswa === (int)$reviewMahasiswaId);
                        }

                        // 1) periksa relation mahasiswa->avatar, user->avatar, dan accessor avatar_url
                        $fotoPath = null;
                        $fotoUrl = null;

                        // DEBUG: log info
                        \Log::info('DEBUG Review Avatar', [
                            'review_id' => $review->id_review,
                            'id_mahasiswa' => $review->id_mahasiswa,
                            'mahasiswa_exists' => isset($review->mahasiswa) ? 'yes' : 'no',
                            'mahasiswa_is_null' => $review->mahasiswa ? 'no' : 'yes',
                            'mahasiswa_avatar' => $review->mahasiswa?->avatar ?? 'null',
                            'mahasiswa_email' => $review->mahasiswa?->email ?? 'null',
                        ]);

                        if (isset($review->mahasiswa) && $review->mahasiswa) {
                            // priority: Mahasiswa->avatar (stored path)
                            if (!empty($review->mahasiswa->avatar)) {
                                $fotoPath = $review->mahasiswa->avatar;
                                \Log::info('Using mahasiswa avatar: ' . $fotoPath);
                            }

                            // jika model menyediakan accessor full URL (avatar_url), gunakan langsung
                            if (empty($fotoUrl) && !empty($review->mahasiswa->avatar_url)) {
                                // Jangan pakai default avatar (accessor mengembalikan default jika avatar kosong)
                                $defaultAvatarUrl = asset('storage/avatars/default.png');
                                if ($review->mahasiswa->avatar_url !== $defaultAvatarUrl) {
                                    $fotoUrl = $review->mahasiswa->avatar_url;
                                    \Log::info('Using mahasiswa avatar_url: ' . $fotoUrl);
                                } else {
                                    \Log::info('Mahasiswa avatar_url is default, skip using it.');
                                }
                            }

                            // coba relasi user (jika user_id ter-isi)
                            if (empty($fotoPath) && isset($review->mahasiswa->user) && $review->mahasiswa->user && !empty($review->mahasiswa->user->avatar)) {
                                $fotoPath = $review->mahasiswa->user->avatar;
                                \Log::info('Using user (via relation) avatar: ' . $fotoPath);
                            }

                            // fallback: cari user via email jika user_id null
                            if (empty($fotoPath) && !empty($review->mahasiswa->email)) {
                                try {
                                    $u = \App\Models\User::where('email', $review->mahasiswa->email)->first();
                                    if ($u && !empty($u->avatar)) {
                                        $fotoPath = $u->avatar;
                                        \Log::info('Using user (via email) avatar: ' . $fotoPath . ' for email: ' . $review->mahasiswa->email);
                                    } else {
                                        \Log::info('User via email not found or no avatar for: ' . $review->mahasiswa->email);
                                    }
                                } catch (\Throwable $e) {
                                    \Log::error('Error finding user by email: ' . $e->getMessage());
                                }
                            }
                        } else {
                            \Log::warning('Mahasiswa relation not loaded for review: ' . $review->id_review);
                        }

                        // 2) jika ada field foto_mahasiswa di review (snapshot saat review dibuat)
                        if (empty($fotoPath) && empty($fotoUrl) && !empty($review->foto_mahasiswa)) {
                            $fotoPath = $review->foto_mahasiswa;
                            \Log::info('Using review foto_mahasiswa: ' . $fotoPath);
                        }

                        // 3) fallback: cari di tabel Mahasiswa jika kita punya id (dengan eager load user + cek email)
                        if (empty($fotoPath) && empty($fotoUrl) && $reviewMahasiswaId) {
                            try {
                                $m = Mahasiswa::with('user')->find($reviewMahasiswaId);
                                if ($m) {
                                    if (!empty($m->avatar)) {
                                        $fotoPath = $m->avatar;
                                        \Log::info('Found avatar via Mahasiswa::find: ' . $fotoPath);
                                    } elseif (!empty($m->avatar_url)) {
                                        $defaultAvatarUrl = asset('storage/avatars/default.png');
                                        if ($m->avatar_url !== $defaultAvatarUrl) {
                                            $fotoUrl = $m->avatar_url;
                                            \Log::info('Found avatar_url via Mahasiswa::find: ' . $fotoUrl);
                                        } else {
                                            \Log::info('Mahasiswa::find avatar_url is default, skip using it.');
                                        }
                                    } elseif (isset($m->user) && $m->user && !empty($m->user->avatar)) {
                                        $fotoPath = $m->user->avatar;
                                        \Log::info('Found user avatar via Mahasiswa::find: ' . $fotoPath);
                                    } elseif (!empty($m->email)) {
                                        // cari via email
                                        $u = \App\Models\User::where('email', $m->email)->first();
                                        if ($u && !empty($u->avatar)) {
                                            $fotoPath = $u->avatar;
                                            \Log::info('Found user avatar via email (Mahasiswa::find): ' . $fotoPath);
                                        }
                                    }
                                }
                            } catch (\Throwable $e) {
                                \Log::error('Error in Mahasiswa::find fallback: ' . $e->getMessage());
                            }
                        }

                        // 4) resolve menjadi URL final (atau null jika tidak ada / tidak ditemukan)
                        if (empty($fotoUrl)) {
                            $fotoUrl = resolveAvatarUrl($fotoPath);
                        }
                        \Log::info('Final resolved fotoUrl for review', ['review_id' => $review->id_review, 'fotoUrl' => $fotoUrl]);
                    @endphp

                    <div class="review-card card mb-3 p-3" style="max-width:700px;">
                        <div class="review-header d-flex align-items-center">
                            {{-- Avatar --}}
                            @if(!empty($fotoUrl))
                                <div class="review-avatar rounded-circle me-3" style="width:48px;height:48px;overflow:hidden;flex:0 0 48px;">
                                    {{-- jika fotoUrl adalah data URL atau http(s) atau asset storage --}}
                                    <img src="{{ $fotoUrl }}"
                                         alt="Foto {{ $nama }}"
                                         onerror="(function(img){ img.style.display='none'; var s=img.parentElement.querySelector('.initial'); if(s) s.style.display='inline-block'; })(this);"
                                         style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
                                    <span class="initial" style="display:none;color:#fff;font-weight:700;">{{ $inisial }}</span>
                                </div>
                            @else
                                <div class="review-avatar rounded-circle me-3" style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex:0 0 48px;">
                                    <span class="initial" style="color:#fff;font-weight:700;">{{ $inisial }}</span>
                                </div>
                            @endif

                            <div class="reviewer-info">
                                <span class="reviewer-name fw-semibold">{{ $namaTersembunyi }}</span>
                                <div class="review-rating mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="review-text mt-3">
                            {{ $review->review }}
                        </div>

                        <div class="review-footer d-flex justify-content-between align-items-center mt-3">
                            <span class="review-date text-muted">
                                <i class="fa-regular fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($review->tanggal_review)->translatedFormat('d F Y') }}
                            </span>

                            <div class="action-buttons">
                                {{-- EDIT hanya untuk pemilik review --}}
                                @if($isOwner)
                                    <a href="{{ route('ratingdanreview.edit', $review->id_review) }}"
                                       class="btn-edit btn btn-sm me-2"
                                       title="Edit Review">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                @endif

                                {{-- HAPUS untuk pemilik ATAU koordinator --}}
                                @if($isOwner || $isKoordinator)
                                    <form action="{{ route('ratingdanreview.destroy', $review->id_review) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin menghapus review ini?')"
                                          style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-delete btn btn-sm"
                                                title="Hapus Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center mt-4">
                        Belum ada rating & review untuk perusahaan ini.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

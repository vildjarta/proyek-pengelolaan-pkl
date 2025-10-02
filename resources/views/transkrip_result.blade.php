<div class="d-flex">
    {{-- header --}}
    @include('layout.header')
</div>

<div class="d-flex">
    {{-- sidebar --}}
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper">
    <h2>Hasil Analisa Tersimpan</h2>
    <p class="info-text">Berikut adalah daftar hasil analisa kelayakan PKL yang telah tersimpan dalam sistem.</p>

    <div class="result-card">
        @if(count($data) > 0)
            <div class="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>IPK</th>
                            <th>SKS D</th>
                            <th>Ada E</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td data-label="Nama">{{ $row->nama_mahasiswa }}</td>
                            <td data-label="NIM">{{ $row->nim }}</td>
                            <td data-label="IPK">{{ number_format($row->ipk, 2) }}</td>
                            <td data-label="SKS D">{{ $row->total_sks_d }}</td>
                            <td data-label="Ada E">{{ $row->has_e ? 'Ya' : 'Tidak' }}</td>
                            <td data-label="Status">
                                @if($row->eligible)
                                    <span class="status-badge status-layak">
                                        <i class="fas fa-check-circle"></i> Layak
                                    </span>
                                @else
                                    <span class="status-badge status-tidak-layak">
                                        <i class="fas fa-times-circle"></i> Tidak Layak
                                    </span>
                                @endif
                            </td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data hasil analisa tersimpan.</p>
            </div>
        @endif
    </div>
</div>

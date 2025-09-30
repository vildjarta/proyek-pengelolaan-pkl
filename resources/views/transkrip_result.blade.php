<h2>Hasil Analisa Tersimpan</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>IPK</th>
        <th>SKS D</th>
        <th>Ada E</th>
        <th>Status</th>
        <th>Tanggal</th>
    </tr>
    @foreach($data as $row)
    <tr>
        <td>{{ $row->nama_mahasiswa }}</td>
        <td>{{ $row->nim }}</td>
        <td>{{ $row->ipk }}</td>
        <td>{{ $row->total_sks_d }}</td>
        <td>{{ $row->has_e ? 'Ya' : 'Tidak' }}</td>
        <td>{!! $row->eligible ? '<span style="color:green">Layak</span>' : '<span style="color:red">Tidak Layak</span>' !!}</td>
        <td>{{ $row->created_at }}</td>
    </tr>
    @endforeach
</table>

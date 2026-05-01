@extends('layout.header')

@section('title', 'VMTS - D-3 Teknologi Informasi Politala')

<!-- Bootstrap 5 CSS for styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    .vmts-header {
        background: linear-gradient(135deg, #0f2e5a, #1a4a8c);
        color: white;
        border-radius: 1rem;
        padding: 3rem 2rem;
        text-align: center;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(15, 46, 90, 0.2);
        position: relative;
        overflow: hidden;
    }
    .vmts-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        transform: rotate(30deg);
        pointer-events: none;
    }
    .vmts-header img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 1rem;
        background: white;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .vmts-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .vmts-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .vmts-card-header {
        background-color: #0f2e5a;
        color: white;
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .vmts-card-body {
        padding: 2rem;
        background-color: #ffffff;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
    }
    .vmts-list {
        padding-left: 1.5rem;
        margin-bottom: 0;
    }
    .vmts-list li {
        margin-bottom: 0.75rem;
    }
    .vmts-list li:last-child {
        margin-bottom: 0;
    }
</style>

<div class="d-flex">
    @include('layout.sidebar')
</div>

<div class="main-content-wrapper bg-light">
    <div class="container py-5">
        
        <!-- Header Banner -->
        <div class="vmts-header">
            <img src="{{ asset('assets/images/logo-baru.png') }}" alt="Logo Politala" onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Logo_Politeknik_Negeri_Tanah_Laut.png/600px-Logo_Politeknik_Negeri_Tanah_Laut.png';">
            <h1 class="display-4 fw-bold mb-2">VMTS</h1>
            <h3 class="fw-normal text-white-50">D-3 TEKNOLOGI INFORMASI POLITALA</h3>
        </div>

        <div class="row">
            <!-- Visi -->
            <div class="col-12">
                <div class="card vmts-card">
                    <div class="vmts-card-header justify-content-center text-center">
                        <i class="fas fa-eye fa-lg"></i> V I S I
                    </div>
                    <div class="vmts-card-body text-center fw-medium text-primary-emphasis fs-5 px-5 py-4">
                        Menjadi program studi terbaik di bidang Teknologi Informasi se-Kalimantan dan berdaya saing nasional pada tahun 2024
                    </div>
                </div>
            </div>

            <!-- Misi -->
            <div class="col-12">
                <div class="card vmts-card">
                    <div class="vmts-card-header justify-content-center text-center">
                        <i class="fas fa-rocket fa-lg"></i> M I S I
                    </div>
                    <div class="vmts-card-body">
                        <ol class="vmts-list">
                            <li>Menyelenggarakan dan mengembangkan pendidikan vokasi yang sesuai dengan kompetensi terstandar SKKNI level 5 di bidang Teknologi Informasi yang sesuai dengan kebutuhan industri di wilayah Kalimantan tahun 2024.</li>
                            <li>Menyelenggarakan penelitian dan pengabdian di bidang Teknologi Informasi secara berkelanjutan, yang mengelola dan memanfaatkan potensi lokal sehingga bermanfaat bagi masyarakat dan negara indonesia.</li>
                            <li>Mengoptimalkan penggunaan sistem berbasis IT yang terintegrasi dalam penyelenggaraan tridharma perguruan tinggi agar efektif, efisien dan akuntabel.</li>
                            <li>Meningkatkan Investasi sarana dan prasarana yang memenuhi kelayakan program studi Teknologi Informasi sesuai dengan kurikulum berbasis kebutuhan dunia industri.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="col-12">
                <div class="card vmts-card">
                    <div class="vmts-card-header justify-content-center text-center">
                        <i class="fas fa-bullseye fa-lg"></i> TUJUAN
                    </div>
                    <div class="vmts-card-body">
                        <ol class="vmts-list" style="list-style-type: decimal;">
                            <li>Menghasilkan tenaga terampil yang berkompeten, memiliki jiwa wirausaha dibidang Teknologi Informasi, beriman dan bertaqwa kepada Tuhan Yang Maha Esa dan mampu mengimplementasikan ilmunya di masyarakat.</li>
                            <li>Menghasilkan produk unggulan di bidang Teknologi Informasi yang berdaya guna dan berorientasi pada kebutuhan industri dan masyarakat, sehingga memiliki kebermanfaatan.</li>
                            <li>Meningkatkan efektifitas dan efisienitas, serta akuntabilitas dalam penyelenggaraan tridharma perguruan tinggi dengan penggunaan sistem berbasis IT.</li>
                            <li>Meningkatkan kepercayaan pada pemasaran lulusan, sehingga menghasilkan outcome yang berdaya saing nasional.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Sasaran Strategis -->
            <div class="col-12">
                <div class="card vmts-card">
                    <div class="vmts-card-header justify-content-center text-center">
                        <i class="fas fa-chess-knight fa-lg"></i> SASARAN STRATEGIS
                    </div>
                    <div class="vmts-card-body">
                        <ol class="vmts-list" style="list-style-type: decimal;">
                            <li>Meningkatnya lulusan yang mempunyai kompetensi di bidang Teknologi Informasi (hardskill dan softskill) yang mempunyai nilai-nilai etika, integritas dan akhlak mulia.</li>
                            <li>Terserapnya lulusan di IDUKA dengan melalui kompetensi yang terstandar di bidang Teknologi Informasi sesuai dengan SKKNI level 5.</li>
                            <li>Terciptanya lulusan mandiri yang berwirausaha di bidang Teknologi Informasi.</li>
                            <li>Meningkatnya kapasitas dosen dalam penelitian dan pengabdian kepada masyarakat, terutama yang sesuai dengan bidang ilmunya.</li>
                            <li>Meningkatnya kerjasama dengan pihak IDUKA dengan konsep saling menguntungkan terutama dalam tridharma perguruan tinggi di bidang Teknologi Informasi.</li>
                            <li>Meningkatnya jumlah pendukung kegiatan tridharma berbasis sistem teknologi informasi dan komunikasi.</li>
                            <li>Diterapkannya sistem yang terpadu melalui program aplikasi yang mudah, cepat, tepat dan akurat.</li>
                            <li>Meningkatnya sarana dan prasarana, peralatan, perlengkapan serta fasilitas pendukung, untuk memperluas daya tampung dan akses pelaksanaan tridharma.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

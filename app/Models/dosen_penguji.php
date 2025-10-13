<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dosen_penguji extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'dosen_penguji';

    // Primary key
    protected $primaryKey = 'id_penguji';

    // Jika primary key auto increment dan bertipe integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nip',
        'nama_dosen',
        'email',
        'no_hp',
        'created_at',
        'updated_at',
    ];
}

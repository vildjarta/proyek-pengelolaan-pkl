<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nip',
        'nama',
        'email',
        'no_hp',
    ];

    // --- Accessor & Mutator no_hp untuk kompatibilitas lama ---
    public function getNoHpAttribute()
    {
        if (array_key_exists('no_hp', $this->attributes) && !is_null($this->attributes['no_hp'])) {
            return $this->attributes['no_hp'];
        }

        if (array_key_exists('nomor_hp', $this->attributes) && !is_null($this->attributes['nomor_hp'])) {
            return $this->attributes['nomor_hp'];
        }

        return null;
    }

    public function setNoHpAttribute($value)
    {
        // prefer explicit column if exists
        if (Schema::hasColumn($this->getTable(), 'no_hp')) {
            $this->attributes['no_hp'] = $value;
        } elseif (Schema::hasColumn($this->getTable(), 'nomor_hp')) {
            $this->attributes['nomor_hp'] = $value;
        } else {
            $this->attributes['no_hp'] = $value;
        }
    }

    // optional relation to user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // relation to pembimbing (if you want)
    public function pembimbingRecords()
    {
        return $this->hasMany(DataDosenPembimbing::class, 'id_dosen', 'id_dosen');
    }

    // relation to penguji (if you want)
    public function pengujiRecords()
    {
        return $this->hasMany(dosen_penguji::class, 'id_dosen', 'id_dosen');
    }

    // alias so $dosen->id works in views
    public function getIdAttribute()
    {
        return $this->attributes[$this->getKeyName()] ?? $this->getKey();
    }
}

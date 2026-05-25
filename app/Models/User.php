<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status_mahasiswa',
        'alasan_tolak_ktm',
        'ktm_path',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================
    // KODE RELASI DATABASE (TAMBAHAN UNTUK BUANA BERLIAN)
    // =========================================================

    /**
     * Relasi: Satu user (pelanggan) bisa punya banyak pesanan travel
     */
    public function pesananTravel()
    {
        return $this->hasMany(PesananTravel::class);
    }

    /**
     * Relasi: Satu user (pelanggan) bisa punya banyak pesanan kargo
     */
    public function pesananKargo()
    {
        return $this->hasMany(PesananKargo::class);
    }

    /**
     * Relasi: Jika user ini login sebagai "driver", 
     * fungsi ini untuk memanggil jadwal keberangkatan si supir
     */
    public function jadwalSupir()
    {
        return $this->hasMany(Jadwal::class, 'driver_id');
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'tbl_user';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'level',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function createUser(Request $request)
    {
        return self::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);
    }

    public function updateUser(Request $request)
    {
        $this->nama = $request->nama;
        $this->username = $request->username;
        $this->email = $request->email;
        $this->level = $request->level;

        if ($request->filled('password')) {
            $this->password = Hash::make($request->password);
        }

        $this->save();
    }

    public function updateUserPassword(string $newPassword)
    {
        $this->password = Hash::make($newPassword);
        $this->save();
    }

    public function deleteUser()
    {
        $this->delete();
    }
}

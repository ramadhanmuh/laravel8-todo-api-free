<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Membuat data
     * 
     * @param array $input
     * 
     */
    public static function createData($input)
    {
        $input['created_at'] = time();

        return self::insertGetId($input);
    }

    /**
     * Mendapatkan data berdasarkan id dan token konfirmasi email
     * 
     * @param string $id
     * @param string $email_confirmation_token
     * 
     */
    public static function getByIdEmailConfirmationToken($id, $email_confirmation_token)
    {
        return self::where('id', $id)
                    ->where('email_confirmation_token', $email_confirmation_token)
                    ->first();
    }

    /**
     * Mengubah data menjadi terferifikasi berdasarkan id
     * 
     * @param string $id
     * 
     */
    public static function updateVerificationById($id)
    {
        return User::where('id', $id)
                    ->update([
                        'email_confirmation_token' => null,
                        'email_verified_at' => time()
                    ]);
    }

    /**
     * Mengubah data berdasarkan email
     * 
     * @param string $email
     * @param array $input
     * 
     */
    public static function updateByEmail($email, $input)
    {
        $input['updated_at'] = time();

        return self::where('email', $email)
                    ->update($input);
    }

    /**
     * Mendapatkan 1 data berdasarkan email
     * 
     * @param string $email
     * 
     */
    public static function getActiveDataByEmail($email)
    {
        return self::select('id', 'name', 'email', 'password', 'created_at', 'updated_at')
                    ->where('email', $email)
                    ->whereNotNull('email_verified_at')
                    ->first();
    }

    public function updateById($id, $input)
    {
        return self::where('id', $id)
                    ->update($input);
    }
}

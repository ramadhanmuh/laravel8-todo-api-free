<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'task',
        'description',
        'created_at',
        'updated_at'
    ];

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
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Membuat data
     * 
     * @param array $input
     * 
     */
    public static function createData($input)
    {
        return self::insert($input);
    }

    /**
     * Mendapatkan data berdasarkan token
     * 
     * @param string $token
     * 
     */
    public static function getByToken($token)
    {
        return self::where('token', $token)
                    ->first();
    }

    public static function deleteByUserId($user_id)
    {
        return self::where('user_id', $user_id)
                    ->delete();
    }
}

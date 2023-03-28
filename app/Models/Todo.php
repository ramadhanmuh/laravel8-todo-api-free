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

    public static function getData($input)
    {
        $columns = [
            'id', 'task', 'description', 'created_at',
            'updated_at'
        ];

        $directions = ['asc', 'desc'];

        if (array_key_exists('selects', $input)) {
            foreach ($input['selects'] as $key => $value) {
                if (!in_array($value, $columns)) {
                    unset($input['selects'][$key]);
                }
            }

            if (!count($input['selects'])) {
                return null;
            }
        } else {
            $input['selects'] = $columns;
        }

        $data = self::select($input['selects'])
                    ->where('user_id', $input['user_id']);

        if (array_key_exists('q', $input)) {
            $data->where(function ($query) use ($input) {
                $key = 0;
                foreach ($input['selects'] as $key => $value) {
                    if ($key) {
                        $query->orWhere($value, 'LIKE', '%' . $input['q'] . '%');
                    } else {
                        $query->where($value, 'LIKE', '%' . $input['q'] . '%');
                    }

                    $key++;
                }
            });
        }

        if (array_key_exists('created_at', $input)) {
            $data->where('created_at', '>', $input['created_at'] - 1);
        }

        if (array_key_exists('to_created_at', $input)) {
            $data->where('created_at', '<', $input['created_at'] + 1);
        }

        if (array_key_exists('order_by', $input)) {
            if (array_key_exists('order_direction', $input)) {
                if (in_array($input['order_by'], $columns)) {
                    if (in_array($input['order_direction'], $directions)) {
                        $data->orderBy($input['order_by'], $input['order_direction']);
                    }
                }
            }
        }

        return $data->offset($input['offset'])
                ->limit(10)
                ->get();
    }

    public static function countData($input)
    {
        $columns = [
            'id', 'task', 'description', 'created_at',
            'updated_at'
        ];

        if (array_key_exists('selects', $input)) {
            foreach ($input['selects'] as $key => $value) {
                if (!in_array($value, $columns)) {
                    unset($input['selects'][$key]);
                }
            }

            if (!count($input['selects'])) {
                return null;
            }
        } else {
            $input['selects'] = $columns;
        }

        $data = self::select('id')
                    ->where('user_id', $input['user_id']);

        if (array_key_exists('q', $input)) {
            $data->where(function ($query) use ($input) {
                $key = 0;
                foreach ($input['selects'] as $key => $value) {
                    if ($key) {
                        $query->orWhere($value, 'LIKE', '%' . $input['q'] . '%');
                    } else {
                        $query->where($value, 'LIKE', '%' . $input['q'] . '%');
                    }

                    $key++;
                }
            });
        }

        if (array_key_exists('created_at', $input)) {
            $data->where('created_at', '>', $input['created_at'] - 1);
        }

        if (array_key_exists('to_created_at', $input)) {
            $data->where('created_at', '<', $input['created_at'] + 1);
        }

        return $data->count();
    }
}

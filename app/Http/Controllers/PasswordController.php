<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;
 
class PasswordController extends Controller
{
    /**
     * Mengubah data kata sandi
     */
    public function update(UpdatePasswordRequest $request)
    {
        $input['password'] = Hash::make($request->password);

        if (!User::updateById($request->get('user_id'), $input)) {
            return response()->json([
                'status' => true
            ], 503);
        }

        return response()->json([
            'status' => true
        ]);
    }
}
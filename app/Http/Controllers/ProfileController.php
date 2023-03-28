<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
 
class ProfileController extends Controller
{
    /**
     * Mendapatkan data
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {        
        return response()->json([
            'data' => User::find($request->get('user_id'))
        ]);
    }

    /**
     * Mengubah data profil
     */
    public function update(UpdateProfileRequest $request)
    {
        $input = $request->safe()->only(['name', 'email']);

        User::updateById($request->get('user_id'), $input);

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Menghapus akun
     */
    public function destroy(Request $request)
    {
        if (!User::destroy($request->get('user_id'))) {
            return response()->json([
                'status' => false
            ], 503);
        }

        return response()->json([
            'status' => true
        ]);
    }
}
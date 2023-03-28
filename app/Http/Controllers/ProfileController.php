<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
 
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
            'data' => User::find($request->user_id)
        ]);
    }
}
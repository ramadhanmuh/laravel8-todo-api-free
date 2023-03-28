<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\UserToken;
use Illuminate\Http\Request;
 
class LogoutController extends Controller
{
    /**
     * Proses Logout
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function deleteToken(Request $request)
    {
        UserToken::deleteByUserId($request->user_id);
        
        return response()->json([
            'status' => true
        ]);
    }
}
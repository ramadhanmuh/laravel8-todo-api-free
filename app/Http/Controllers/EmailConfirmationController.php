<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
 
class EmailConfirmationController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, $token)
    {
        $data = User::getByIdEmailConfirmationToken($id, $token);

        if (empty($data)) {
            return response()->json([
                'status' => false
            ], 404);
        }

        User::updateVerificationById($id);

        return response('Akun berhasil diaktifkan.');
    }
}
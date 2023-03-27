<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailConfirmation;
 
class ForgotPasswordController extends Controller
{
    /**
     * Store new user
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function sendEmail(RegisterUserRequest $request)
    {
        $input = $request->safe()->only(['name', 'email']);

        $input['password'] = Hash::make($request->password);

        $input['email_confirmation_token'] = bin2hex(random_bytes(16));

        $data = User::createData($input);

        if (!$data) {
            return response()->json([
                'status' => false
            ], 503);
        }

        $input['id'] = $data;

        Mail::to($input['email'])->send(new EmailConfirmation($input));

        return response()->json([
            'status' => true
        ]);
    }
}
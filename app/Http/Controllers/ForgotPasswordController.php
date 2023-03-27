<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Hash;
 
class ForgotPasswordController extends Controller
{
    /**
     * Store new user
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function sendEmail(ForgotPasswordRequest $request)
    {
        $email = $request->email;

        $password = $this->createUniquePassword();

        $input['password'] = Hash::make($password);

        User::updateByEmail($email, $input);

        Mail::to($email)->send(new ForgotPassword($password));

        return response()->json([
            'status' => true
        ]);
    }

    private function createUniquePassword()
    {
        $randomString = bin2hex(random_bytes(8));

        // Create a unique ID based on the current timestamp
        $uniqueId = uniqid();

        // Concatenate the random string and the unique ID to create the URL string
        $urlString = $randomString . $uniqueId;

        // Encode the URL string for use in a URL
        $secureUrl = urlencode($urlString);

        // Output the secure URL
        return $secureUrl;
    }
}
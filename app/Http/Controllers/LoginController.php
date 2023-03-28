<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserToken;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Hash;
 
class LoginController extends Controller
{
    /**
     * Proses Login
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function authenticate(LoginUserRequest $request)
    {
        $data = User::getActiveDataByEmail($request->email);

        if (empty($data)) {
            return $this->sendFailedResponse();
        }

        if (!Hash::check($request->password, $data->password)) {
            return $this->sendFailedResponse();
        }

        $input = [
            'user_id' => $data->id,
            'token' => uniqid(),
            'created_at' => time()
        ];

        if (empty($request->remember_me)) {
            $input['remember_me'] = 0;
            $input['expired_at'] = $input['created_at'] + 10800;
        } else {
            $input['remember_me'] = 1;
            $input['expired_at'] = $input['created_at'] + 7884000;
        }

        UserToken::createData($input);

        return response()->json([
            'data' => $data,
            'token' => $input['token']
        ]);
    }

    private function sendFailedResponse()
    {
        return response()->json([
            'status' => false
        ], 401);
    }
}
<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Hash;
 
class RegisterController extends Controller
{
    /**
     * Store new user
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function store(RegisterUserRequest $request)
    {
        $input = $request->safe()->only(['name', 'email']);

        $input['password'] = Hash::make($request->password);

        User::createData($input);

        return response()->json([
            'status' => true
        ]);
    }
}
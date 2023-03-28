<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTodoRequest;
 
class TodoController extends Controller
{
    public function store(StoreTodoRequest $request)
    {
        $input = $request->validated();
        
        $input['user_id'] = $request->get('user_id');

        $input['created_at'] = time();

        if (!Todo::create($input)) {
            return $this->serviceUnavailableResponse();
        }

        return $this->successResponse();
    }

    private function serviceUnavailableResponse()
    {
        return response()->json([
            'status' => false
        ], 503);
    }

    private function successResponse()
    {
        return response()->json([
            'status' => true
        ]);
    }
}
<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTodoRequest;
 
class TodoController extends Controller
{
    public function index(Request $request)
    {
        $input = $request->query();

        $input['user_id'] = $request->get('user_id');

        if (array_key_exists('selects', $input)) {
            $input['selects'] = explode(',', $input['selects']);
        }

        if (array_key_exists('page', $input)) {
            $input['page'] = intval($input['page']);
            $input['offset'] = $input['page'] > 1 ? ($input['page'] * 10) - 10 : 0;
        } else {
            $input['page'] = 1;
            $input['offset'] = 0;
        }

        $total = Todo::countData($input);

        $data['pageTotal'] = intval(ceil($total / 10));

        $data['data'] = Todo::getData($input);

        return response()->json($data);
    }

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
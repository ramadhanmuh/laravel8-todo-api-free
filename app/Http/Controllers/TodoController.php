<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
 
class TodoController extends Controller
{
    /**
     * Get data
     */
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

    /**
     * Mendapatkan total data
     */
    public function count(Request $request)
    {
        $input = $request->query();

        $input['user_id'] = $request->get('user_id');

        if (array_key_exists('selects', $input)) {
            $input['selects'] = explode(',', $input['selects']);
        }

        return response()->json(Todo::countData($input));
    }

    public function show($id)
    {
        $data = Todo::find($id);

        if (empty($data)) {
            return $this->notFoundResponse();
        }

        return response()->json($data);
    }

    /**
     * Menambahkan data
     */
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

    /**
     * Mengubah data
     */
    public function update($id, UpdateTodoRequest $request)
    {
        $data = Todo::find($id);

        if (empty($data)) {
            return $this->notFoundResponse();
        }

        $input = $request->validated();

        $input['updated_at'] = time();

        if (!Todo::updateById($id, $input)) {
            return $this->serviceUnavailableResponse();
        }

        return $this->successResponse();
    }

    /**
     * Menghapus data
     */
    public function destroy($id)
    {
        $data = Todo::find($id);

        if (empty($data)) {
            return $this->notFoundResponse();
        }

        if (!$data->delete()) {
            return $this->serviceUnavailableResponse();
        }

        return $this->successResponse();
    }

    private function notFoundResponse()
    {
        return response()->json([
            'status' => false
        ], 404);
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
<?php

namespace App\Http\Controllers\V1;

use Validator;
use App\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\Todo as TodoReponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::all();

        return new TodoCollection($todos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'todo_title' => 'string|required',
        ];

        $this->validate($request, $rules);

        $newTodo = new Todo();
        $newTodo->todo_title = $request->title;
        throw new HttpException(Response::HTTP_UNAUTHORIZED, "Error in saving Todo");
        $newTodo->save();
        return [
            'status' => Response::HTTP_OK,
            'data' => [
                'message' => 'Todo successfully saved'
            ],
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todos = Todo::where('id', $id)->first();

        if(!$todos) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Todo {$id} not found");
        }

        return new TodoReponse($todos);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

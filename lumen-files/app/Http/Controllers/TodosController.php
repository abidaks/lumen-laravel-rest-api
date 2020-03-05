<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Todos;
use App\User;

 

class TodosController extends Controller
{
    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'description'=>'required',
            'datetime'=>'required',
            'status'=>'required',
            'category_id'=>'required'
        ]);

        if($validator->fails()){
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }

        //we get user id from token so user can add tasks for himself and not others.
        $api_token = ($request->header('api-token')) ? $request->header('api-token') : $request->input('api-token');
        $user = User::where('api_token', $api_token)->first();

        $todo = new Todos();
        $todo->name = $request->input('name');
        $todo->description = $request->input('description');
        $todo->datetime = $request->input('datetime');
        $todo->status = $request->input('status');
        $todo->category_id= $request->input('category_id');
        //we get user id from token so user can add tasks for himself and not others.
        $todo->user_id= $user->id;
        $todo->save();
        return array('error'=>false, 'todo'=>$todo);
    }
  
}
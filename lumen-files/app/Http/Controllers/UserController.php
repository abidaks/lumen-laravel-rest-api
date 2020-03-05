<?php

namespace App\Http\Controllers;

use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Category;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
        ]);

        //if validation fails 
        if ($validator->fails()) {
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }
    
        //creating a new user
        $user = new User();
        
        //adding values to the users
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->gender = $request->input('gender');
        $user->birthday = $request->input('birthday');
        $user->email = $request->input('email');
        $user->password = (new BcryptHasher)->make($request->input('password'));
        $user->api_token = '';
        
        //saving the user to database
        $user->save();
        
        //unsetting the password so that it will not be returned 
        unset($user->password);
 
        //returning the registered user 
        return array('error' => false, 'user' => $user);
    }
 
    //function for user login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
 
        if ($validator->fails()) {
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }
 
        $user = User::where('email', $request->input('email'))->first();
 
        if (isset($user->id)) {
            if (password_verify($request->input('password'), $user->password)) {
                $api_token = bin2hex(random_bytes(64));
                $user->api_token = $api_token;
                $user->save();
                unset($user->password);
                return array('error' => false, 'user' => $user, 'api-token' => $api_token);
            } else {
                return array('error' => true, 'message' => 'Incorrect Email or password'); // we don't want hackers to know that email exists in our database.
            }
        } else {
            return array('error' => true, 'message' => 'Incorrect Email or password'); // we don't want hackers to know that email exists in our database.
        }
    }

    //function for user login
    public function logout(Request $request)
    {
        $api_token = ($request->header('api-token')) ? $request->header('api-token') : $request->input('api-token');
        $user = User::where('api_token', $api_token)->first();
        $user->api_token = '';
        $user->save();
        return array('error' => false, 'msg' => 'successfully logout');

    }
 
    //getting the todos for a particular user 
    public function getTodos(Request $request)
    {
        //we get user id from token so that user cannot view other users tasks.
        $api_token = ($request->header('api-token')) ? $request->header('api-token') : $request->input('api-token');
        $user = User::where('api_token', $api_token)->first();

        if ($request->has("filters") && $request->input('filters') !== 'all') {
            $filters = $request->input('filters');
            if($filters == 'day' && $request->has("day")){
                $day = $request->input('day');
                $todos = DB::table('todos')
                        ->where('user_id', $user->id)
                        ->whereDay('datetime', $day)
                        ->get();

            }else if($filters == 'month' && $request->has("month")){
                $month = $request->input('month');
                $todos = DB::table('todos')
                        ->where('user_id', $user->id)
                        ->whereMonth('datetime', $month)
                        ->get();

            }else{
                $todos = $user->todos;
            }
        }else{
            $todos = $user->todos;
        }
        foreach ($todos as $key => $todo) {
            $category = Category::where('id', $todo->category_id)->first();
            unset($todos[$key]->category_id);
            $todos[$key]->category = $category;
        }
        return array('error' => false, 'todos' => $todos);
    }

    //
}

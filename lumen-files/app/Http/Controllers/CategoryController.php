<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Category; 

class CategoryController extends Controller
{

	public function add(Request $request){

		$validator = Validator::make($request->all(), [
            'name'=>'required|unique:categories'
        ]);

        if($validator->fails()){
            return array(
                'error' => true,
                'message' => $validator->errors()->all()
            );
        }

        $category = new Category(); 
        $category->name = $request->input('name');
        $category->save();

        return array('error'=>false, 'category'=>$category);
	}

}
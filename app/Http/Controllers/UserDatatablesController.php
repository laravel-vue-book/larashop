<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use App\User;

class UserDatatablesController extends Controller
{
    public function index(){
        return view('datatables/users');
    }

    public function data(){
        return Datatables::of(User::query())
               ->addColumn('action', function($item){
                  return '
                   <a href="'.route('users.edit', ['id' => $item->id]).'" class="btn btn-sm btn-primary">Edit</a> 
                   <a href="'.route('users.edit', ['id' => $item->id]).'" class="btn btn-sm btn-info">Detail</a>
                   <form onsubmit="return confirm(\'Delete this user permanently?\')" class="d-inline" action="'.route('users.destroy', ['id' => $item->id ]) . '" method="POST">
                        <input type="hidden" name="_token" value="'.csrf_token().'"/>
                        <input type="hidden" name="_method" value="DELETE">
                        
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                ';
               })
               ->make(true);
    }
}

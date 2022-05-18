<?php

namespace App\Http\Controllers\admin;
use View;
use Auth;
use DB;
use App\Role;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
class RoleController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  
	public function index()
  {
    if(Auth::user()->hasAnyRole('List Roles')){
      $roles = Role::orderBy('created_at', 'desc')->paginate(25);
      $start_page= (($roles->currentPage()-1) * 25) + 1;
      return view('admin.roles.index',array('roles'=>$roles,'start_page'=>$start_page));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  } 

	public function create()
  {
    if(Auth::user()->hasAnyRole('Create Role')){
      return View::make('admin.roles.create');
    }else{
       return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function store(Request $request)
  {
    if(Auth::user()->hasAnyRole('Create Role')){
      $this->validate($request,[
            'name'           =>'required|unique:roles,name',
            ]);
      // store
      $role = new Role;
      $role ->name       = Input::get('name');
      $role ->save();
      
      // redirect
      return Redirect::action('admin\RoleController@index')->with('flash-store','Data berhasil ditambahkan.');
    }else{
       return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function show($id)
  {
    if(Auth::user()->hasAnyRole('Details Role')){
      $role = Role::findOrFail($id);
      return view('admin.roles.show', array('role' => $role));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function edit($id)
  {
    if(Auth::user()->hasAnyRole('Edit Role')){
      $role = Role::findOrFail($id);     
      return view('admin.roles.edit', array('role' => $role));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function update(Request $request, $id)
  {
    if(Auth::user()->hasAnyRole('Edit Role')){
      // $this->validate($request,[
      //       'name'           =>'required|unique:roles,name',
      //       ]);
      $role = Role::findOrFail($id);
      $role ->name          = Input::get('name');
      $role ->save();
      
      // redirect
      return Redirect::action('admin\RoleController@index')->with('flash-update','Data berhasil diubah.');
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function destroy($id)
  {
    if(Auth::user()->hasAnyRole('Delete Role')){
  	  $role = Role::findOrFail($id);
  	  $grouprole = $role->groupRole;
      if(!$grouprole->isEmpty()){
          return Redirect::action('admin\RoleController@index')->with('flash-error','The role can not be removed because used in group roles.');  
      }else{
          $role->delete();
          return Redirect::action('admin\RoleController@index')->with('flash-success','The user has been deleted.');
      }
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function search(Request $request)
  {
      $search = $request->get('search');
      $roles = Role::where('name','LIKE','%'.$search.'%')->paginate(25);
      $start_page= (($roles->currentPage()-1) * 10) + 1;
        return view('admin.roles.index', compact('roles','start_page'));
  }
}

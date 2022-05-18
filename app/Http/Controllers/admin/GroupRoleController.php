<?php

namespace App\Http\Controllers\admin;
use View;
use Auth;
use DB;
use App\Role;
use App\Group;
use App\GroupRole;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class GroupRoleController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  
	public function index()
  {
    if(Auth::user()->hasAnyRole('List Group Roles')){
      $count = GroupRole::count();
      $group_roles = GroupRole::orderBy('created_at', 'desc')->paginate($count);
      $start_page= (($group_roles->currentPage()-1) * 25) + 1;
      return view('admin.grouproles.index',array('group_roles'=>$group_roles,'start_page'=>$start_page));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  } 

	public function create()
  {
    if(Auth::user()->hasAnyRole('Create Group Role')){
      $groups = Group::pluck('name','id');
      $groups->prepend('',0);
      $roles = Role::orderBy('id', 'desc')
                    ->pluck('name','id');
      $roles->prepend('',0);
      return View::make('admin.grouproles.create', array('roles' => $roles, 'groups' => $groups ));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function store(Request $request)
  {
    if(Auth::user()->hasAnyRole('Create Group Role')){
      $this->validate($request,[
            'group_id'           =>'required',
            'role_id'           =>'required',
            ]);
      // store
      $group_role = new GroupRole;
      $group_role ->group_id   = Input::get('group_id');
      $group_role ->role_id   = Input::get('role_id');
      $group_role ->save();
      
      // redirect
      return Redirect::action('admin\GroupRoleController@index')->with('flash-store','Data berhasil ditambahkan.');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function show($id)
  {
    if(Auth::user()->hasAnyRole('Details Group Role')){
      $group_role = GroupRole::findOrFail($id);
      return view('admin.grouproles.show', array('group_role' => $group_role));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function edit($id)
  {
    if(Auth::user()->hasAnyRole('Edit Group Role')){
      $group_role = GroupRole::findOrFail($id);
      $groups = Group::pluck('name','id');
      $roles = Role::pluck('name','id');
      return view('admin.grouproles.edit', array('group_role' => $group_role,'roles' => $roles, 'groups' => $groups));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function update(Request $request, $id)
  {
    if(Auth::user()->hasAnyRole('Edit Group Role')){
      $this->validate($request,[
            'group_id'           =>'required',
            'role_id'           =>'required',
            ]);
      $group_role = GroupRole::findOrFail($id);
      $group_role->group_id          = Input::get('group_id');
      $group_role->role_id          = Input::get('role_id');
      $group_role->save();
      
      // redirect
      return Redirect::action('admin\GroupRoleController@index')->with('flash-update','Data berhasil diubah.');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function destroy($id)
  {
    if(Auth::user()->hasAnyRole('Delete Group Role')){
  	  $group_role = GroupRole::findOrFail($id);
  	  $group_role->delete();
  	  return Redirect::action('admin\GroupRoleController@index')->with('flash-store','Data berhasil dihapus.');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function search(Request $request){
      $search = $request->get('search');
      $group_roles = GroupRole::join('groups', 'groups.id', '=', 'group_roles.group_id')
                    ->join('roles', 'roles.id', '=', 'group_roles.role_id')
                    ->where("groups.name", "like", '%'.$search.'%')
                    ->orWhere("roles.name", "like", "%".$search."%")->paginate(25);
      $start_page= (($group_roles->currentPage()-1) * 10) + 1;
        return view('admin.grouproles.index', compact('group_roles','start_page'));
  }
}



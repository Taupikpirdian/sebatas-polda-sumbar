<?php

namespace App\Http\Controllers\admin;
use View;
use Auth;
use DB;
use App\User;
use App\Group;
use App\UserGroup;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
class UserGroupController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  
	public function index()
  {
    if(Auth::user()->hasAnyRole('List User Groups')){
      $count =  UserGroup::count();
      $user_groups = UserGroup::orderBy('created_at', 'desc')->paginate($count);
      $start_page= (($user_groups->currentPage()-1) * 25) + 1;
      return view('admin.usergroups.index',array('user_groups'=>$user_groups,'start_page'=>$start_page));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  } 

	public function create()
  {
    if(Auth::user()->hasAnyRole('Create User Group')){
      $users = User::pluck('name','id');
      $users->prepend('',0);
      $groups = Group::pluck('name','id');
      $groups->prepend('',0);
      return View::make('admin.usergroups.create', array('users' => $users, 'groups' => $groups ));
    }else{
       return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function store(Request $request)
  {
    if(Auth::user()->hasAnyRole('Create User Group')){
      $this->validate($request,[
            'user_id'           =>'required',
            'group_id'           =>'required',
            ]);
      // store
      $user_group = new UserGroup;
      $user_group ->user_id   = Input::get('user_id');
      $user_group ->group_id   = Input::get('group_id');
      $user_group ->save();
      
      // redirect
      return Redirect::action('admin\UserGroupController@index')->with('flash-store','Data berhasil ditambahkan.');
    }else{
       return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function show($id)
  {
    if(Auth::user()->hasAnyRole('Details User Group')){
      $user_group = UserGroup::findOrFail($id);
      return view('admin.usergroups.show', array('user_group' => $user_group));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function edit($id)
  {

    if(Auth::user()->hasAnyRole('Edit User Group')){
      $user_group = UserGroup::findOrFail($id);
      $users = User::pluck('name','id');
      $groups = Group::pluck('name','id');
      return view('admin.usergroups.edit', array('user_group' => $user_group, 'users' => $users, 'groups' => $groups));
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function update(Request $request, $id)
  {
    if(Auth::user()->hasAnyRole('Edit User Group')){
      $this->validate($request,[
            'user_id'           =>'required',
            'group_id'           =>'required',
            ]);
      $user_group = UserGroup::findOrFail($id);
      $user_group->user_id          = Input::get('user_id');
      $user_group->group_id          = Input::get('group_id');
      $user_group->save();
      
      // redirect
      return Redirect::action('admin\UserGroupController@index')->with('flash-update','Data berhasil diubah.');
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function destroy($id)
  {
    if(Auth::user()->hasAnyRole('Delete User Group')){
  	  $user_group = UserGroup::findOrFail($id);
  	  $user_group->delete();
  	  return Redirect::action('admin\UserGroupController@index')->with('flash-destroy','Data berhasil dihapus.');
    }else{
      return response ("ERROR PERMISSIONS", 401);
    }
  }

  public function search(Request $request){
      $search = $request->get('search');
      $user_groups = UserGroup::join('users', 'users.id', '=', 'user_groups.user_id')
                    ->join('groups', 'groups.id', '=', 'user_groups.group_id')
                    ->where("users.name", "like", '%'.$search.'%')
                    ->orWhere("groups.name", "like", "%".$search."%")->paginate(25);
      return view('admin.usergroups.index', compact('user_groups'));
  }
}
<?php

namespace App\Http\Controllers\admin;
use View;
use Auth;
use DB;
use App\Group;
use App\GroupRole;
// use App\UserGroup;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
class GroupController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  
	public function index()
  {
    if(Auth::user()->hasAnyRole('List Groups')){
      $groups = Group::orderBy('created_at', 'desc')->paginate(25);
      $start_page= (($groups->currentPage()-1) * 25) + 1;
      return view('admin.groups.index',array('groups'=>$groups,'start_page'=>$start_page));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  } 

	public function create()
  {
    if(Auth::user()->hasAnyRole('Create Group')){
      return View::make('admin.groups.create');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function store(Request $request)
  {
    if(Auth::user()->hasAnyRole('Create Group')){
      $this->validate($request,[
            'name'           =>'required|unique:groups,name',
            ]);
      // store
      $group = new Group;
      $group ->name       = Input::get('name');
      $group ->save();
      
      // redirect
      return Redirect::action('admin\GroupController@index')->with('flash-store','Data berhasil ditambahkan.');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function show($id)
  {
    if(Auth::user()->hasAnyRole('Details Group')){
      $group = Group::findOrFail($id);
      return view('admin.groups.show', array('group' => $group));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function edit($id)
  {
    if(Auth::user()->hasAnyRole('Edit Group')){
      $group = Group::findOrFail($id);     
      return view('admin.groups.edit', array('group' => $group));
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function update(Request $request, $id)
  {
    if(Auth::user()->hasAnyRole('Edit Group')){
      $this->validate($request,[
            'name'           =>'required|unique:groups,name',
            ]);
      
      $group = Group::findOrFail($id);
      $group ->name          = Input::get('name');
      $group ->save();
      
      // redirect
      return Redirect::action('admin\GroupController@index')->with('flash-update','Data berhasil diubah.');
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function destroy($id)
  {
    if(Auth::user()->hasAnyRole('Delete Group')){
      $group_role = Group::findOrFail($id);
      // $id_group = GroupRole::findOrFail($id);
  	  $group = GroupRole::orderBy('group_roles.created_at','asc')
      // ->leftjoin('user_groups', 'user_groups.group_id', '=', 'group_roles.id' )
      ->leftJoin('groups', 'groups.id', '=', 'group_roles.group_id')
      ->whereGroupId($id)
      ->get();
          // echo "<pre>";
          // print_r($group);
          // echo "</pre>";
          // exit();

      // $group_role = $group->group_id; 
      if(!$group->isEmpty()){
          return Redirect::action('admin\GroupController@index')->with('flash-error','Group Role tidak bisa dihapus karena sudah diacu di Data Group Role dan User Group, Silakan menghapus data yang mengacu Group Role terlebih dahulu.');  
      }else{
          $group_role->delete();
          return Redirect::action('admin\GroupController@index')->with('flash-success','Group Role sudah berhasil dihapus.');
      }
    }else{
      return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function search(Request $request){
      $search = $request->get('search');
      $groups = Group::where('name','LIKE','%'.$search.'%')->paginate(25);
      return view('admin.groups.index', compact('groups'));
  }
}

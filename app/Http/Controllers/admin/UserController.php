<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use File;
use Image;
use App\User;	
use App\Group;	
use App\UserGroup;  
use App\UserSekolah;  
use App\Perkara;  
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index()
  {   
    $user = Auth::user();
    $count =  User::count();
    $users = User::leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
      				->leftJoin('groups', 'groups.id', '=', 'user_groups.group_id')
      				->select(
          							'users.name',
          							'users.id',
          							'users.email',
          							'users.divisi',
          							'groups.name as group_name'
      						    )
              ->whereNotIn('email', ['admin@sisdarkrim.ac.id'])
              ->paginate($count);
    $start_page= (($users->currentPage()-1) * 25) + 1;
    return view('admin.user.list',array('users'=>$users, 'user' => $user, 'start_page'=>$start_page));
  }

  public function create()
  {
    $groups = Group::orderBy('created_at', 'desc')->get();
    // $groups=Group::pluck('name', 'id');
    // $groups->prepend('=== Pilih Salah Satu ===', '');
		return View::make('admin.user.create', compact('groups'));
  }

  public function assignUserGroup($user)
  {
    $userGroup = new UserGroup;
    $userGroup->group_id  = Input::get('user_group_id');
    $userGroup->user_id   = $user->id;
    return $userGroup->save() == true ? [
      'isCreated' => true,
      'msg' => 'success'
    ] : [
      'isCreated' => false,
      'msg' => 'error'
    ];
  }

  public function store(Request $request)
  {
    // echo "<pre>";
    // print_r($request->divisi_polda);
    // echo "</pre>";
    // exit();
    // $validated = $request->validated();
    $this->validate($request, [
        'name'        => 'required|min:3|max:50',
        'email'       => 'email|unique:users',
        'password'    => 'required|confirmed|min:6',
    ],
    [
      'name.min' => 'nama minimal 3 karakter',
      'email.unique' => 'email sudah digunakan',
      'password.min' => 'password minimal 6 karakter',
      'password.confirmed' => 'password konfirmasi berbeda',
    ]);

    $user = new User;
    $user->name            = Input::get('name');
    $user->email           = Input::get('email');
    $user->password        = Hash::make(Input::get('password'));
      if($request->divisi_polda){
        $user->divisi           = $request->divisi_polda;
      }
      
      if($request->divisi_polres){
          $user->divisi           = $request->divisi_polres;
      }
      
      if($request->divisi_polsek){
          $user->divisi           = $request->divisi_polsek;
      }


    $response = $user->save() == true ? $this->assignUserGroup($user) : [
      'isCreated' => false,
      'msg' => 'error'
    ];

    return Redirect::action('admin\UserController@index')->with('flash-store','Data berhasil ditambahkan.');
  }

  public function show($id)
  {
    $user = User::leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
    				->leftJoin('groups', 'groups.id', '=', 'user_groups.group_id')
    				->select(
        							'users.name',
        							'users.email',
        							'users.password',
        							'users.created_at',
        							'groups.name as group_name'
    						    )
    				->findOrFail($id);
    return view('admin.user.show', array('user' => $user));
  }

  public function edit($id)
  {
    $user = User::leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
      				->leftJoin('groups', 'groups.id', '=', 'user_groups.group_id')
      				->select(
          							'users.id',
          							'users.name',
                        'users.email',
          							'users.divisi',
          							'groups.name as group_name',
          							'groups.id as group_id'
      						    )
    				->findOrFail($id);
    // echo "<pre>";
    // print_r($user);
    // echo "</pre>";
    // exit();
    $group1 = Group::where('id',$user->group_id)->firstOrFail();  
    $groups = Group::pluck('name', 'id');
    return view('admin.user.edit', array('user' => $user, 'groups' => $groups, 'group1' => $group1));
  }

  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $user->name       = Input::get('name');
    $user->email      = Input::get('email');
    $user->password   = Hash::make(Input::get('password'));
    // $response = $user->save() == true ? $this->assignUserGroup($user) : [
    //   'isCreated' => false,
    //   'msg' => 'error'
    // ];
    $user_groups = UserGroup::where('user_id', $id)->firstOrFail();
    $user_groups->group_id       = Input::get('user_group_id');
    if($request->divisi_polda){
      $user->divisi           = $request->divisi_polda;
    }
    
    if($request->divisi_polres){
        $user->divisi           = $request->divisi_polres;
    }
    
    if($request->divisi_polsek){
        $user->divisi           = $request->divisi_polsek;
    }
    $user_groups->save();
    $user->save();
    return Redirect::action('admin\UserController@index')->with('flash-update','Data berhasil diubah.');
  }

  public function destroy($id)
	{

    $check = Perkara::where('user_id', $id)->first();
        
        if($check != null){
          return Redirect::back()->withErrors(['Tidak bisa menghapus data ini, karena sudah memiliki data User Groups', 'The Message']);
        }else{
          $user_group = UserGroup::where('user_id', $id)->firstOrFail();
          $user = User::findOrFail($id);
          $user_group->delete();
          $user->delete();
          return Redirect::action('admin\UserController@index')->with('flash-destroy','Data berhasil dihapus.');
        }

    // $user_group = UserGroup::where('user_id', $id)->firstOrFail();
    // $user = User::findOrFail($id);
    // $user_group->delete();
    // $user->delete();
    // return Redirect::action('admin\UserController@index')->with('flash-destroy','User berhasil dihapus.');
	}

  public function search(Request $request)
  {
    $users = User::leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
              ->leftJoin('groups', 'groups.id', '=', 'user_groups.group_id')
              ->select(
                    'users.name',
                    'users.id',
                    'users.email',
                    'groups.name as group_name'
                  );

    if($request->has('search')){
    $search = $request->get('search');
    $users = $users->Where("users.name", "like", "%".$search."%")
                  ->orWhere("users.email", "like", "%".$search."%")
                  ->orWhere("groups.name", "like", "%".$search."%");
    }

    $users = $users->paginate(25);

    return view('admin.user.list',array('users'=>$users));
  }

  public function showuser($id)
  {
    $user = User::leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->leftJoin('groups', 'groups.id', '=', 'user_groups.group_id')
                ->select(
                      'users.id',
                      'users.name',
                      'users.email',
                      'groups.name as group_name',
                      'groups.id as group_id'
                    )
              ->findOrFail($id);
    return view('admin.user.show-user', array('user' => $user));
  }

  public function example()
  {
    $groups = Group::orderBy('created_at', 'desc')->get();
    return View::make('admin.user.example', compact('groups'));
  }
}

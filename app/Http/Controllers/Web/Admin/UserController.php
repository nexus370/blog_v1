<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->route()->getName() == 'admin-users.authors') {
            $roleId = 2;
        } else {
            $roleId = 0;
        }

        $query = User::where( function($query) use ($request) {
            if($id = $request->id) {
                $query->where('id', $id);
            }

            if($name = $request->name) {
                $query->where('name', 'like', '%'.$name.'%');
            }

            if($email = $request->email) {
                $query->where('email', 'like', '%'.$email.'%');
            }

            if($roleId = $request->role_id) {
                $query->where('role_id', $roleId);
            }else if ($request->route()->getName() == 'admin-users.authors') {
                $query->where('role_id', 2);
            }
    
            if($status = $request->status) {
                if($status == 1) {
                    $query->whereNull('deleted_at');
                } else {
                    $query->whereNotNull('deleted_at');
                }
            }

            if($fromDate = $request->from_date) {
                $query->whereDate('created_at', '>=', $fromDate);
            } else if($toDate = $request->to_date) {
                $query->whereDate('created_at', '<=', $toDate);
            } else if ($fromDate = $request->from_date && $toDate = $request->to_date) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            }
            
        });

        if(!isset($request->order_by)) {
            $order_by = 8;
        }else {
            $order_by = $request->order_by;
        }

        switch($order_by) {
            case 1: 
                $orderBy = 'name';
                $order = 'asc';
                $query->orderBy($orderBy, $order);
                break;
            case 2: 
                $orderBy = 'name';
                $order = 'desc';
                $query->orderBy($orderBy, $order);
                break;
            case 3:
                $orderBy = 'email';
                $order = 'desc';
                $query->orderBy($orderBy, $order);
                break;
            case 4: 
                $orderBy = 'email';
                $order = 'asc';
                $query->orderBy($orderBy, $order);
                break;
            case 5: 
              
                break;
            case 6: 
             
                break;
            case 7:
                $orderBy = 'created_at';
                $order = 'asc';
                $query->orderBy($orderBy, $order);
                break;
            case 8:
                $orderBy = 'created_at';
                $order = 'desc';
                $query->orderBy($orderBy, $order);
                break;
        }


        $users = $query->with('role')->paginate(15);

        $roles = Role::all();

        $request->flash();

        return view('admin.user.index', compact('users', 'roles', 'order_by', 'roleId'));
    }

 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $query = Post::filter($request);

        if(!isset($request->order_by)) {
            $order_by = 6;
        }else {
            $order_by = $request->order_by;
        }

        switch($order_by) {
            case 1: 
                $orderBy = 'title';
                $order = 'asc';
                $query->orderBy($orderBy, $order);
                break;
            case 2: 
                $orderBy = 'title';
                $order = 'desc';
                $query->orderBy($orderBy, $order);
                break;
            case 3:
                $query->orderByUniqueViews();
                break;
            case 4: 
                $query->orderByUniqueViews('asc');
                break;
            case 5:
                $orderBy = 'created_at';
                $order = 'asc';
                $query->orderBy($orderBy, $order);
                break;
            case 6:
                $orderBy = 'created_at';
                $order = 'desc';
                break;
            default: 
                $orderBy = 'created_at';
                $order = 'desc';
                $query->orderBy($orderBy, $order);
        }

        $posts = $query->withTrashed()->paginate(15);

        $categories = Category::all();

        $request->flash();

        return view('admin.user.show', compact('user', 'posts', 'categories', 'order_by'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        session()->flash('info', 'The user was updated');

        return redirect()->route('admin-users.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id);
        session()->flash('info', 'The user was updated');
        
        return redirect()->route('admin-users.index');
    }
}

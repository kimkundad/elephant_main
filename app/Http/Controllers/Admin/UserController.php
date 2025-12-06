<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        // ดึงผู้ใช้ที่ไม่มี role superAdmin
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'superAdmin');
        })->get();

        return view('admin.users.index', compact('users'));
    }
}

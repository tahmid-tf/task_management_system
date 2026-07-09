<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.add-user');
    }

    public function store()
    {

    }
}

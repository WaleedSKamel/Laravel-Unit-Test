<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function store()
    {
        $author = Author::create(\request()->only([
            'name','dob'
        ]));
        //return redirect($author->path());
    }


    protected function validateRequest()
    {
        return \request()->validate([
            'name' => 'required',
            'dob' => 'required',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function documents_dashboard()
    {
        return view('pages.pages_backend.documents_dashboard');
    }
}

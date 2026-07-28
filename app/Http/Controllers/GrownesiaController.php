<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GrownesiaController extends Controller
{
  public function landing()
  {
    if (Auth::check()) {
      return redirect()->route(Auth::user()->getDashboardRouteName());
    }
    return view('grownesia.landing');
  }
}

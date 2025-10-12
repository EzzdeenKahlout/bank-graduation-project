<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, ['ar', 'en'])) {
            abort(400);
        }
        session(['locale' => $locale]);
        
        if (auth()->check()) {
            auth()->user()->update(['preferred_language' => $locale]);
        }
         
        return redirect()->back();
    }
}

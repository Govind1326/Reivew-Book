<?php

namespace App\Http\View\Composers;
use \App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = User::find(Auth::id());
            $reviewCount = $user->reviews()->count();
            $view->with('reviewCount', $reviewCount);
        }
    }
}

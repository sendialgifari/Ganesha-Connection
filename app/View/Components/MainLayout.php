<?php

namespace App\View\Components;

use Closure;
use Route;
use App\Models\StaticPage;
use ProtoneMedia\Splade\Components\PersistentComponent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.main-layout', [
                    'canLogin' => Route::has('login'),
                    'canRegister' => Route::has('register'),
                    'static_pages' => StaticPage::orderBy('name', 'ASC')->get(),
                ]);
    }
}

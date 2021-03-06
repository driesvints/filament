<?php

namespace Filament\View\Components;

use Illuminate\View\Component;

class Nav extends Component
{
    public $nav;

    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct()
    {
        $nav = app('Filament\Navigation');

        $this->nav = $nav->items()
                        
                        ->sortBy('sort')
                        ->groupBy('group')
                        ->all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('filament::components.nav');
    }
}
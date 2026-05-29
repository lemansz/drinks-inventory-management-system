<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavLinks extends Component
{
    /**
     * Create a new component instance.
     */
    
    public $icon = null;

    public function __construct($icon = null)
    {
        $this->icon = $icon;
    }

    /**
     * Get the SVG icon contents
     */

    public function getIcon()
    {
        if ($this->icon) {
            $path = public_path('images/' . $this->icon);
            if (file_exists($path))
            {
                return file_get_contents($path);
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav-links');
    }
}

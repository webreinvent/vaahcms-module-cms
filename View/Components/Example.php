<?php namespace VaahCms\Modules\Cms\View\Components;

use Illuminate\View\Component;

class Example extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('cms::frontend.components.contents');
    }
}

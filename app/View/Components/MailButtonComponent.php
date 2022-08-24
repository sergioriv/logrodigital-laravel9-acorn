<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MailButtonComponent extends Component
{
    public $url;
    public $text;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($text, $url)
    {
        $this->text = $text;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.mail.button')->with([
            'url' => $this->url,
            'slot' => $this->text
        ]);
    }
}

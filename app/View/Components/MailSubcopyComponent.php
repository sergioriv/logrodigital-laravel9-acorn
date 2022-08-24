<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MailSubcopyComponent extends Component
{
    public $textButton;
    public $url;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($textButton, $url)
    {
        $this->textButton = $textButton;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.mail.subcopy')->with([
            'textButton' => $this->textButton,
            'url' => $this->url
        ]);
    }
}

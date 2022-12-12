<?php

namespace App\View\Components;

use App\Http\Controllers\SchoolController;
use Illuminate\View\Component;

class MailMessageComponent extends Component
{
    public $content;
    protected $SCHOOL;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->SCHOOL = SchoolController::myschool()->getData();
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.mail.message')->with([
            'SCHOOL' => $this->SCHOOL,
            'slot' => $this->content
        ]);
    }
}

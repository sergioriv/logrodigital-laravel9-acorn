<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\View\Components\MailButtonComponent;
use App\View\Components\MailLineComponent;
use App\View\Components\MailMessageComponent;
use App\View\Components\MailSubcopyComponent;
use App\View\Components\MailTitleComponent;
use Illuminate\Contracts\Support\Htmlable;

class ContentMail extends Controller
{
    /**
     * The "Content" lines of the notification.
     *
     * @var array
     */
    public $content;

    /**
     * The text / label for the action.
     *
     * @var string
     */
    public $actionText;

    /**
     * The action URL.
     *
     * @var string
     */
    public $actionUrl;

    /**
     * Add a title of text to the notification.
     *
     * @param  mixed  $line
     * @return $this
     */
    public function title($text)
    {
        $this->content .= (new MailTitleComponent( $this->formatLine($text) ))->render();

        return $this;
    }

    /**
     * Add a line of text to the notification.
     *
     * @param  mixed  $line
     * @return $this
     */
    public function line($text)
    {
        $this->content .= (new MailLineComponent( $this->formatLine($text) ))->render();

        return $this;
    }

    /**
     * Configure the "call to action" button.
     *
     * @param  string  $text
     * @param  string  $url
     * @return $this
     */
    public function action($text, $url)
    {
        $this->actionText = $this->formatLine($text);
        $this->actionUrl = $this->formatLine($url);

        $this->content .= (new MailButtonComponent( $this->actionText, $this->actionUrl ))->render();

        return $this;
    }

    /**
     * Add a subcopy of text to the notification.
     *
     * @param  mixed  $line
     * @return $this
     */
    public function subcopy()
    {
        $this->content .= (new MailSubcopyComponent( $this->actionText, $this->actionUrl ))->render();

        return $this;
    }

    /**
     * Add a line of text to the notification.
     *
     * @param  mixed  $line
     * @return $this
     */
    /* public function with($line)
    {
        if ($line instanceof Action) {
            $this->action($line->text, $line->url);
        } elseif (! $this->actionText) {
            $this->introLines[] = $this->formatLine($line);
        } else {
            $this->outroLines[] = $this->formatLine($line);
        }

        return $this;
    } */

    /**
     * Format the given line of text.
     *
     * @param  \Illuminate\Contracts\Support\Htmlable|string|array  $line
     * @return \Illuminate\Contracts\Support\Htmlable|string
     */
    protected function formatLine($line)
    {
        if ($line instanceof Htmlable) {
            return $line;
        }

        if (is_array($line)) {
            return implode(' ', array_map('trim', $line));
        }

        return trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line ?? ''))));
    }

    /**
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toContent()
    {
        return (new MailMessageComponent( $this->content ))->render();
    }
}

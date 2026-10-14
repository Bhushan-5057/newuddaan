<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MailTemplate;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $username;
    public $password;

    protected $template;
    
public function __construct($username, $email, $password)
    {
 $this->username = $username;
$this->email = $email;
$this->password = $password;


        // Fetch the template with slug 'register-template'
        $this->template = MailTemplate::where('slug', 'registion-template')
                            ->where('status', true)
                            ->firstOrFail();
    }

    public function build()
    {
        $parsedHtml = $this->parseTemplate($this->template->template, [
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        return $this->subject($this->template->subject)
                    ->view('emails.user_registered')
                    ->with([
                        'subject' => $this->template->subject,
                        'templateContent' => $parsedHtml
                    ]);
    }


 protected function parseTemplate($template, $data)
    {
        // Find all placeholders like {{ key }} or { key }
        preg_match_all('/\{\{\s*(\w+)\s*\}\}|\{\s*(\w+)\s*\}/', $template, $matches);

        // Unique placeholder keys found in the template
        $placeholders = array_unique(array_filter(array_merge($matches[1], $matches[2])));

        foreach ($placeholders as $key) {
            if (isset($data[$key])) {
                $template = str_replace(
                    ['{{ ' . $key . ' }}', '{{' . $key . '}}', '{ ' . $key . ' }', '{' . $key . '}'],
                    $data[$key],
                    $template
                );
            }
        }

        return $template;
    }
}
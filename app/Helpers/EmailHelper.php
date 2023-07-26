<?php namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Helpers\Email;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Snowfire\Beautymail\Beautymail;

class EmailHelper extends Controller
{
    private $path;
    public function __construct()
    {
        try {
            $this->path = url()->asset('/img/logo_aclink.png');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getParameters (array $data) : bool
    {
        $mdlEmail = new Email();
        $mdlEmail->template = $data['template'];
        $mdlEmail->title = $data['title'];
        $mdlEmail->parameters = [
            'name_system' => $data['name_system'],
            'description' => $data['description'],
            'title' => $data['title'],
            'text_button' => $data['text_button'],
            'link' => $data['link']
        ];
        $mdlEmail->emails = [];

        foreach ($data['emails'] as $email) {

            array_push($mdlEmail->emails, [
                'name' => $email->name,
                'email' => $email->email,
            ]);
        }

        return $this->sendEmail($mdlEmail);
    }

    public function sendEmail (Object $mdlEmail) : bool
    {
        $beautymail = app()->make(Beautymail::class);
        foreach ($mdlEmail->emails as $email) {
            $mdlEmail->parameters['email'] = $email['email'];
            $mdlEmail->parameters['nome'] = $email['name'] ?? $email['nome'];

            $beautymail->send(
                $mdlEmail->template,
                [
                    'logo' =>
                    [
                        'path' => ($mdlEmail->path) ?? $this->path,
                        'width' => ($mdlEmail->width) ?? null,
                        'height' => ($mdlEmail->height) ?? null,
                    ],
                    'parameters' => $mdlEmail->parameters,
                ],
                function ($message) use ($mdlEmail, $email) {
                    $message->from(env("MAIL_FROM_ADDRESS"), $mdlEmail->systemName);
                    $message->subject($mdlEmail->title);
                    $message->to($email['email'], $email['name']);
                    if (!empty($mdlEmail->bccs) && is_array($mdlEmail->bccs)) {
                        foreach ($mdlEmail->bccs as $bcc) {
                            $message->bcc($bcc['email'], $bcc['name'] ?? $bcc['nome']);
                        }
                    }

                    if (!empty($mdlEmail->files) && is_array($mdlEmail->files)) {
                        foreach ($mdlEmail->files as $file) {
                            $message->attachData($file['file'], $file['name'] ?? $file['nome'], ['mime' => $file['type']]);
                        }
                    }
                }
            );
        }
        return true;
    }
}

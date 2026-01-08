<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class TestMailController extends Controller
{
    public function sendTestEmail()
    {
        Mail::to('test@example.com')->send(new \App\Mail\TestMail());
        
        return 'Письмо отправлено в логи';
    }
}

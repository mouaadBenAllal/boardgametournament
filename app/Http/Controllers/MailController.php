<?php

namespace App\Http\Controllers;

use App\Components\FlashSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    /**
     * Function to send an mail provided with the data from the contact form.
     */
    public function sendMail(Request $request)
    {
        // Define the name of the receipent:
        $receipentName = $request->input('name');
        // Define the mail of the receipent:
        $receipentMail = $request->input('email');
        // Define the message of the mail:
        $message = $request->input('body');
        // Define the mail of system:
        $systemMail = env('MAIL_USERNAME');
        // Define the name of the system:
        $systemName = env('APP_NAME');
        // Define the content of the mail:
        $content = array('name' => $receipentName, 'email' => $receipentMail, 'body' => $message);
        // Try to send the mail:
        Mail::send('emails.template', $content, function($message) use ($systemMail, $systemName, $receipentMail) {
            // Define the receipent:
            $message->from($systemMail, $systemName);
            // Define the receiver:
            $message->to($systemMail, $systemName)->subject('Contactformulier of suggestie door: ' . $receipentMail);
        });
        // Check if the mail is send or not:
        if(count(Mail::failures()) > 0) {
            // Flash an error:
            FlashSession::addAlert('error', 'Contactformulier of suggestie is  niet verstuurd!');
        } else {
            // Flash an success:
            FlashSession::addAlert('success', 'Contactformulier of suggestie is succesvol verstuurd!');
        }
        // Return to the overview:
        return redirect(route('/'));

    }
}

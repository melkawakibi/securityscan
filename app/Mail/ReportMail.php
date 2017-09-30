<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    private $report;    
    private $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report, $customer)
    {
        $this->report = $report;        
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $file = basename($this->report->file);
        $subject = 'Rapport: ' . $file;

        $email = $this->view('email.report')->with(['customer' => $this->customer, 'report' => $this->report, 'file' => $file]);

        $email->subject($subject);
        $email->attach($this->report->file);

        return $email;

    }
}

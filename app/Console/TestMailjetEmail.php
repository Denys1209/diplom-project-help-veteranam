<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Mailjet\LaravelMailjet\Facades\Mailjet;

class TestMailjetEmail extends Command
{
    protected $signature = 'mailjet:test {email}';
    protected $description = 'Test Mailjet email configuration';

    public function handle()
    {
        $email = $this->argument('email');

        try {
            // Test using Laravel Mail facade
            Mail::raw('This is a test email from Laravel using Mailjet.', function ($message) use ($email) {
                $message->to($email)
                        ->from('water.resistant.5atm@gmail.com', 'Test Laravel App')
                        ->subject('Test Email - Laravel Mailjet Integration');
            });

            $this->info("✅ Test email sent successfully to {$email} using Laravel Mail");

            // Test using Mailjet facade directly
            $response = Mailjet::send([
                'Messages' => [
                    [
                        'From' => [
                            'Email' => 'water.resistant.5atm@gmail.com',
                            'Name' => 'Laravel Test'
                        ],
                        'To' => [
                            [
                                'Email' => $email,
                                'Name' => 'Test Recipient'
                            ]
                        ],
                        'Subject' => 'Direct Mailjet API Test',
                        'TextPart' => 'This is a test email sent directly through Mailjet API.',
                        'HTMLPart' => '<h3>Test Email</h3><p>This is a test email sent directly through <strong>Mailjet API</strong>.</p>'
                    ]
                ]
            ]);

            if ($response->success()) {
                $this->info("✅ Direct Mailjet API test successful");
                $this->info("Message ID: " . $response->getData()['Messages'][0]['To'][0]['MessageID']);
            } else {
                $this->error("❌ Direct Mailjet API test failed");
                $this->error("Error: " . $response->getReasonPhrase());
            }

        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->error("Make sure your Mailjet credentials are correct and the sender email is verified.");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optis:send:email {--password=} {--toemail=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email when new user registered password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        try {
            $to_name = $this->option('toemail');
            $to_email = $this->option('toemail');
            $data = [
                'body' => $this->option('password')
            ];
            Mail::send('mail', compact('data'), function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Şifrə yenilənməsi');
                $message->from('optis@optima.az', 'Optima Tapşırıq İdarə Etmə Sistemi');
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Email gondərilməsində problem yaşandı.Doğru email daxil edin!!'
            ], 500);
        }

    }
}

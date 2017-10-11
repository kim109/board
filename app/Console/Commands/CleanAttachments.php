<?php

namespace App\Console\Commands;

use App\Attachment;
use Illuminate\Console\Command;

class CleanAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachment:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean unlinked attachments';

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
     * @return mixed
     */
    public function handle()
    {
        $attachments = Attachment::where('attach_id', null);
        $attachments->each(function ($attachment) {
            $path = storage_path('app/'.$attachment->path);
            unlink($path);
            $attachment->delete();
        });
    }
}

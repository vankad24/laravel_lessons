<?php

namespace App\Console\Commands;

use App\Events\Post\PostPublishedEvent;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts that have been scheduled and their publication date has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postsToPublish = Post::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->get();

        $publishedCount = 0;
        foreach ($postsToPublish as $post) {
            $post->status = 'published';
            $post->published_at = now();
            $post->save();

            event(new PostPublishedEvent($post));
            $publishedCount++;
        }

        $this->info("Опубликовано {$publishedCount} статей.");

        return Command::SUCCESS;
    }
}

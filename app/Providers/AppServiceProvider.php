<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'qna' => 'App\Question',
            'answers' => 'App\Answer',
            'seminars' => 'App\Seminar',
            'notices' => 'App\Notice',
            'market' => 'App\Market',
            'comments' => 'App\Comment',
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

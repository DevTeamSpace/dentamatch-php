<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
          
         $this->app->singleton('League\Glide\Server', function($app){
            $filesystem = $app->make('Illuminate\Contracts\Filesystem\Filesystem');
           
         
                $source = $filesystem->getDriver();
               
            $dir = storage_path().'/framework/images/cache';
            return  \League\Glide\ServerFactory::create([
                'source'=>$source,
                'cache'=>$dir,
                'source_path_prefix'=>'',
                'cache_path_prefix'=>'',
            ]);
        });
        
    }
    
    
}

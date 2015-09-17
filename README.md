# Wasabi Artisan
Setup wasabi on a laravel 5 installation using php artisan

## Installation
Just follow the laravel artisan command installation [here](http://laravel.com/docs/5.0/commands#registering-commands). 
Copy the Wasabi.php file to app/Console/Commands/ directory. And add "\App\Console\Commands\Wasabi::class" to the 
$commands property of Kerner.php found at app/Console/.

```PHP
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\Wasabi::class // <--- Wasabi here
    ];

```
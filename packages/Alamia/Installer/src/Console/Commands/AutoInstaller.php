<?php

namespace Alamia\Installer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Providers\CoreServiceProvider;
use Alamia\Installer\Database\Seeders\DatabaseSeeder as AlamiaConnectDatabaseSeeder;
use Alamia\Installer\Events\ComposerEvents;

class AutoInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alamia:install-auto
        { --force : Force the operation to run when in production. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AlamiaConnect automated installer using environment variables.';

    /**
     * Install and configure AlamiaConnect.
     */
    public function handle()
    {
        $this->warn('Starting automated installation...');

        // 1. Generate Key
        $this->warn('Step: Generating key...');
        $this->call('key:generate');

        // 2. Migrate
        $this->warn('Step: Migrating all tables...');
        $migrationPaths = [
            base_path('packages/Webkul/*/src/Database/Migrations'),
            base_path('packages/Alamia/*/src/Database/Migrations'),
            database_path('migrations'),
        ];

        $filesToRun = [];
        $isSqlite = DB::getDriverName() === 'sqlite';

        foreach ($migrationPaths as $globPath) {
            $directories = glob($globPath, GLOB_ONLYDIR);
            foreach ($directories as $fullPath) {
                $files = File::files($fullPath);
                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    $content = File::get($file->getRealPath());

                    // Exclude specific problematic Webkul migrations and the shim itself
                    $isProblematic = strpos($filename, '2021_09_30_154222') !== false || 
                                    strpos($filename, '2021_09_30_161722') !== false ||
                                    strpos($filename, '2021_11_11_180804') !== false;
                    
                    // Also exclude any Webkul migration that uses dropForeign or MySQL specific functions if on SQLite
                    $hasDropForeign = strpos($content, 'dropForeign') !== false;
                    $hasMySQLisms = strpos($content, 'CONCAT(') !== false || strpos($content, 'JSON_UNQUOTE') !== false;
                    $isWebkul = strpos($fullPath, 'Webkul') !== false;

                    if ($isProblematic || ($isSqlite && $isWebkul && ($hasDropForeign || $hasMySQLisms)) || strpos($filename, 'shim_webkul_lead_migrations') !== false) {
                        continue;
                    }

                    $filesToRun[] = $file->getRealPath();
                }
            }
        }

        $this->call('migrate:fresh', [
            '--path' => $filesToRun,
            '--realpath' => true,
            '--force' => true
        ]);

        // Run shim migration
        $this->call('migrate', [
            '--path' => 'packages/Alamia/Installer/src/Database/Migrations/2021_09_30_150000_shim_webkul_lead_migrations.php',
            '--realpath' => true,
            '--force' => true
        ]);

        // 3. Seed
        $this->warn('Step: Seeding basic data...');
        $this->info(app(AlamiaConnectDatabaseSeeder::class)->run([
            'locale'   => config('app.locale', 'en'),
            'currency' => config('app.currency', 'USD'),
        ]));

        // 4. Publish Assets
        $this->warn('Step: Publishing assets...');
        $this->call('vendor:publish', ['--provider' => CoreServiceProvider::class, '--force' => true]);

        // 5. Storage Link
        $this->warn('Step: Linking storage directory...');
        $this->call('storage:link');

        // 6. Clear Cache
        $this->warn('Step: Clearing cache...');
        $this->call('optimize:clear');

        // 7. Create Admin
        $this->createAdminCredentials();

        ComposerEvents::postCreateProject();
        
        $this->info('AlamiaConnect installed successfully!');
    }

    /**
     * Create admin credentials from environment variables.
     */
    protected function createAdminCredentials()
    {
        $this->warn('Step: Creating admin user...');

        $adminName = env('ALAMIA_ADMIN_NAME', 'Admin');
        $adminEmail = env('ALAMIA_ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ALAMIA_ADMIN_PASSWORD', 'admin123');

        $password = password_hash($adminPassword, PASSWORD_BCRYPT, ['cost' => 10]);

        try {
            DB::table('users')->updateOrInsert(
                ['id' => 1],
                [
                    'name'     => $adminName,
                    'email'    => $adminEmail,
                    'password' => $password,
                    'role_id'  => 1,
                    'status'   => 1,
                ]
            );

            $filePath = storage_path('installed');
            File::put($filePath, 'AlamiaConnect is successfully installed');

            $this->info("Admin created: {$adminEmail}");
            
            Event::dispatch('AlamiaConnect.installed');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}

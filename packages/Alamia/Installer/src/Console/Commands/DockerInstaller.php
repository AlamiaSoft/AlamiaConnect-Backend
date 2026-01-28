<?php

namespace Alamia\Installer\Console\Commands;

use Alamia\Installer\Database\Seeders\DatabaseSeeder as AlamiaConnectDatabaseSeeder;
use Alamia\Installer\Events\ComposerEvents;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Webkul\Core\Providers\CoreServiceProvider;

class DockerInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alamia:install-docker
        { --force : Force the operation to run when in production. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AlamiaConnect specialized installer for Docker/Portainer environments.';

    /**
     * Install and configure AlamiaConnect.
     */
    public function handle()
    {
        $this->warn('Starting Docker-optimized installation...');

        // 1. Generate Key
        $this->warn('Step: Generating key...');
        $this->call('key:generate', ['--force' => true]);

        // 2. Migrate
        $this->warn('Step: Migrating all tables...');
        $migrationPaths = [
            base_path('packages/Webkul/*/src/Database/Migrations'),
            base_path('packages/Alamia/*/src/Database/Migrations'),
            database_path('migrations'),
        ];

        $filesToRun = [];

        foreach ($migrationPaths as $globPath) {
            $directories = glob($globPath, GLOB_ONLYDIR);
            if (! $directories) {
                continue;
            }

            foreach ($directories as $fullPath) {
                $files = File::files($fullPath);
                foreach ($files as $file) {
                    $filename = $file->getFilename();

                    // Exclude specific problematic Webkul migrations and the shim itself
                    $isProblematic = strpos($filename, '2021_09_30_154222') !== false ||
                                    strpos($filename, '2021_09_30_161722') !== false ||
                                    strpos($filename, '2021_11_11_180804') !== false;

                    if ($isProblematic || strpos($filename, 'shim_webkul_lead_migrations') !== false) {
                        continue;
                    }

                    $filesToRun[] = $file->getRealPath();
                }
            }
        }

        $this->call('migrate:fresh', [
            '--path'     => $filesToRun,
            '--realpath' => true,
            '--force'    => true,
        ]);

        // Run shim migration specifically to avoid "column already exists" conflict
        $this->warn('Step: Running shim migration...');
        $this->call('migrate', [
            '--path'     => 'packages/Alamia/Installer/src/Database/Migrations/2021_09_30_150000_shim_webkul_lead_migrations.php',
            '--realpath' => true,
            '--force'    => true,
        ]);

        // 3. Seed
        $this->warn('Step: Seeding basic data...');
        app(AlamiaConnectDatabaseSeeder::class)->run([
            'locale'   => config('app.locale', 'en'),
            'currency' => config('app.currency', 'PKR'),
        ]);

        // 4. Publish Assets
        $this->warn('Step: Publishing assets...');
        $this->call('vendor:publish', ['--provider' => CoreServiceProvider::class, '--force' => true]);

        // 5. Storage Link
        $this->warn('Step: Linking storage directory...');
        try {
            $this->call('storage:link');
        } catch (\Exception $e) {
            $this->warn('Storage link already exists or failed: '.$e->getMessage());
        }

        // 6. Clear Cache
        $this->warn('Step: Clearing cache...');
        $this->call('optimize:clear');

        // 7. Create Admin
        $this->createAdminCredentials();

        // Dispatch post-install events
        ComposerEvents::postCreateProject();

        $this->info('AlamiaConnect Docker installation completed successfully!');
    }

    /**
     * Create admin credentials from environment variables.
     */
    protected function createAdminCredentials()
    {
        $this->warn('Step: Creating admin user...');

        $adminName = env('ALAMIA_ADMIN_NAME', 'Admin');
        $adminEmail = env('ALAMIA_ADMIN_EMAIL', 'admin@alamiaconnect.com');
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
            File::put($filePath, 'AlamiaConnect is successfully installed via Docker');

            $this->info("Admin created: {$adminEmail}");

            Event::dispatch('AlamiaConnect.installed');
        } catch (\Exception $e) {
            $this->error('Failed to create admin: '.$e->getMessage());
        }
    }
}

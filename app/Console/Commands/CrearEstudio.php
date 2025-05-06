<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CrearEstudio extends Command
{
    protected $signature = 'estudio:crear
                            {nombre : Nombre visible del estudio}
                            {slug : Slug único (ej: estudioabc)}
                            {contacto_nombre}
                            {contacto_email}
                            {contacto_tel?}
                            {--plan_id=1}
                            ';

    protected $description = 'Crea un nuevo estudio, base de datos tenant y usuario admin';

    public function handle()
    {
        $slug = $this->argument('slug');
        $baseDatos = 'tsn_' . $slug;

        // 1. Crear base de datos tenant
        DB::statement("CREATE DATABASE `$baseDatos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->info("Base de datos '$baseDatos' creada");

        // 2. Agregar entrada en tabla estudios (base maestra)
        DB::table('estudios')->insert([
            'nombre' => $this->argument('nombre'),
            'slug' => $slug,
            'base_datos' => $baseDatos,
            'plan_id' => $this->option('plan_id'),
            'contacto_nombre' => $this->argument('contacto_nombre'),
            'contacto_email' => $this->argument('contacto_email'),
            'contacto_tel' => $this->argument('contacto_tel'),
            'fecha_alta' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->info("Estudio registrado en base maestra");

        // 3. Ejecutar migraciones sobre esa base tenant
        config([
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST'),
                'database' => $baseDatos,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        $this->info("Conectado a base tenant '$baseDatos'");

        $this->info("Ejecutando migraciones tenant...");
        Artisan::call('migrate', [
            '--path' => '/database/migrations/tenant',
            '--database' => 'tenant'
        ]);

        // 4. Crear usuario admin@estudio
        DB::connection('tenant')->table('usuarios')->insert([
            'nombre' => 'admin',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("Usuario admin@{$slug} creado con contraseña: admin123");
    }
}

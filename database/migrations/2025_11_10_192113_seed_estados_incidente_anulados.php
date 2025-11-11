<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('estados_incidente')->insert([
            ['nombre' => 'Anulado', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Anulado por error', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Anulado por duplicado', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('estados_incidente')
            ->whereIn('nombre', [
                'Anulado',
                'Anulado por error',
                'Anulado por duplicado'
            ])
            ->delete();
    }
};

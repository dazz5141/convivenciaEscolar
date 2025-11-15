<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // GEO
        $this->call(RegionesSeeder::class);
        $this->call(ProvinciasSeeder::class);
        $this->call(ComunasSeeder::class);

        // Catálogos
        $this->call(DependenciasSeeder::class);
        $this->call(CargosSeeder::class);
        $this->call(TiposContratoSeeder::class);
        $this->call(SexosSeeder::class);
        $this->call(NivelesEmocionalesSeeder::class);
        $this->call(EstadosIncidenteSeeder::class);
        $this->call(EstadosConflictoApoderadoSeeder::class);
        $this->call(EstadosConflictoFuncionarioSeeder::class);
        $this->call(TiposDenunciaLeyKarinSeeder::class);
        $this->call(TiposNovedadSeeder::class);
        $this->call(TiposAsistenciaSeeder::class);
        $this->call(TiposAccidenteSeeder::class);
        $this->call(EstadosCitacionSeeder::class);
        $this->call(TiposMedidaRestaurativaSeeder::class);
        $this->call(EstadosCumplimientoSeeder::class);
        $this->call(TiposProfesionalPIESeeder::class);
        $this->call(TiposIntervencionPIESeeder::class);
        $this->call(EstadoSeguimientoEmocionalSeeder::class);
        $this->call(TiposNovedadInspectoriaSeeder::class);
        $this->call(EstadoConflictoFuncionarioSeeder::class);

        // Roles
        $this->call(RolesSeeder::class);

        // Base institucional
        $this->call(EstablecimientosSeeder::class);
        $this->call(CursosSeeder::class);
        $this->call(FuncionariosSeeder::class);

        // Usuarios
        $this->call(UsuariosSeeder::class);
    }
}

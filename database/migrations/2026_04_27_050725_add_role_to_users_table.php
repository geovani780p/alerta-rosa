<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'contact', 'angel'])->default('user')->after('password');
                  //enum -> significa que el campo solo puede tener uno de estos valores: user,contact o angel,
                  //si alguien intenta guardar otro valor, la base de datos lo rechaza automaticamente
                  //default('user') -> si no se especifica el rol al momento de crear la cuenta en automatico es user
                  //after('password') -> el campo se agrega despues de la columna password en la tabla
                  //dropColumn('role') -> eb el down() eliminamos el campo si hacemos rollback
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

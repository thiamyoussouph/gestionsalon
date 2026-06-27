<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->string('nom_client')->nullable()->after('numero_facture');
            $table->string('reference_paiement')->nullable()->after('mode_paiement');
            $table->string('numero_transaction')->nullable()->after('reference_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn(['nom_client', 'reference_paiement', 'numero_transaction']);
        });
    }
};

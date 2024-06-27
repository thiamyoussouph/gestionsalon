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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture')->unique();
            $table->unsignedBigInteger('user_id'); // Le vendeur
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('montant_recu', 10, 2)->default(0);
            $table->decimal('montant_rendu', 10, 2)->default(0);
            $table->boolean('status')->default(0); // 0 = En attente, 1 = Payée
            $table->string('mode_paiement')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};

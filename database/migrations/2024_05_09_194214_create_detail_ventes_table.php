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
        Schema::create('detail_ventes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vente_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite');
            $table->decimal('prix', 8, 2); // Prix unitaire au moment de la vente
            $table->decimal('total', 10, 2); // Total pour chaque produit
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vente_id')->references('id')->on('ventes')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_ventes');
    }
};

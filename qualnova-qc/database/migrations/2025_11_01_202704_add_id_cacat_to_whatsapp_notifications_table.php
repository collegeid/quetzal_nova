<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cacat')->nullable()->after('id_notif');

            // Jika mau, bisa bikin foreign key ke table data_cacat
            $table->foreign('id_cacat')
                  ->references('id_cacat')
                  ->on('data_cacat')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('whatsapp_notifications', function (Blueprint $table) {
            $table->dropForeign(['id_cacat']);
            $table->dropColumn('id_cacat');
        });
    }
};

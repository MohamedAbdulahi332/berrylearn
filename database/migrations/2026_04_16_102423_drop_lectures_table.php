<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('lectures');
    }

    public function down(): void
    {
        // Intentionally left empty. The historical create migration still defines the table.
    }
};

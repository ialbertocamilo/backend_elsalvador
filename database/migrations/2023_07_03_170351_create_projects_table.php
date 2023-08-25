<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->date('membership_date')->nullable();
            $table->foreignIdFor(User::class);
            $table->string('project_name');
            $table->string('owner_name');
            $table->string('designer_name');
            $table->string('project_director');
            $table->string('address')->nullable();
            $table->string('municipality')->nullable();
            $table->string('energy_advisor')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('levels')->nullable();
            $table->string('offices')->nullable();
            $table->string('surface')->nullable();
            $table->string('extra')->nullable();
            $table->boolean('is_public')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};

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
            $table->foreignIdFor(User::class);
            $table->string('project_name');
            $table->foreignId('reviewer_id')->nullable();
            $table->string('owner_name');
            $table->string('owner_lastname');
            $table->string('profession')->nullable();
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('designer_name');
            $table->string('project_director');
            $table->string('address')->nullable();
            $table->string('municipality')->nullable();
            $table->string('energy_advisor')->nullable();
            $table->string('levels')->nullable();
            $table->string('offices')->nullable();
            $table->string('surface')->nullable();
            $table->string('extra')->nullable();
            $table->integer('building_type')->nullable();
            $table->integer('building_classification')->nullable();
            $table->string('status')->default(0);
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

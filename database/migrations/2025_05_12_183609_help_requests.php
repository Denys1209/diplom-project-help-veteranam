<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\HelpRequestStatus;
use App\Enums\HelpRequestUrgency;

return new class extends Migration
{
    public function up()
    {
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veteran_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('help_categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', array_column(HelpRequestStatus::cases(), 'value'))
            ->default(HelpRequestStatus::PENDING->value);
            $table->enum('urgency', array_column(HelpRequestUrgency::cases(), 'value'))
            ->default(HelpRequestUrgency::MEDIUM->value);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('deadline')->nullable();
            $table->foreignId('volunteer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('help_requests');
    }
};

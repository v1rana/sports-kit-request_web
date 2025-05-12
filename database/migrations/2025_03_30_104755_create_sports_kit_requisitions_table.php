<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('sports_kit_requisitions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('applicant_id'); // Foreign Key
            $table->string('district', 100);
            $table->string('name', 100);
            $table->string('block', 100);
            $table->string('area_name', 100);
            $table->string('designation', 50);
            $table->string('specific_designation', 50);
            $table->json('sports_equipment'); // JSON Column
            $table->string('gram_municipal_signed_document')->nullable();
            $table->enum('verification_status', ['Verified', 'Not Verified']);
            $table->enum('approval_status', ['Approved', 'Rejected']);
            $table->enum('status', ['Pending', 'Verified', 'Not Verified', 'Approved', 'Rejected', 'Disbursed'])->default('Pending');
             //$table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
             $table->foreignId('vendor_id')->nullable()->onDelete('cascade')->constrained('vendors'); 
           // $table->string('vendor_id', 50);
           
            $table->string('not_verify_remarks')->nullable();
            $table->string('reject_remarks')->nullable();
            $table->string('disbursement_status')->nullable();
            $table->timestamp('verification_datetime')->nullable();
            $table->timestamp('approval_rejection_datetime')->nullable();
            $table->timestamp('vendor_assign_date')->nullable();
            $table->timestamps(); // Created at & Updated at
        });
    }

    public function down() {
        Schema::dropIfExists('sports_kit_requisitions');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateH1bsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h1bs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('CASE_NUMBER', 20);
            $table->string('CASE_STATUS')->nullable();
            $table->date('CASE_SUBMITTED')->nullable();
            $table->date('DECISION_DATE')->nullable();
            $table->string('VISA_CLASS', 40)->nullable();
            $table->date('EMPLOYMENT_START_DATE')->nullable();
            $table->date('EMPLOYMENT_END_DATE')->nullable();
            $table->string('EMPLOYER_NAME', 55)->nullable();
            $table->string('EMPLOYER_BUSINESS_DBA')->nullable();
            $table->string('EMPLOYER_ADDRESS')->nullable();
            $table->string('EMPLOYER_CITY')->nullable();
            $table->string('EMPLOYER_STATE')->nullable();
            $table->integer('EMPLOYER_POSTAL_CODE')->nullable();
            $table->string('EMPLOYER_COUNTRY')->nullable();
            $table->string('EMPLOYER_PROVINCE')->nullable();
            $table->string('EMPLOYER_PHONE')->nullable();
            $table->string('EMPLOYER_PHONE_EXT')->nullable();
            $table->string('AGENT_REPRESENTING_EMPLOYER');
            $table->string('AGENT_ATTORNEY_NAME')->nullable();
            $table->string('AGENT_ATTORNEY_CITY')->nullable();
            $table->string('AGENT_ATTORNEY_STATE')->nullable();
            $table->string('JOB_TITLE', 55)->nullable();
            $table->string('SOC_CODE')->nullable();
            $table->string('SOC_NAME')->nullable();
            $table->string('NAICS_CODE')->nullable();
            $table->integer('TOTAL_WORKERS')->nullable();
            $table->integer('NEW_EMPLOYMENT')->nullable();
            $table->integer('CONTINUED_EMPLOYMENT')->nullable();
            $table->integer('CHANGE_PREVIOUS_EMPLOYMENT')->nullable();
            $table->integer('NEW_CONCURRENT_EMPLOYMENT')->nullable();
            $table->integer('CHANGE_EMPLOYER')->nullable();
            $table->integer('AMENDED_PETITION')->nullable();
            $table->string('FULL_TIME_POSITION')->nullable();
            $table->double('PREVAILING_WAGE', 12, 2)->nullable();
            $table->string('PW_UNIT_OF_PAY')->nullable();
            $table->string('PW_WAGE_LEVEL')->nullable();
            $table->string('PW_SOURCE')->nullable();
            $table->year('PW_SOURCE_YEAR')->nullable();
            $table->string('PW_SOURCE_OTHER')->nullable()->nullable();
            $table->double('WAGE_RATE_OF_PAY_FROM', 12, 2)->nullable();
            $table->double('WAGE_RATE_OF_PAY_TO', 12, 2)->nullable();
            $table->string('WAGE_UNIT_OF_PAY')->nullable();
            $table->string('H1B_DEPENDENT')->nullable();
            $table->string('WILLFUL_VIOLATOR')->nullable();
            $table->string('SUPPORT_H1B')->nullable();
            $table->string('LABOR_CON_AGREE')->nullable();
            $table->string('PUBLIC_DISCLOSURE_LOCATION')->nullable();
            $table->string('WORKSITE_CITY', 30)->nullable();
            $table->string('WORKSITE_COUNTY')->nullable();
            $table->string('WORKSITE_STATE')->nullable();
            $table->integer('WORKSITE_POSTAL_CODE')->nullable();
            $table->date('ORIGINAL_CERT_DATE')->nullable();

            $table->index('EMPLOYER_NAME');
            $table->index('VISA_CLASS');
            $table->index('JOB_TITLE');
            $table->index('PREVAILING_WAGE');
            $table->index('WORKSITE_CITY');
            $table->index('DECISION_DATE');
            $table->index(['WORKSITE_CITY', 'PREVAILING_WAGE', 'VISA_CLASS']);
            $table->index(['EMPLOYER_NAME', 'PREVAILING_WAGE', 'VISA_CLASS']);
            $table->index(['JOB_TITLE', 'PREVAILING_WAGE', 'VISA_CLASS']);
        });

        // Full Text Index
        DB::statement('ALTER TABLE h1bs ADD FULLTEXT search_employer (EMPLOYER_NAME)');
        DB::statement('ALTER TABLE h1bs ADD FULLTEXT search_job (JOB_TITLE)');
        DB::statement('ALTER TABLE h1bs ADD FULLTEXT search_city (WORKSITE_CITY)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('h_1bs');
    }
}

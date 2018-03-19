<?php

class TableController extends BaseController
{

    public static function check()
    {
        if (!Schema::hasTable('zone')) {
            TableController::add_zone();
        }
    }

    public static function add_zone()
    {
        Schema::create('zone', function ($table) {
            $table->increments('id');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('zone_json', 100000);
            $table->string('zone_name', 100);
        });
    }

}

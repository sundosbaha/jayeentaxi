<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ZoneType extends Eloquent {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'zone_type';

}
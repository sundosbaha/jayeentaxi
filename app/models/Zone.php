<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Zone extends Eloquent
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'zone';

}
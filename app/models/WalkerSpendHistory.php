<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class WalkerSpendHistory extends Eloquent {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'walker_spend_history';

}

<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class WalkerBankingDetails extends Eloquent {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'walker_banking_details';

}

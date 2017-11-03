<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment as PaymentPaypal;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class DispatcherController extends \BaseController {

public function dispatchuserTripStatus() {
        $id = Request::segment(4);
        $owner_id = Input::get('user_id');
        $request = Requests::where('id', $id)->first();
        if ($request != NULL) {
            $status = 0;

            if ($request->is_walker_rated) {
                $status = 6;
				$response_array=array(
				'status' => $status,
				);
            } elseif ($request->is_completed) {
                $status = 5;
				$response_array=array(
				'status' => $status,
				);
            } elseif ($request->is_started) {
                $status = 4;
				$response_array=array(
				'status' => $status,
				);
            } elseif ($request->is_walker_arrived) {
                $status = 3;
				$response_array=array(
				'status' => $status,
				);
            } elseif ($request->is_walker_started) {
                $status = 2;
				$response_array=array(
				'status' => $status,
				);
            } elseif ($request->confirmed_walker) {
                $status = 1;
				$walker=DB::table('walker')->where('id', $request->confirmed_walker)->first();
				$response_array=array(
				'status' => $status,
				'walkerId' => $walker->id,
				'walkerName' => $walker->first_name.' '.$walker->last_name,
				'walkerPhone' => $walker->phone,
				'walkerEmail' => $walker->email,
				'walkerPic' => $walker->picture,
				);
				
            }
			
           $response=Response::json($response_array);
				return $response;
        }
    }	
}
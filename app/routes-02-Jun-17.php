<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */


Route::get('admin/wallet/information', array('as' => 'AdminWalletInformation', 'uses' => 'AdminController@wallet_information'));
Route::post('admin/wallet/information', array('as' => 'AdminWalletInformation', 'uses' => 'AdminController@update_wallet_information'));
Route::get('admin/wallet/user_information', array('as' => 'AdminWalletUserInformation', 'uses' => 'AdminController@user_wallet_information'));


Route::post('user/add_wallet', 'DogController@add_wallet_amt');
Route::post('user/get_amt', 'DogController@get_wallet_amount');




Route::get('/package_installer', 'AdminController@package_installer');
Route::post('/package_installer', 'AdminController@package_installer1');

Route::get('/test', 'HelloController@test');

Route::get('/push', 'ApplicationController@test_notifi');

Route::get('/payms', 'HelloController@payms');

Route::post('/dog/addschedule', 'DogController@add_schedule');

Route::post('/dog/getUserSchedules', 'DogController@get_user_schedule');

Route::post('/dog/cancelschedule', 'DogController@cancel_schedule');

Route::get('/dog/getwalkers', 'DogController@get_walkers');

Route::post('/dog/assignwalker', 'DogController@assign_walker');

Route::get('/walk/walkinprogress', 'DogController@walkinprogress');

Route::get('/walk/nonreviewedwalks', 'DogController@nonreviewedwalks');

Route::get('/walks', 'DogController@get_walks');

Route::post('/walk/walksummary', 'WalkerController@walk_summary');

Route::post('/walk/photo', 'WalkerController@upload_photo');

Route::post('/walk/video', 'WalkerController@upload_video');

Route::get('/walker/walks', 'WalkerController@get_walks');

Route::get('/walker/details', 'WalkerController@get_details');

Route::post('/walker/cancelwalk', 'WalkerController@cancel_walk');

Route::post('/walker/getwalk', 'WalkerController@walk_details');

Route::post('/walker/getschedule', 'WalkerController@get_schedule');


// On Demand API's
// Owner APIs

Route::get('/user/report', 'DogController@auto_user_report');

Route::post('/user/testapi', 'OwnerController@testapi');

Route::post('/user/login', 'OwnerController@login');

Route::post('/user/register', 'OwnerController@register');

Route::get('/test/pushtest', 'DogController@testpush');

//testing

Route::post('/user/checkotp', 'OwnerController@checkotp');
Route::post('/user/resendotp', 'OwnerController@otpresend');
Route::post('/user/temp_user', 'OwnerController@deleteuser');
//testing

Route::post('/user/location', 'DogController@set_location');

Route::any('/user/details', 'OwnerController@details');

Route::post('/user/addcardtoken', 'OwnerController@addcardtoken');

Route::get('/user/braintreekey', 'OwnerController@get_braintree_token');


Route::post('/user/deletecardtoken', 'OwnerController@deletecardtoken');

Route::post('/user/update', 'OwnerController@update_profile');

Route::post('/user/paydebt', 'OwnerController@pay_debt');

Route::post('/user/selectcard', 'OwnerController@select_card');

Route::post('/user/card_selection', 'OwnerController@card_selection');

Route::get('/user', 'OwnerController@getProfile');

Route::any('/user/thing', 'DogController@create');

Route::post('/user/updatething', 'DogController@update_thing');

Route::post('/user/createrequest', 'DogController@create_request');

//testing
Route::post('/user/create_scheduled_request', 'DogController@createScheduled_request');
//testing

Route::post('/user/payment_type', 'OwnerController@payment_type');


Route::post('/user/createrequestlater', 'DogController@create_request_later');

/* Route::post('/user/getproviders', 'DogController@get_providers'); */
Route::post('/user/getproviders', 'DogController@get_providers_old');

Route::post('/user/getproviders_new', 'DogController@get_providers');


Route::post('/user/getprovidersall', 'DogController@get_providers_all');

Route::post('/user/getnearbyproviders', 'DogController@get_nearby_providers');

Route::post('/user/createrequestproviders', 'DogController@create_request_providers');


Route::post('/provider/cancellation', 'WalkerController@cancellation');

Route::post('/user/cancellation', 'DogController@cancellation');

Route::get('/user/getrequest', 'DogController@get_request');

Route::post('/user/cancelrequest', 'DogController@cancel_request');

Route::get('/server/schedulerequest', 'DogController@schedule_request');

Route::get('/user/getrequestlocation', 'DogController@get_request_location');

Route::post('/user/rating', 'DogController@set_walker_rating');


Route::get('/user/requestinprogress', 'DogController@request_in_progress');

Route::get('/user/requestpath', 'DogController@get_walk_location');

Route::get('/provider/requestpath', 'WalkerController@get_walk_location');

Route::post('/user/referral', 'OwnerController@set_referral_code');

Route::get('/user/referral', 'OwnerController@get_referral_code');

Route::post('/user/apply-referral', 'OwnerController@apply_referral_code');
Route::post('/user/apply-promo', 'OwnerController@apply_promo_code');

Route::get('/user/cards', 'OwnerController@get_cards');

Route::get('/user/history', 'OwnerController@get_completed_requests');


Route::post('/user/paybypaypal', 'OwnerController@paybypaypal');

Route::post('/user/paybybitcoin', 'OwnerController@paybybitcoin');

Route::post('/user/acceptbitcoin', 'OwnerController@acceptbitcoin');

Route::get('/user/send_eta', 'OwnerController@send_eta');

Route::get('/user/current_eta', 'DogController@eta');

Route::get('/user/credits', 'OwnerController@get_credits');

Route::get('/user/payment_options', array('as' => '/user/payment_options', 'uses' => 'OwnerController@payment_options_allowed'));

Route::get('/user/check_promo_code', 'DogController@check_promo_code');

Route::post('/user/logout', 'OwnerController@logout');

Route::post('/user/payment_select', 'DogController@payment_select');

Route::post('/user/provider_list', 'DogController@get_provider_list');

//under-testing
Route::post('/user/allprovider_list', 'DogController@get_allprovider_list');
Route::post('/user/deleteuser', 'OwnerController@deleteuser');
Route::get('/user/splash', 'OwnerController@splash');
//under-testing

Route::post('/user/setdestination', 'DogController@user_set_destination');

Route::get('/provider/check_banking', 'WalkerController@check_banking');


// Walker APIs
Route::post('/provider/createrequest', 'WalkerController@create_request');

Route::get('/provider/getwalkerdisable', 'WalkerController@get_walker_disable');

Route::get('/provider/getrequests', 'WalkerController@get_requests');

Route::get('/provider/getrequest', 'WalkerController@get_request');

Route::post('/provider/respondrequest', 'WalkerController@respond_request');

Route::post('/provider/location', 'WalkerController@walker_location');

Route::post('/provider/requestwalkerstarted', 'WalkerController@request_walker_started');

Route::post('/provider/requestwalkerarrived', 'WalkerController@request_walker_arrived');

Route::post('/provider/requestwalkstarted', 'WalkerController@request_walk_started');

Route::post('/request/location', 'WalkerController@walk_location');

Route::post('/provider/requestwalkcompleted', 'WalkerController@request_walk_completed');

Route::post('/provider/prepayment', 'WalkerController@pre_payment');

Route::post('/provider/paymentselection', 'WalkerController@payment_selection');

Route::post('/provider/rating', 'WalkerController@set_dog_rating');

Route::post('/provider/login', 'WalkerController@login');

Route::post('/provider/register', 'WalkerController@register');

Route::post('/provider/update', 'WalkerController@update_profile');

Route::post('/provider_services/update', 'WalkerController@provider_services_update');

Route::get('/provider/services_details', 'WalkerController@services_details');

Route::get('/provider/requestinprogress', 'WalkerController@request_in_progress');

Route::get('/provider/checkstate', 'WalkerController@check_state');

Route::post('/provider/togglestate', 'WalkerController@toggle_state');

Route::get('/provider/history', 'WalkerController@get_completed_requests');

Route::post('panic', array('as' => 'panic', 'uses' => 'WalkerController@panic'));

Route::post('/provider/logout', 'WalkerController@logout');

Route::get('/user/splash', 'OwnerController@splash');

// Info Page API

Route::get('/application/pages', 'ApplicationController@pages');

Route::get('/application/types', 'ApplicationController@types');

Route::post('/application/alltypes', 'ApplicationController@all_types');

Route::get('/application/page/{id}', 'ApplicationController@get_page');

Route::post('/application/forgot-password', 'ApplicationController@forgot_password');

// Admin Panel
Route::get('/admin/report', array('as' => 'AdminReport', 'uses' => 'AdminController@report'));

Route::get('/admin/payprovider/{id}', array('as' => 'AdminPayProvider', 'uses' => 'AdminController@pay_provider'));

Route::get('/admin/chargeuser/{id}', array('as' => 'AdminChargeUser', 'uses' => 'AdminController@charge_user'));

Route::get('/admin/addreq/{id}', array('as' => 'AdminAddRequest', 'uses' => 'AdminController@add_request'));

Route::post('/admin/transfer_amount', array('as' => 'AdminProviderPay', 'uses' => 'AdminController@transfer_amount'));

Route::get('/admin/map_view', array('as' => 'AdminMapview', 'uses' => 'AdminController@map_view'));

Route::get('/admin/providers', array('as' => 'AdminProviders', 'uses' => 'AdminController@walkers'));

Route::get('/admin/users', array('as' => 'AdminUsers', 'uses' => 'AdminController@owners'));

Route::get('/admin/requests', array('as' => 'AdminRequests', 'uses' => 'AdminController@walks'));

Route::get('/admin/reviews', array('as' => 'AdminReviews', 'uses' => 'AdminController@reviews'));

Route::get('/admin/reviews/delete/{id}', array('as' => 'AdminReviewsDelete', 'uses' => 'AdminController@delete_review'));
Route::get('/admin/reviews/delete_client/{id}', array('as' => 'AdminReviewsDeleteDog', 'uses' => 'AdminController@delete_review_owner'));

Route::get('/admin/search', array('as' => 'AdminSearch', 'uses' => 'AdminController@search'));

Route::get('/admin/login', array('as' => 'AdminLogin', 'uses' => 'AdminController@login'));

Route::post('/admin/verify', array('as' => 'AdminVerify', 'uses' => 'AdminController@verify'));

Route::get('/admin/logout', array('as' => 'AdminLogout', 'uses' => 'AdminController@logout'));


Route::get('/admin/admins', array('as' => 'AdminAdmins', 'uses' => 'AdminController@admins'));

Route::get('/admin/add_admin', array('as' => 'AdminAddAdmin', 'uses' => 'AdminController@add_admin'));

Route::get('/admin/user/referral/{id}', array('as' => 'AdminUserReferral', 'uses' => 'AdminController@referral_details'));

Route::post('/admin/admins/add', array('as' => 'AdminAdminsAdd', 'uses' => 'AdminController@add_admin_do'));

Route::get('/admin/admins/edit/{id}', array('as' => 'AdminAdminsEdit', 'uses' => 'AdminController@edit_admins'));

Route::post('/admin/admins/update', array('as' => 'AdminAdminsUpdate', 'uses' => 'AdminController@update_admin'));

Route::get('/admin/admins/delete/{id}', array('as' => 'AdminAdminsDelete', 'uses' => 'AdminController@delete_admin'));



Route::get('/admin', array('as' => 'admin', 'uses' => 'AdminController@index'));

Route::get('/admin/privilege/{id}', array('as' => 'adminprivilege', 'uses' => 'AdminController@privilege'));
Route::post('/admin/addprivilege', array('as' => 'adminaddprivilege', 'uses' => 'AdminController@addprivilege'));
Route::get('/admin/listuser', array('as' => 'listusers', 'uses' => 'AdminController@get_all_users'));

Route::get('/admin/add', array('as' => 'AdminAdd', 'uses' => 'AdminController@add'));

Route::get('/admin/savesetting', array('as' => 'AdminSettingDontShow', 'uses' => 'AdminController@skipSetting'));


Route::get('/admin/provider/edit/{id}', array('as' => 'AdminProviderEdit', 'uses' => 'AdminController@edit_walker'));



Route::get('/admin/provider/edit/availability/{id}', array('as' => 'provider_availabilty', 'uses' => 'AdminController@provider_availabilty'));

Route::get('/admin/provider/add', array('as' => 'AdminProviderAdd', 'uses' => 'AdminController@add_walker'));

Route::get('/admin/promo_code/add', array('as' => 'AdminPromoAdd', 'uses' => 'AdminController@add_promo_code'));

Route::get('/admin/promo_code/edit/{id}', array('as' => 'AdminPromoCodeEdit', 'uses' => 'AdminController@edit_promo_code'));

Route::get('/admin/promo_code/deactivate/{id}', array('as' => 'AdminPromoCodeDeactivate', 'uses' => 'AdminController@deactivate_promo_code'));

Route::get('/admin/promo_code/activate/{id}', array('as' => 'AdminPromoCodeActivate', 'uses' => 'AdminController@activate_promo_code'));

Route::post('/admin/provider/update', array('as' => 'AdminProviderUpdate', 'uses' => 'AdminController@update_walker'));

Route::post('/admin/promo_code/update', array('as' => 'AdminPromoUpdate', 'uses' => 'AdminController@update_promo_code'));

Route::get('/admin/provider/history/{id}', array('as' => 'AdminProviderHistory', 'uses' => 'AdminController@walker_history'));

Route::get('/admin/provider/documents/{id}', array('as' => 'AdminProviderDocuments', 'uses' => 'AdminController@walker_documents'));

Route::get('/admin/provider/requests/{id}', array('as' => 'AdminProviderRequests', 'uses' => 'AdminController@walker_upcoming_walks'));

Route::get('/admin/provider/decline/{id}', array('as' => 'AdminProviderDecline', 'uses' => 'AdminController@decline_walker'));

Route::get('/admin/provider/delete/{id}', array('as' => 'AdminProviderDelete', 'uses' => 'AdminController@delete_walker'));

Route::get('/admin/provider/approve/{id}', array('as' => 'AdminProviderApprove', 'uses' => 'AdminController@approve_walker'));


//testing
Route::get('/admin/provider/available/{id}', array('as' => 'AdminProviderAvailable', 'uses' => 'AdminController@change_status_walker'));
//testing

Route::get('/admin/providers_xml', array('as' => 'AdminProviderXml', 'uses' => 'AdminController@walkers_xml'));


Route::get('/admin/user/delete/{id}', array('as' => 'AdminDeleteUser', 'uses' => 'AdminController@delete_owner'));

Route::get('/admin/user/edit/{id}', array('as' => 'AdminUserEdit', 'uses' => 'AdminController@edit_owner'));

Route::post('/admin/user/update', array('as' => 'AdminUserUpdate', 'uses' => 'AdminController@update_owner'));

Route::get('/admin/user/history/{id}', array('as' => 'AdminUserHistory', 'uses' => 'AdminController@owner_history'));

Route::get('/admin/user/requests/{id}', array('as' => 'AdminUserRequests', 'uses' => 'AdminController@owner_upcoming_walks'));

Route::get('/admin/request/decline/{id}', array('as' => 'AdminUserDecline', 'uses' => 'AdminController@decline_walk'));

Route::get('/admin/request/approve/{id}', array('as' => 'AdminUserApprove', 'uses' => 'AdminController@approve_walk'));

Route::get('/admin/request/map/{id}', array('as' => 'AdminRequestsMap', 'uses' => 'AdminController@view_map'));

Route::get('/admin/request/change_provider/{id}', array('as' => 'AdminRequestChangeProvider', 'uses' => 'AdminController@change_walker'));

Route::get('/admin/request/alternative_providers_xml/{id}', array('as' => 'AdminRequestsAlternative', 'uses' => 'AdminController@alternative_walkers_xml'));

Route::post('/admin/request/change_provider', array('as' => 'AdminRequestChange', 'uses' => 'AdminController@save_changed_walker'));

Route::post('/admin/request/pay_provider', array('as' => 'AdminRequestPay', 'uses' => 'AdminController@pay_walker'));

Route::get('/admin/settings', array('as' => 'AdminSettings', 'uses' => 'AdminController@get_settings'));

Route::get('/admin/settings/installation', array('as' => 'AdminSettingInstallation', 'uses' => 'AdminController@installation_settings'));

Route::post('/admin/install', array('as' => 'AdminInstallFinish', 'uses' => 'AdminController@finish_install'));

Route::post('/admin/certi', array('as' => 'AdminAddCerti', 'uses' => 'AdminController@addcerti'));

Route::post('/admin/theme', array('as' => 'AdminTheme', 'uses' => 'AdminController@theme'));

Route::post('/admin/settings', array('as' => 'AdminSettingsSave', 'uses' => 'AdminController@save_settings'));

Route::get('/admin/informations', array('as' => 'AdminInformations', 'uses' => 'AdminController@get_info_pages'));

Route::get('/admin/information/edit/{id}', array('as' => 'AdminInformationEdit', 'uses' => 'AdminController@edit_info_page'));

Route::post('/admin/information/update', array('as' => 'AdminInformationUpdate', 'uses' => 'AdminController@update_info_page'));

Route::get('/admin/information/delete/{id}', array('as' => 'AdminInformationDelete', 'uses' => 'AdminController@delete_info_page'));

Route::get('/admin/provider-types', array('as' => 'AdminProviderTypes', 'uses' => 'AdminController@get_provider_types'));

Route::get('/admin/provider-type/edit/{id}', array('as' => 'AdminProviderTypeEdit', 'uses' => 'AdminController@edit_provider_type'));

Route::post('/admin/provider-type/update', array('as' => 'AdminProviderTypeUpdate', 'uses' => 'AdminController@update_provider_type'));

Route::get('/admin/provider-type/delete/{id}', array('as' => 'AdminProviderTypeDelete', 'uses' => 'AdminController@delete_provider_type'));

Route::get('/admin/document-types', array('as' => 'AdminDocumentTypes', 'uses' => 'AdminController@get_document_types'));

Route::get('/admin/promo_code', array('as' => 'AdminPromoCodes', 'uses' => 'AdminController@get_promo_codes'));

Route::get('/admin/promo_code', array('as' => 'AdminPromoCodes', 'uses' => 'AdminController@get_promo_codes'));

Route::get('/admin/edit_keywords', array('as' => 'AdminKeywords', 'uses' => 'AdminController@edit_keywords'));

Route::post('/admin/save_keywords', array('as' => 'AdminKeywordsSave', 'uses' => 'AdminController@save_keywords'));

Route::post('/admin/save_keywords_ui', array('as' => 'AdminUIKeywordsSave', 'uses' => 'AdminController@save_keywords_UI'));

Route::get('/admin/document-type/edit/{id}', array('as' => 'AdminDocumentTypesEdit', 'uses' => 'AdminController@edit_document_type'));

Route::post('/admin/document-type/update', array('as' => 'AdminDocumentTypesUpdate', 'uses' => 'AdminController@update_document_type'));

Route::get('/admin/document-type/delete/{id}', array('as' => 'AdminDocumentTypesDelete', 'uses' => 'AdminController@delete_document_type'));

Route::post('/admin/adminCurrency', array('as' => 'adminCurrency', 'uses' => 'AdminController@adminCurrency'));

Route::get('/admin/details_payment', array('as' => 'AdminPayment', 'uses' => 'AdminController@payment_details'));



Route::get('/admin/provider/banking/{id}', array('as' => 'AdminProviderBanking', 'uses' => 'AdminController@banking_provider'));

Route::post('/admin/provider/providerB_bankingSubmit', array('as' => 'AdminProviderBBanking', 'uses' => 'AdminController@providerB_bankingSubmit'));

Route::post('/admin/provider/providerS_bankingSubmit', array('as' => 'AdminProviderSBanking', 'uses' => 'AdminController@providerS_bankingSubmit'));

Route::post('admin/add-request', array('as' => 'adminmanualrequest', 'uses' => 'AdminController@create_manual_request'));


//zone divison
Route::get('admin/zonedivision', array('as' => 'ZoneDivision', 'uses' => 'AdminController@zonedivision'));







//Admin Panel Sorting 

Route::get('/admin/sortur', array('as' => '/admin/sortur', 'uses' => 'AdminController@sortur'));

Route::get('/admin/sortpv', array('as' => '/admin/sortpv', 'uses' => 'AdminController@sortpv'));

Route::get('/admin/sortpvtype', array('as' => '/admin/sortpvtype', 'uses' => 'AdminController@sortpvtype'));

Route::get('/admin/sortreq', array('as' => '/admin/sortreq', 'uses' => 'AdminController@sortreq'));

Route::get('/admin/sortpromo', array('as' => '/admin/sortpromo', 'uses' => 'AdminController@sortpromo'));

//Provider Availability

Route::get('/admin/provider/allow_availability', array('as' => 'AdminProviderAllowAvailability', 'uses' => 'AdminController@allow_availability'));

Route::get('/admin/provider/disable_availability', array('as' => 'AdminProviderDisableAvailability', 'uses' => 'AdminController@disable_availability'));

Route::get('/admin/provider/availability/{id}', array('as' => 'AdminProviderAvailability', 'uses' => 'AdminController@availability_provider'));

Route::post('/admin/provider/availabilitySubmit/{id}', array('as' => 'AdminProviderAvailabilitySubmit', 'uses' => 'AdminController@provideravailabilitySubmit'));

Route::get('/admin/provider/view_documents/{id}', array('as' => 'AdminViewProviderDoc', 'uses' => 'AdminController@view_documents_provider'));

//Providers Who currently walking

Route::get('/admin/provider/current', array('as' => 'AdminProviderCurrent', 'uses' => 'AdminController@current'));


// Web User

Route::get('/', 'WebController@index');

Route::get('/user/signin', array('as' => '/user/signin', 'uses' => 'WebUserController@userLogin'));

Route::get('/user/signup', array('as' => '/user/signup', 'uses' => 'WebUserController@userRegister'));

Route::post('/user/save', array('as' => '/user/save', 'uses' => 'WebUserController@userSave'));

Route::post('/user/forgot-password', array('as' => '/user/forgot-password', 'uses' => 'WebUserController@userForgotPassword'));

Route::get('/user/logout', array('as' => '/user/logout', 'uses' => 'WebUserController@userLogout'));

Route::post('/user/verify', array('as' => '/user/verify', 'uses' => 'WebUserController@userVerify'));

Route::get('/user/trips', array('as' => '/user/trips', 'uses' => 'WebUserController@userTrips'));

Route::get('/user/trip/status/{id}', array('as' => '/user/trip/status', 'uses' => 'WebUserController@userTripStatus'));

//testing
Route::get('/user/dispatchertrip/status/{id}', array('as' => '/user/trip1/status', 'uses' => 'DispatcherController@dispatchuserTripStatus'));
//testing


Route::get('/user/trip/cancel/{id}', array('as' => '/user/trip/cancel', 'uses' => 'WebUserController@userTripCancel'));

Route::get('/find', array('as' => '/find', 'uses' => 'WebUserController@surroundingCars'));


Route::get('user/paybypaypal/{id}', array('as' => 'user/paybypaypal', 'uses' => 'WebUserController@webpaybypaypal'));

Route::get('user/paybypalweb/{id}', array('as' => 'user/paybypalweb', 'uses' => 'WebUserController@paybypalwebSubmit'));

Route::get('userpaypalstatus', array('as' => 'userpaypalstatus', 'uses' => 'WebUserController@paypalstatus'));

Route::get('userpaypalipn', array('as' => 'userpaypalipn', 'uses' => 'WebUserController@userpaypalipn'));


Route::get('/user/request-trip', array('as' => 'userrequestTrip', 'uses' => 'WebUserController@userRequestTrip'));

Route::get('/user/skipReview/{id}', array('as' => 'userSkipReview', 'uses' => 'WebUserController@userSkipReview'));


Route::post('/user/eta', array('as' => 'etaweb', 'uses' => 'WebUserController@send_eta_web'));

Route::get('/user/request-fare', array('as' => 'userrequestFare', 'uses' => 'WebUserController@request_fare'));

Route::get('/user/requesteta', array('as' => 'userrequestETA', 'uses' => 'WebUserController@request_eta'));

Route::post('/user/request-trip', array('as' => 'userrequesttrips', 'uses' => 'WebUserController@saveUserRequestTrip'));

Route::post('/user/post-review', array('as' => '/user/post-review', 'uses' => 'WebUserController@saveUserReview'));

Route::get('/user/profile', array('as' => '/user/profile', 'uses' => 'WebUserController@userProfile'));

Route::get('/user/payments', array('as' => 'userPayment', 'uses' => 'WebUserController@userPayments'));


Route::get('termsncondition', array('as' => 'termsncondition', 'uses' => 'WebController@termsncondition'));

Route::get('banking_provider_mobile/{id}', array('as' => 'banking_provider_mobile', 'uses' => 'WebController@banking_provider_mobile'));

Route::post('provider/provider_braintree_banking', array('as' => 'ProviderBBanking', 'uses' => 'WebController@providerB_bankingSubmit'));

Route::post('provider/provider_stripe_banking', array('as' => 'ProviderSBanking', 'uses' => 'WebController@providerS_bankingSubmit'));


Route::get('page/{title}', array('as' => 'page', 'uses' => 'WebController@page'));

Route::get('track/{id}', array('as' => 'track', 'uses' => 'WebController@track_ride'));

Route::get('get_track_loc/{id}', array('as' => 'getTrackLoc', 'uses' => 'WebController@get_track_loc'));


Route::post('/user/payments', array('as' => 'userpayments', 'uses' => 'WebUserController@saveUserPayment'));


Route::get('/user/payment/delete/{id}', array('as' => '/user/payment/delete', 'uses' => 'WebUserController@deleteUserPayment'));

Route::post('/user/update_profile', array('as' => '/user/update_profile', 'uses' => 'WebUserController@updateUserProfile'));

Route::post('/user/update_password', array('as' => '/user/update_password', 'uses' => 'WebUserController@updateUserPassword'));

Route::post('/user/update_code', array('as' => '/user/update_code', 'uses' => 'WebUserController@updateUserCode'));

Route::get('/user/trip/{id}', array('as' => '/user/trip', 'uses' => 'WebUserController@userTripDetail'));


// Search Admin Panel
Route::get('/admin/searchpv', array('as' => '/admin/searchpv', 'uses' => 'AdminController@searchpv'));
Route::get('/admin/searchur', array('as' => '/admin/searchur', 'uses' => 'AdminController@searchur'));
Route::get('/admin/searchreq', array('as' => '/admin/searchreq', 'uses' => 'AdminController@searchreq'));
Route::get('/admin/searchrev', array('as' => '/admin/searchrev', 'uses' => 'AdminController@searchrev'));
Route::get('/admin/searchinfo', array('as' => '/admin/searchinfo', 'uses' => 'AdminController@searchinfo'));
Route::get('/admin/searchpvtype', array('as' => '/admin/searchpvtype', 'uses' => 'AdminController@searchpvtype'));
Route::get('/admin/searchdoc', array('as' => '/admin/searchdoc', 'uses' => 'AdminController@searchdoc'));
Route::get('/admin/searchpromo', array('as' => '/admin/searchpromo', 'uses' => 'AdminController@searchpromo'));


 
//dispatcher

Route::any('/dispatch/login', array('as' => 'DispatchLogin', 'uses' => 'DispaController@login'));

Route::get('/dispatch/booking', array('as' => 'DispatchBooking', 'uses' => 'DispaController@booking'));

Route::get('/dispatch/tracking', array('as' => 'DispatchTracking', 'uses' => 'DispaController@tracking'));

Route::post('/dispatch/userdetail', array('as' => 'DispatchUserdetail', 'uses' => 'DispaController@user_detail'));

Route::post('/disptach/providers_xml', array('as' => 'DispatchProviderXml', 'uses' => 'DispaController@walkers_xml'));

Route::post('/disptach/all_providers_xml', array('as' => 'DispatchallProviderXml', 'uses' => 'DispaController@all_walkers_xml'));

Route::post('/dispatch/createtrip', array('as' => 'DispatchCreatetrip', 'uses' => 'DispaController@create_trip'));

Route::post('/dispatch/manualdriver', array('as' => 'DispatchManualdriver', 'uses' => 'DogController@dispatch_manual_request'));

Route::post('/dispatch/setdriver', array('as' => 'DispatchSetdriver', 'uses' => 'DispaController@set_driver_request'));

Route::post('/dispatch/canceltrip', array('as' => 'DispatchCanceltrip', 'uses' => 'DispaController@canceltrip'));

Route::post('/dispatch/statustrip', array('as' => 'DispatchStatustrip', 'uses' => 'DispaController@statustrip'));

Route::post('/dispatch/schedule', array('as' => 'DispatchSchedule', 'uses' => 'DispaController@schedule_trip'));

Route::get('/dispatch/schedule_view', array('as' => 'DispatchScheduleview', 'uses' => 'DispaController@schedule_view'));

Route::post('/dispatch/dispatch_eta', array('as' => 'Dispatcheta', 'uses' => 'DispaController@eta_type'));

Route::get('/dispatch/user', array('as' => 'DispatchUser', 'uses' => 'DispaController@user'));

Route::post('/dispatch/chckuser', array('as' => 'DispatchcheckUser', 'uses' => 'DispaController@chckuser'));

Route::post('/dispatch/action', array('as' => 'DispatchAction', 'uses' => 'DispaController@action'));

Route::post('/dispatch/editaction', array('as' => 'DispatcheditAction', 'uses' => 'DispaController@edit_action'));

Route::get('/dispatch/edit/{id}', array('as' => 'Dispatchedit', 'uses' => 'DispaController@useredit'));

Route::get('/dispatch/setting', array('as' => 'Dispatchsetting', 'uses' => 'DispaController@settings'));

Route::get('/dispatch/logout', array('as' => 'Dispatchlogout', 'uses' => 'DispaController@logout'));

Route::get('/dispatch/cron_job', array('as' => 'Dispatchlogout', 'uses' => 'DispaController@cron_job_schdule'));
//dispatcher








// Web Provider

Route::get('/provider/signin', array(
    'as' => 'ProviderSignin',
    'uses' => 'WebProviderController@providerLogin'
));

Route::get('/provider/activation/{act}', array(
    'as' => '/provider/activation',
    'uses' => 'WebProviderController@providerActivation'
));



Route::get('/provider/signup', array(
    'as' => 'ProviderSignup',
    'uses' => 'WebProviderController@providerRegister'
));


Route::post('/provider/save', array(
    'as' => 'ProviderSave',
    'uses' => 'WebProviderController@providerSave'
));


Route::get('/provider/availability', array(
    'as' => 'ProviderAvail',
    'uses' => 'WebProviderController@provideravailability'
));

Route::post('/provider/availabilitysubmit', array(
    'as' => 'provideravailabilitySubmit',
    'uses' => 'WebProviderController@provideravailabilitysubmit'
));



Route::post('/provider/forgot-password', array(
    'as' => 'providerForgotPassword',
    'uses' => 'WebProviderController@providerForgotPassword'));

Route::get('/provider/logout', array(
    'as' => 'ProviderLogout',
    'uses' => 'WebProviderController@providerLogout'
));


Route::post('/provider/verify', array(
    'as' => 'ProviderVerify',
    'uses' => 'WebProviderController@providerVerify'
));



Route::get('/provider/trips', array(
    'as' => 'ProviderTrips',
    'uses' => 'WebProviderController@providerTrips'
));


Route::get('/provider/trip/{id}', array(
    'as' => 'ProviderTripDetail',
    'uses' => 'WebProviderController@providerTripDetail'
));


Route::get('/provider/trip/changestate/{id}', array(
    'as' => 'providerTripChangeState',
    'uses' => 'WebProviderController@providerTripChangeState'
));


Route::get('/provider/tripinprogress', array(
    'as' => 'providerTripInProgress',
    'uses' => 'WebProviderController@providerTripInProgress'));

Route::get('/provider/skipReview', array(
    'as' => 'providerSkipReview',
    'uses' => 'WebProviderController@providerSkipReview'));

Route::get('/provider/profile', array(
    'as' => 'providerProfile',
    'uses' => 'WebProviderController@providerProfile'));

Route::post('/provider/update_profile', array(
    'as' => 'updateProviderProfile',
    'uses' => 'WebProviderController@updateProviderProfile'));

Route::post('/provider/update_password', array(
    'as' => 'updateProviderPassword',
    'uses' => 'WebProviderController@updateProviderPassword'));

Route::get('/provider/documents', array(
    'as' => 'providerDocuments',
    'uses' => 'WebProviderController@providerDocuments'));

Route::post('/provider/update_documents', array(
    'as' => 'providerUpdateDocuments',
    'uses' => 'WebProviderController@providerUpdateDocuments'));

Route::get('/provider/request', array(
    'as' => 'providerRequestPing',
    'uses' => 'WebProviderController@providerRequestPing'));

Route::post('user/request', array('as' => 'manualrequest', 'uses' => 'WebProviderController@create_manual_request'));

Route::get('/provider/request/decline/{id}', 'WebProviderController@decline_request');

Route::get('/provider/request/accept/{id}', 'WebProviderController@approve_request');

Route::post('provider/get-nearby', array('as' => 'nearby', 'uses' => 'WebProviderController@get_nearby'));

Route::any('/provider/availability/toggle', array(
    'as' => 'toggle_availability',
    'uses' => 'WebProviderController@toggle_availability'));

//Route::any('/provider/location/set', array(
// 		'as' => 'providerLocation',
//		'uses' =>'WebProviderController@set_location'));

Route::any('/provider/location/set', 'WebProviderController@set_location');

// Installer
//Route::any('/install', 'InstallerController@install');


Route::any('install', array('as' => 'install', 'uses' => 'InstallerController@install'))->before('new_installation');

Route::get('/install/complete', 'InstallerController@finish_install');

Route::post('user/fare', 'DogController@fare_calculator');

Route::get('token_braintree', array('as' => 'token_braintree', 'uses' => 'ApplicationController@token_braintree'));

//zone divison

Route::get('admin/zonedivisions', array('as' => 'ZoneDivisions', 'uses' => 'AdminController@zonedivisions'));
Route::get('admin/zonedivision', array('as' => 'ZoneDivision', 'uses' => 'AdminController@zonedivision'));
Route::get('admin/zone_edit_view/{id}', array('as' => 'EditViewZoneDivision', 'uses' => 'AdminController@zone_division_edit_view'));
Route::post('admin/zone_edit', array('as' => 'EditZoneDivision', 'uses' => 'AdminController@zone_division_edit'));
Route::post('admin/zone_division_add', array('as' => 'AddZoneDivision', 'uses' => 'AdminController@zone_division_add'));
Route::get('admin/zone_delete/{id}', array('as' => 'DeleteZoneDivision', 'uses' => 'AdminController@zone_division_delete'));

Route::get('admin/zone_view/{id}', array('as' => 'ViewZoneDivision', 'uses' => 'AdminController@zone_division_view'));
Route::post('admin/zone_map_view', array('as' => 'MapViewZone', 'uses' => 'AdminController@zone_map_view'));


Route::get('/admin/user_register', array('as' => 'userRegister', 'uses' => 'AdminController@user_register'));
Route::post('/admin/user_register_save', array('as' => 'userRegisterSave', 'uses' => 'AdminController@user_register_save'));

Route::get('/admin/provider_register', array('as' => 'providerRegister', 'uses' => 'AdminController@provider_register'));
Route::post('/admin/provider_register_save', array('as' => 'providerRegisterSave', 'uses' => 'AdminController@provider_register_save'));

Route::post('/provider/walk_save', 'WalkerController@walk_save');

Route::get('/provider/walk_list', 'WalkerController@walk_list');

Route::post('/provider/provider_history', 'WalkerController@providerCompletedTripHistory');

Route::get('/admin/driver_earning', array('as' => 'DriverEarnings', 'uses' => 'AdminController@driver_earnings'));

Route::post('/provider/get_item', 'WalkerController@get_item');

Route::get('/admin/peak-types', array('as' => 'AdminPeakTypes', 'uses' => 'AdminController@get_peak_types'));

Route::get('admin/peak_edit_view/{id}', array('as' => 'EditPeakTypes', 'uses' => 'AdminController@peak_edit_view'));
Route::post('admin/peak_edit', array('as' => 'EditPeak', 'uses' => 'AdminController@peak_edit'));

Route::post('/user/tips', array('as' => 'OwnerTips', 'uses' => 'OwnerController@tips'));

Route::get('/admin/document_image_edit', array('as' => 'DocumentImageEdit', 'uses' => 'AdminController@document_image_edit'));

Route::post('/admin/document_image_update', array('as' => 'DocumentImageUpdate', 'uses' => 'AdminController@document_image_update'));

Route::get('/admin/document-image/edit/{id}', array('as' => 'AdminImageTypesEdit', 'uses' => 'AdminController@edit_image_type'));

Route::post('/admin/document-image/update', array('as' => 'AdminImageTypesUpdate', 'uses' => 'AdminController@update_image_type'));
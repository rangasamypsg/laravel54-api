<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'view_loyalty_list','registration','merchent_login','resetpassword','verify_otp','updatepassword','loyalty_zoin_point','loyalty','sidebar-loyalty-list','loyalty','loyalty-list','merchant-profile-list','resendotp','merchant-edit-profile','merchant-edit-profile-list','merchent_mobile_login','login_verify_otp','logout_update','app_version','popup_menu','loyalty_offer_type','merchant_social_media','merchant_tag_list','user_profile_explore_list','merchant_block',
        'user_registration','user_mobile_login','user_sidebar_list', 'user_profile_list','user_edit_profile','user_edit_profile_list','user_loyalty_detail_list','user_resend_otp', 'user_verify_otp', 'user_login_verify_otp','user_logout_update', 'user_explore_image_list','user_explore_confirm','user_redeem_list','merchant_redeem_verify','merchant_transaction','loyalty_count_check','user_redeemed_list','user_transaction_list','merchant_transaction_loyalty','merchant_transaction_list','merchant_notification','user_notification','merchant_company_description','merchant_tags','merchant_loyalty_status','merchant_tag_delete'
    ];
}

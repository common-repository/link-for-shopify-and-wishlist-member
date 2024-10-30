<?php

namespace link_for_shopify_and_wishlist_member\register;

use link_for_shopify_and_wishlist_member\admin\UserOptions;

class LoginPage {
  private $wlm_api, $communicator, $user_options;

  public function __construct($wlm_api = null, $communicator = null, $user_options = null)
  {
    if ($wlm_api === null) {
      $wlm_api = new \WLMAPI();
    }
    $this->wlm_api = $wlm_api;
    
    if ($communicator === null) {
      $communicator = new Communicator();
    }
    $this->communicator = $communicator;

    if ($user_options === null) {
      $user_options = new UserOptions();
    }
    $this->user_options = $user_options;
  }

  public function get_redirection_url($token)
  {
    $registration_info = $this->communicator->get_registration_info($token); // TODO: handle token not valid
    if ($registration_info->redeemed == true) {
      die('The link you accessed has already been used. If you believe this was in error, please contact the store owner.');
    }

    if ($this->user_options->get_add_level_to_existing_users() &&
      $user = get_user_by('email', $registration_info->customer->email)) { // TODO: this logic is ugly
      $data = array('Users' => array($user->ID), 'TxnID' => $token);
      WishListMemberAPIRequest("/levels/{$registration_info->wishlist_member_level_id}/members", 'POST', $data);
      wp_set_auth_cookie($user->ID, true); // login user
      return get_site_url();
    } else {
      return $this->create_registration_url($registration_info, $token);
    }
  }

  private function sign_params(&$params)
  {
    $secret = (string) $this->wlm_api->GetOption('genericsecret');
    $hash = md5($params['cmd'] . '__' . $secret . '__' . strtoupper(implode('|', $params)));
    $params['hash'] = $hash;
  }

  private function get_registration_generator_url()
  {
    $base_url = get_bloginfo('url');
    $registration_path = $this->wlm_api->GetOption('genericthankyou');
    return "{$base_url}/index.php?/register/{$registration_path}";
  }

  public function request_generated_registration_url($params) // TODO: public for testing, fix with VCR
  {
    $ch = curl_init($this->get_registration_generator_url());
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cookie: XDEBUG_SESSION=PHPSTORM')); // for debugging
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
  }

  private function create_registration_url($registration_info, $token) {
    $params = array(
      'cmd' => 'CREATE',
      'transaction_id' => $token,
      'lastname' => $registration_info->customer->last_name,
      'firstname' => $registration_info->customer->first_name,
      'email' => $registration_info->customer->email,
      'level' => $registration_info->wishlist_member_level_id,
    );
    $this->sign_params($params);

    $output = $this->request_generated_registration_url($params);
    $match_result = preg_match('/CREATE\n(?P<url>http\S+continue\S+)/', $output, $matches);
    if ($match_result === 1) {
      return $matches['url'];
    } else {
      // TODO: handle invalid token more gracefully
      die('The link you accessed is invalid. If you believe this was in error, please contact the store owner.');
    }
  }

}
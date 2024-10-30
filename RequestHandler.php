<?php

namespace link_for_shopify_and_wishlist_member;

class RequestHandler
{
  const API_PARAM = 'link_for_shopify_and_wishlist_member_api';
  const PAGE_PARAM = 'link_for_shopify_and_wishlist_member';
  private $version, $level, $login_page;
  private $response;

  public function __construct($version = null, $level = null, $login_page = null)
  {
    $this->version = $version;
    $this->level = $level;
    $this->login_page = $login_page;

    $this->response['continue'] = false;
  }

  public function dispatch_request($request)
  {
    if(array_key_exists(self::API_PARAM, $request) && $request[self::API_PARAM] == '1') {
      if (array_key_exists('path', $request)) {
        $this->handle_api_request($request);
      } else {
        http_response_code(404);
      }
    } elseif (array_key_exists(self::PAGE_PARAM, $request) && $request[self::PAGE_PARAM] == '1') {
      if (array_key_exists('register', $request) && $request['register'] == '1') {
        if (array_key_exists('token', $request)) {
          if($this->login_page === null) {
            $this->login_page = new register\LoginPage;
          }
          $url = $this->login_page->get_redirection_url($request['token']);
          header("Location: {$url}", true, 307);
        } else {
          $this->response['output'] = 'Link is invalid because registration key is missing.';
        }
      } else {
        http_response_code(404);
      }
    }
    else {
      $this->response['continue'] = true;
    }
    return $this->response;
  }

  private function handle_api_request($request)
  {
    switch ($request['path']) {
      case 'version':
        if ($this->version === null) {
          $this->version = new api\Version();
        }
        $this->response['output'] = $this->version->get_version();
        http_response_code(200);
        break;
      case 'levels':
        if ($this->level === null) {
          $this->level = new api\Levels();
        }
        // TODO: return 400 if levels has an error
        $this->response['output'] = $this->level->get_levels();
        http_response_code(200);
        break;
      default:
        http_response_code(404);
        break;
    }
  }
}
<?php
namespace App\Http\Controllers;

use App;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Respect\Validation\Validator as Valid;
use View;
use Auth;

class SysController extends WasabiBaseController {
	
    public function __construct() {
		
    }
	
	public function message(Request $request) {
		$data = [];
		$session = $request->session();
		if (! $session->has('sys_message')) {
			// If no message was found then redirect to homepage //
			xplog('No message was passed', __METHOD__);
			return redirect(url());
		}
		$msg_details = $session->get('sys_message');
		$data['sys_message'] = $msg_details['message'];
		$data['sys_message_label'] = 'Okay';
		if (isset($msg_details['redirect'])) {
			$data['sys_message_link'] = $msg_details['redirect'];
			if (is_array($msg_details['redirect'])) {
				foreach ($msg_details['redirect'] as $label => $link) { // TODO: maybe not use a foreach here
					$data['sys_message_link'] = $link;
					$data['sys_message_label'] = $label;
				}			
			}		
		}		
		return View::make('sys.message', $data)->render();
	} 
}

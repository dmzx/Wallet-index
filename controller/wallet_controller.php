<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\controller;

use phpbb\template\template;
use phpbb\user;
use phpbb\controller\helper;

class wallet_controller
{
	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param template			$template
	* @param user				$user
	* @param helper		 		$helper
	*/
	public function __construct(
		template $template,
		user $user,
		helper $helper
	)
	{
		$this->template 			= $template;
		$this->user 				= $user;
		$this->helper 				= $helper;
	}

	public function handle_wallet()
	{
		$this->template->assign_block_vars('navlinks', [
			'FORUM_NAME'	=> $this->user->lang('WALLETINDEX_TITLE'),
			'U_VIEW_FORUM'	=> $this->helper->route('dmzx_wallet_controller'),
		]);

		return $this->helper->render('wallet_body.html', $this->user->lang('WALLETINDEX_TITLE'));
	}
}

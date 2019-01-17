<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\acp;

class acp_walletindex_info
{
	function module()
	{
		return array(
			'filename'	=> '\dmzx\walletindex\acp\acp_walletindex_module',
			'title'		=> 'ACP_WALLETINDEX_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title' => 'ACP_WALLETINDEX_CONFIG',
					'auth' => 'ext_dmzx/walletindex && acl_a_board',
					'cat'	=> array('ACP_WALLETINDEX_CONFIG')
				),
			),
		);
	}
}

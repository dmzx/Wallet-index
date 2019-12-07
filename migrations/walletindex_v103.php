<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\migrations;

class walletindex_v103 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return [
			'\dmzx\walletindex\migrations\walletindex_v102',
		];
	}

	public function update_data()
	{
		return [
			['config.update', ['walletindex_version', '1.0.3']],
		];
	}
}

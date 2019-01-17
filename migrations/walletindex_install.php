<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\migrations;

class walletindex_install extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('walletindex_version', '1.0.0')),

			// Add ACP extension category
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_WALLETINDEX_TITLE'
			)),
			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_WALLETINDEX_TITLE',
				array(
					'module_basename'	=> '\dmzx\walletindex\acp\acp_walletindex_module',
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'walletindex'	=> array(
					'COLUMNS' => array(
						'walletindex_id'					=> array('UINT', null, 'auto_increment'),
						'walletindex_enable' 				=> array('UINT', '0'),
						'walletindex_enable_guest' 			=> array('UINT', '0'),
						'walletindex_enable_footer' 		=> array('UINT', '0'),
						'walletindex_footer_guest' 			=> array('UINT', '0'),
						'walletindex'						=> array('VCHAR', ''),
						'walletindex_wallet'				=> array('VCHAR', ''),
						'walletindex_ticker'				=> array('VCHAR', ''),
						'walletindex_qr_size'				=> array('VCHAR', ''),
					),
					'PRIMARY_KEY'	=> 'walletindex_id',
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'walletindex',
			),
		);
	}
}

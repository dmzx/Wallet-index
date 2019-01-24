<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\migrations;

class walletindex_v101 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\dmzx\walletindex\migrations\walletindex_install',
		);
	}

	public function update_data()
	{
		return array(
			array('config.update', array('walletindex_version', '1.0.1')),
			array('config.add', array('walletindex_icon_name', 'Wallet')),
			array('config.add', array('walletindex_icon', 'fa-address-card')),
			array('config.add', array('walletindex_default_currency', 1)),
			array('config_text.add', array('walletindex_terms', '')),
			array('custom', array(array(&$this, 'add_walletindex_currency_data'))),
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'walletindex' => array(
					'walletindex_show_recieved'		=> array('UINT', '0'),
					'walletindex_value_crypto'		=> array('UINT', '0'),
				),
			),
			'add_tables' => array(
				$this->table_prefix . 'walletindex_currency' => array(
					'COLUMNS'	 => array(
						'currency_id'		=> array('UINT', null, 'auto_increment'),
						'currency_name'	 => array('VCHAR:50', ''),
						'currency_iso_code' => array('VCHAR:10', ''),
						'currency_symbol'	=> array('VCHAR:10', ''),
						'currency_on_left'	=> array('BOOL', 1),
						'currency_enable'	=> array('BOOL', 1),
						'currency_order'	=> array('UINT', 0),
					),
					'PRIMARY_KEY' => array('currency_id'),
				),
			),
		);
	}

	public function add_walletindex_currency_data()
	{
		$currency_data = array(
			array(
				'currency_name'	 => 'U.S. Dollar',
				'currency_iso_code' => 'USD',
				'currency_symbol'	=> '&dollar;',
				'currency_enable'	=> true,
				'currency_on_left'	=> true,
				'currency_order'	=> 1,
			),
			array(
				'currency_name'	 => 'Euro',
				'currency_iso_code' => 'EUR',
				'currency_symbol'	=> '&euro;',
				'currency_enable'	=> true,
				'currency_on_left'	=> false,
				'currency_order'	=> 2,
			),
			array(
				'currency_name'	 => 'Australian Dollar',
				'currency_iso_code' => 'AUD',
				'currency_symbol'	=> '&dollar;',
				'currency_enable'	=> true,
				'currency_on_left'	=> true,
				'currency_order'	=> 3,
			),
			array(
				'currency_name'	 => 'Canadian Dollar',
				'currency_iso_code' => 'CAD',
				'currency_symbol'	=> '&dollar;',
				'currency_enable'	=> true,
				'currency_on_left'	=> true,
				'currency_order'	=> 4,
			),
			array(
				'currency_name'	 => 'Hong Kong Dollar',
				'currency_iso_code' => 'HKD',
				'currency_symbol'	=> '&dollar;',
				'currency_enable'	=> true,
				'currency_on_left'	=> true,
				'currency_order'	=> 5,
			),
			array(
				'currency_name'	 => 'Pound Sterling',
				'currency_iso_code' => 'GBP',
				'currency_symbol'	=> '&pound;',
				'currency_enable'	=> true,
				'currency_on_left'	=> true,
				'currency_order'	=> 6,
			),
			array(
				'currency_name'	 => 'Yen',
				'currency_iso_code' => 'JPY',
				'currency_symbol'	=> '&yen;',
				'currency_enable'	=> true,
				'currency_on_left'	=> false,
				'currency_order'	=> 7,
			),
		);

		$this->db->sql_multi_insert($this->table_prefix . 'walletindex_currency', $currency_data);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'walletindex_currency',
			),
		);
	}
}

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
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\config\db_text;
use phpbb\extension\manager;
use phpbb\path_helper;

class wallet_controller
{
	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var db_interface */
	protected $db;

	/** @var config */
	protected $config;

	/** @var helper */
	protected $helper;

	/** @var db_text */
	protected $config_text;

	/** @var string */
	protected $walletindex_table;

	/** @var string */
	protected $walletindex_currency_table;

	/** @var manager */
	protected $ext_manager;

	/** @var path_helper */
	protected $path_helper;

	/**
	* Constructor
	*
	* @param template				$template
	* @param user					$user
	* @param db_interface			$db
	* @param config					$config
	* @param helper		 			$helper
	* @param db_text				$config_text
	* @param string					$walletindex_table
	* @param string					$walletindex_currency_table
	* @param manager				$ext_manager
	* @param path_helper			$path_helper
	*/
	public function __construct(
		template $template,
		user $user,
		db_interface $db,
		config $config,
		helper $helper,
		db_text $config_text,
		$walletindex_table,
		$walletindex_currency_table,
		manager $ext_manager,
		path_helper $path_helper
	)
	{
		$this->template 					= $template;
		$this->user 						= $user;
		$this->db 							= $db;
		$this->config 						= $config;
		$this->helper 						= $helper;
		$this->config_text					= $config_text;
		$this->walletindex_table 			= $walletindex_table;
		$this->walletindex_currency_table 	= $walletindex_currency_table;
		$this->ext_manager	 				= $ext_manager;
		$this->path_helper	 				= $path_helper;
		$this->ext_path 					= $this->ext_manager->get_extension_path('dmzx/walletindex', true);
		$this->ext_path_web 				= $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function handle_wallet()
	{
		$sql = 'SELECT *
			FROM ' . $this->walletindex_table;
		$result	 = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if ($row['walletindex_enable'])
		{
			$data = $this->config_text->get_array(array(
				'walletindex_terms',
			));

			$walletindex_currency_symbol = $this->get_walletindex_currency_symbol();
			$get_walletindex_currency_iso_code = $this->get_walletindex_currency_iso_code();

			$this->template->assign_vars(array(
				'WALLETINDEX_SHOW_RECIEVED'			=> $row['walletindex_show_recieved'],
				'WALLETINDEX_VALUE_CRYPTO'			=> $row['walletindex_value_crypto'],
				'WALLETINDEX_TERMS'					=> html_entity_decode($data['walletindex_terms']),
				'WALLETINDEX_CURRENCY_SYMBOL'		=> $walletindex_currency_symbol,
			));

			while ($row = $this->db->sql_fetchrow($result))
			{
				if (!empty($row['walletindex']))
				{
					$url= 'https://min-api.cryptocompare.com/data/price?fsym=' . strtoupper ($row['walletindex_ticker']) . '&tsyms=' . $get_walletindex_currency_iso_code;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$response = curl_exec($ch);
					curl_close($ch);

					$json = json_decode($response, true);
					$crypto_price = $json["$get_walletindex_currency_iso_code"];

					$this->template->assign_block_vars('walletindex', array(
						'WALLETINDEX'						=> $row['walletindex'],
						'WALLETINDEX_WALLET'				=> $row['walletindex_wallet'],
						'WALLETINDEX_TICKER'				=> $row['walletindex_ticker'],
						'WALLETINDEX_QR_SIZE'				=> $row['walletindex_qr_size'],
						'WALLETINDEX_IMAGEPATH'				=> $this->ext_path_web . 'images',
						'WALLETINDEX_VALUE'					=> $crypto_price,
						'WALLETINDEX_WALLET_RECIEVED'		=> $this->user->lang('WALLETINDEX_RECIEVED', $row['walletindex'] , $this->fetch($row['walletindex_ticker'], $row['walletindex_wallet'])),
						'S_WALLETINDEX_RECIEVED'			=> ($this->fetch($row['walletindex_ticker'], $row['walletindex_wallet']) > 0),
					));
				};
			}
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_block_vars('navlinks', [
			'FORUM_NAME'	=> $this->config['walletindex_icon_name'],
			'U_VIEW_FORUM'	=> $this->helper->route('dmzx_wallet_controller'),
		]);

		return $this->helper->render('wallet_body.html', $this->config['walletindex_icon_name']);
	}

	public function get_walletindex_currency_symbol()
	{
		$sql = 'SELECT currency_symbol
			FROM ' . $this->walletindex_currency_table . '
			WHERE currency_id = "' .	$this->config['walletindex_default_currency'] .'"';
		$this->db->sql_query($sql);

		return $this->db->sql_fetchfield('currency_symbol');
	}

	public function get_walletindex_currency_iso_code()
	{
		$sql = 'SELECT currency_iso_code
			FROM ' . $this->walletindex_currency_table . '
			WHERE currency_id = "' .	$this->config['walletindex_default_currency'] .'"';
		$this->db->sql_query($sql);

		return $this->db->sql_fetchfield('currency_iso_code');
	}

	public function fetch($type, $address)
	{
		switch ($type)
		{
			case 'eth':
				return $this->address_received_eth($address);
			break;
			case 'btc':
				return $this->address_received_btc($address);
			break;
			case 'ltc':
				return $this->address_received_ltc($address);
			break;
			case 'bch':
				return $this->address_received_bch($address);
			break;
			default:
				return false;
			break;
		}
	}

	private function address_received_eth($address)
	{
		$api = 'https://api.blockcypher.com/v1/eth/main/addrs/'. $address .'/balance';
		$data = @file_get_contents($api);

		if (!$data)
		{
			return false;
		}
		else
		{
			$data = @json_decode($data, true);
			return floatval($data['total_received'] / 1000000000000000000);
		}
	}

	private function address_received_btc($address)
	{
		$api = 'https://blockchain.info/address/' . $address . '?limit=0&format=json';
		$data = @file_get_contents($api);

		if (!$data)
		{
			return false;
		}
		else
		{
			$data = @json_decode($data, true);
			return floatval($data['total_received'] / 100000000);
		}
	}

	private function address_received_bch($address)
	{
		$api = 'https://bitcoincash.blockexplorer.com/api/addr/' . $address . '?noTxList=1';
		$data = @file_get_contents($api);

		if (!$data)
		{
			return false;
		}
		else
		{
			$data = @json_decode($data, true);
			return $data['totalReceived'];
		}
	}

	private function address_received_ltc($address)
	{
		$api = 'https://chainz.cryptoid.info/ltc/api.dws?q=getreceivedbyaddress&a='. $address;
		$data = @file_get_contents($api);
		$data = @json_decode($data, true);

		return $data;
	}
}

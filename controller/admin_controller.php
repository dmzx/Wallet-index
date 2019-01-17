<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\controller;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\log\log_interface;
use phpbb\user;
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\request\request_interface;
use phpbb\extension\manager;
use phpbb\path_helper;

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var log_interface */
	protected $log;

	/** @var user */
	protected $user;

	/** @var db_interface */
	protected $db;

	/** @var request_interface */
	protected $request;

	/** @var string */
	protected $walletindex_table;

	/** @var manager */
	protected $ext_manager;

	/** @var path_helper */
	protected $path_helper;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param config				$config
	 * @param template				$template
	 * @param log_interface			$log
	 * @param user					$user
	 * @param db_interface			$db
	 * @param request_interface		$request
	 * @param string				$walletindex_table
	 * @param manager				$ext_manager
	* @param path_helper			$path_helper
	 */
	public function __construct(
		config $config,
		template $template,
		log_interface $log,
		user $user,
		db_interface $db,
		request_interface $request,
		$walletindex_table,
		manager $ext_manager,
		path_helper $path_helper
	)
	{
		$this->config 				= $config;
		$this->template 			= $template;
		$this->log 					= $log;
		$this->user 				= $user;
		$this->db 					= $db;
		$this->request 				= $request;
		$this->walletindex_table 	= $walletindex_table;
		$this->ext_manager	 		= $ext_manager;
		$this->path_helper	 		= $path_helper;
		$this->ext_path 			= $this->ext_manager->get_extension_path('dmzx/walletindex', true);
		$this->ext_path_web 		= $this->path_helper->update_web_root_path($this->ext_path);
	}

	public function display_options()
	{
		add_form_key('acp_walletindex');

		$this->user->add_lang_ext('dmzx/walletindex', 'acp_walletindex');

		$sql = 'SELECT *
			FROM '. $this->walletindex_table;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if (!empty($row['walletindex']))
			{
				$this->template->assign_block_vars('walletindex', array(
					'WALLETINDEX'				=> $row['walletindex'],
					'WALLETINDEX_WALLET'		=> $row['walletindex_wallet'],
					'WALLETINDEX_TICKER'		=> $row['walletindex_ticker'],
					'WALLETINDEX_QR_SIZE'		=> $row['walletindex_qr_size'],
					'WALLETINDEX_IMAGEPATH'		=> $this->ext_path_web . 'images',
				));
			};
		};

		if (empty($row['walletindex']))
		{
			$this->template->assign_block_vars('walletindex', array(
				'WALLETINDEX' => '',
			));
		};

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_walletindex'))
			{
				trigger_error('FORM_INVALID');
			}

			$this->db->sql_query('TRUNCATE TABLE ' . $this->walletindex_table);

			if (!$row['walletindex_id'])
			{
				$sql_arr_id = array(
					'walletindex_id' => '1',
				);
				$sql = 'INSERT INTO ' . $this->walletindex_table . ' ' . $this->db->sql_build_array('INSERT', $sql_arr_id);
				$this->db->sql_query($sql);
			};

			$walletindex 			= $this->request->variable('walletindex', array('' => ''),true);
			$walletindex_wallet 	= $this->request->variable('walletindex_wallet', array('' => ''),true);
			$walletindex_ticker 	= $this->request->variable('walletindex_ticker', array('' => ''),true);
			$walletindex_qr_size 	= $this->request->variable('walletindex_qr_size', array('' => ''),true);
			$walletindex			= array_merge(array_filter($walletindex));

			$i = 0;
			while ($i < count($walletindex))
			{
				$walletindex[$i] = $walletindex[$i];

				$sql_ary1 = array(
					'walletindex' 			=> $walletindex[$i],
					'walletindex_wallet' 	=> $walletindex_wallet[$i],
					'walletindex_ticker' 	=> $walletindex_ticker[$i],
					'walletindex_qr_size' 	=> $walletindex_qr_size[$i],
				);
				$this->db->sql_multi_insert($this->walletindex_table, $sql_ary1);
				$i++;
			}

			$sql_ary_block = array(
				'walletindex_enable' 			=> $this->request->variable('walletindex_enable', ''),
				'walletindex_enable_guest' 		=> $this->request->variable('walletindex_enable_guest', ''),
				'walletindex_enable_footer' 	=> $this->request->variable('walletindex_enable_footer', ''),
				'walletindex_footer_guest' 		=> $this->request->variable('walletindex_footer_guest', ''),
			);

			$this->db->sql_query('UPDATE ' . $this->walletindex_table . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary_block) . "
				WHERE walletindex_id =	1"
			);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_WALLETINDEX_SAVE');

			trigger_error($this->user->lang('WALLETINDEX_SAVED') . adm_back_link($this->u_action));
		}

		$sql = 'SELECT walletindex_enable, walletindex_enable_guest, walletindex_enable_footer, walletindex_footer_guest
			FROM ' . $this->walletindex_table . "
			WHERE walletindex_id =	1";
		$result = $this->db->sql_query($sql);
		$walletindex_data = $this->db->sql_fetchrow($result);

		$this->template->assign_vars(array(
			'U_ACTION'							=> $this->u_action,
			'WALLETINDEX_ENABLE'				=> $walletindex_data['walletindex_enable'],
			'WALLETINDEX_ENABLE_GUEST'			=> $walletindex_data['walletindex_enable_guest'],
			'WALLETINDEX_ENABLE_FOOTER'			=> $walletindex_data['walletindex_enable_footer'],
			'WALLETINDEX_FOOTER_GUEST'			=> $walletindex_data['walletindex_footer_guest'],
			'WALLETINDEX_VERSION'				=> $this->config['walletindex_version'],
		));
	}

	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}

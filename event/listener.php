<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\walletindex\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\template\template;
use phpbb\user;
use phpbb\db\driver\driver_interface as db_interface;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\extension\manager;
use phpbb\path_helper;

class listener implements EventSubscriberInterface
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

	/** @var string */
	protected $walletindex_table;

	/** @var string */
	protected $php_ext;

	/** @var manager */
	protected $ext_manager;

	/** @var path_helper */
	protected $path_helper;

	/**
	* Constructor
	*
	* @param template			$template
	* @param user				$user
	* @param db_interface		$db
	* @param config				$config
	* @param helper		 		$helper
	* @param string				$walletindex_table
	* @param string				$php_ext
	* @param manager			$ext_manager
	* @param path_helper		$path_helper
	*/
	public function __construct(
		template $template,
		user $user,
		db_interface $db,
		config $config,
		helper $helper,
		$walletindex_table,
		$php_ext,
		manager $ext_manager,
		path_helper $path_helper
	)
	{
		$this->template 			= $template;
		$this->user 				= $user;
		$this->db 					= $db;
		$this->config 				= $config;
		$this->helper 				= $helper;
		$this->walletindex_table 	= $walletindex_table;
		$this->php_ext				= $php_ext;
		$this->ext_manager	 		= $ext_manager;
		$this->path_helper	 		= $path_helper;
		$this->ext_path 			= $this->ext_manager->get_extension_path('dmzx/walletindex', true);
		$this->ext_path_web 		= $this->path_helper->update_web_root_path($this->ext_path);
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'						=> 'load_language_on_setup',
			'core.page_header' 						=> 'page_header',
			'core.viewonline_overwrite_location'	=> 'add_page_viewonline',
		];
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/walletindex',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function page_header($event)
	{
		$sql = 'SELECT *
			FROM ' . $this->walletindex_table;
		$result	 = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if ($row['walletindex_enable'])
		{
			$s_walletindex_enable_guest = (empty($row['walletindex_enable_guest']) && ($this->user->data['user_id'] == ANONYMOUS || $this->user->data['is_bot']));
			$s_walletindex_footer_guest = (empty($row['walletindex_footer_guest']) && ($this->user->data['user_id'] == ANONYMOUS || $this->user->data['is_bot']));

			$this->template->assign_vars(array(
				'WALLETINDEX_ENABLE'				=> $row['walletindex_enable'],
				'WALLETINDEX_ENABLE_GUEST'			=> $s_walletindex_enable_guest,
				'WALLETINDEX_ENABLE_FOOTER'			=> $row['walletindex_enable_footer'],
				'WALLETINDEX_FOOTER_GUEST'			=> $s_walletindex_footer_guest,
			));

			while ($row = $this->db->sql_fetchrow($result))
			{
				if (!empty($row['walletindex']))
				{
					$this->template->assign_block_vars('walletindex_footer', array(
						'WALLETINDEX'					=> $row['walletindex'],
						'WALLETINDEX_TICKER'			=> $row['walletindex_ticker'],
						'WALLETINDEX_IMAGEPATH'			=> $this->ext_path_web . 'images',
					));
				};
			}
			$this->assign_authors();
		}
		else
		{
			$this->template->assign_vars(array(
				'WALLETINDEX_ENABLE'			=> false,
				'WALLETINDEX_ENABLE_GUEST'		=> true,
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'U_WALLET'	 					=> $this->helper->route('dmzx_wallet_controller'),
			'WALLETINDEX_ICON_NAME'			=> $this->config['walletindex_icon_name'],
			'WALLETINDEX_ICON'				=> $this->config['walletindex_icon'],
		));
	}

	public function add_page_viewonline($event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/wallet') === 0)
		{
			$event['location'] = $this->config['walletindex_icon_name'];
			$event['location_url'] = $this->helper->route('dmzx_wallet_controller');
		}
	}

	protected function assign_authors()
	{
		$md_manager = $this->ext_manager->create_extension_metadata_manager('dmzx/walletindex', $this->template);
		$meta = $md_manager->get_metadata();
		$author_homepages = array();

		foreach (array_slice($meta['authors'], 0, 2) as $author)
		{
			$author_homepages[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', $author['homepage'], $author['name']);
		}

		$this->template->assign_vars(array(
			'WALLETINDEX_DISPLAY_NAME'		=> $this->config['walletindex_icon_name'],
			'WALLETINDEX_AUTHOR_HOMEPAGES'	=> implode(' &amp; ', $author_homepages),
			'WALLETINDEX_VERSION'			=> $this->config['walletindex_version'],
		));
	}
}

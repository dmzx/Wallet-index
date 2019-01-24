<?php
/**
*
* @package phpBB Extension - Wallet index
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'WALLETINDEX_ENABLE'					=> 'Enable Wallet index',
	'WALLETINDEX_ENABLE_EXPLAIN'			=> 'Enable the Wallet index extension.',
	'WALLETINDEX_ICON_NAME'					=> 'Name for link',
	'WALLETINDEX_ICON_NAME_EXPLAIN'			=> 'Name for link in navbar.',
	'WALLETINDEX_ICON'						=> 'Select icon',
	'WALLETINDEX_ICON_EXPLAIN'				=> 'Click on name to select new Font Awesome icon.<br />See <samp><strong><a href="https://fontawesome.com/v4.7.0/icons/" title="Font Awesome">Font Awesome</a></strong></samp> for more info.',
	'WALLETINDEX_ICON_NAME_PLACEHOLDER'		=> 'Name for link',
	'WALLETINDEX_ICON_PLACEHOLDER'			=> 'Click here',
	'WALLETINDEX_VALUE_CRYPTO'				=> 'Show current value',
	'WALLETINDEX_VALUE_CRYPTO_EXPLAIN'		=> 'Show current value of crypto.',
	'WALLETINDEX_DEFAULT_CURRENCY'			=> 'Currency symbol',
	'WALLETINDEX_DEFAULT_CURRENCY_EXPLAIN'	=> 'Define the currency symbol.',
	'WALLETINDEX_SHOW_RECIEVED'				=> 'Enable recieved donations',
	'WALLETINDEX_SHOW_RECIEVED_EXPLAIN'		=> 'If enabled the donations amount will be shown.</br><em>If value is above 0 then only visable.</em>',
	'WALLETINDEX_ENABLE_GUEST'				=> 'Enable to guest',
	'WALLETINDEX_ENABLE_GUEST_EXPLAIN'		=> 'Show Wallet index to guests.',
	'WALLETINDEX_ENABLE_FOOTER'				=> 'Enable wallet icons to footer',
	'WALLETINDEX_ENABLE_FOOTER_EXPLAIN'		=> 'Show wallet icons in footer.',
	'WALLETINDEX_FOOTER_GUEST'				=> 'Enable wallet icons in footer to guest',
	'WALLETINDEX_FOOTER_GUEST_EXPLAIN'		=> 'Show wallet icons in footer to guests.',
	'WALLETINDEX_PLACEHOLDER'				=> 'Name',
	'WALLETINDEX_PLACEHOLDER_WALLET'		=> 'Wallet adress',
	'WALLETINDEX_PLACEHOLDER_TICKER'		=> 'Ticker',
	'WALLETINDEX_PLACEHOLDER_SIZE'			=> 'Size QR code',
	'WALLETINDEX_TERMS_BODY'				=> 'Wallet Terms of use',
	'WALLETINDEX_TERMS'						=> 'Wallet index terms',
	'WALLETINDEX_TERMS_EXPLAIN'				=> 'Enter the text you want displayed for terms on wallet page.<br />HTML is allowed and only visable when terms are added.',
	'WALLETINDEX'							=> 'Name',
	'WALLETINDEX_EXPLAIN'					=> '<em>
To generate a wallet follow 4 simple steps: <br />
- Fill in the name of cryptocoin.<br />
- Fill in the wallet adress.<br />
- Fill in the ticker of cryptocoin.<br />
- Fill in the format for QR size.
</em>',
	'WALLETINDEX_WALLET'					=> 'Wallet adress',
	'WALLETINDEX_TICKER'					=> 'Ticker',
	'WALLETINDEX_QR_SIZE'					=> 'QR size',
	'WALLETINDEX_MORE_LINKS'				=> 'Add Wallet',
	'WALLETINDEX_SAVED'						=> 'Wallet index settings saved',
	'WALLETINDEX_VERSION'					=> 'Version',
));

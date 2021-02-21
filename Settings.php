<?php
/**********************************************************************************
* Settings.php                                                                    *
***********************************************************************************
* SMF: Simple Machines Forum                                                      *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
* =============================================================================== *
* Software Version:           SMF 1.1                                             *
* Software by:                Simple Machines (http://www.simplemachines.org)     *
* Copyright 2006 by:          Simple Machines LLC (http://www.simplemachines.org) *
*           2001-2006 by:     Lewis Media (http://www.lewismedia.com)             *
* Support, News, Updates at:  http://www.simplemachines.org                       *
***********************************************************************************
* This program is free software; you may redistribute it and/or modify it under   *
* the terms of the provided license as published by Simple Machines LLC.          *
*                                                                                 *
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* See the "license.txt" file for details of the Simple Machines license.          *
* The latest version can always be found at http://www.simplemachines.org.        *
**********************************************************************************/

########## Maintenance ##########
# Note: If $maintenance is set to 2, the forum will be unusable!  Change it to 0 to fix it.
$mtitle = 'Maintenance Mode';		# Title for the Maintenance Mode message.
$mmessage = 'Woopig is temporarily offline for server and software maintenance.  In the meantime, please find a suitable chemical fire, and dive right in.  We expect to be back online by the afternoon of Thursday, Oct. 12.';		# Description of why the forum is in maintenance mode.

########## Forum Info ##########
$mbname = 'Woopig.net';		# The name of your forum.
$language = 'english';		# The default language file set for the forum.
$boardurl = 'http://woopig.net/board';		# URL to your forum's folder. (without the trailing /!)
$webmaster_email = 'admin@woopig.net';		# Email address to send emails from. (like noreply@yourdomain.com.)
$cookiename = 'SMFCookie472';		# Name of the cookie to set for authentication.

########## Database Info ##########
$db_server = 'localhost';
$db_name = 'ct_wobo_x4G83';
$db_user = 'ct_wobo_x4G83';
$db_passwd = '9RN&mmD$Ad9b5Cyw03fq';
$db_prefix = 'yabbse_';
$db_persist = 0;
$db_error_send = 0;

########## Directories/Files ##########
# Note: These directories do not have to be changed unless you move things.
$boarddir = '/home/woopigdotnet/public_html/board';		# The absolute path to the forum's folder. (not just '.'!)
$sourcedir = '/home/woopigdotnet/public_html/board/Sources';		# Path to the Sources directory.

########## Error-Catching ##########
# Note: You shouldn't touch these settings.
$db_last_error = 1318338312;

# Make sure the paths are correct... at least try to fix them.
if (!file_exists($boarddir) && file_exists(dirname(__FILE__) . '/agreement.txt'))
	$boarddir = dirname(__FILE__);
if (!file_exists($sourcedir) && file_exists($boarddir . '/Sources'))
	$sourcedir = $boarddir . '/Sources';

$cachedir = '/home/woopigdotnet/public_html/board/cache';
$maintenance = 0;
$ssi_db_user = '';
$image_proxy_secret = 'cadd5eed774978afcf94';
$image_proxy_maxsize = 5190;
$image_proxy_enabled = 0;
?>
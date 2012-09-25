<?php
/*
 Plugin Name: Facebook Page Albums
 Plugin URI:
 Description: Get the All album from Facebook Page.
 Version: 1.0.0
 Author: Daiki Suganuma
 Author URI: 
 */

/*  Copyright 2012 Daiki Suganuma  (email : daiki.suganuma@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/***** Define Part *****/
define('FACEBOOK_PAGE_ALBUMS_CACHE_GROUP', 'facebook_page_albums');
define('FACEBOOK_PAGE_ALBUMS_CACHE_TIMEOUT', 60 * 60 ); //60 minutes

if ( is_admin() ) {
	require_once('facebook-page-albums-admin.php');
}


/**
 * Get album list.
 *
 * @return array album list
 */
function facebook_page_albums_get_album_list() {
	$cache_name = 'album_list';
	$result = wp_cache_get($cache_name, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP);
	if (empty($result)) {
		require_once('class-facebook-page-albums-apimanager.php');
		$api = new FacebookPageAlbumsAPIManager();
		$result = $api->get_albums();
		if (!empty($result)) {
			//set cache
			wp_cache_set($cache_name, $result, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP, FACEBOOK_PAGE_ALBUMS_CACHE_TIMEOUT);
		}
	}

	return $result;
}


/**
 * Get album.
 *
 * @return array album list
 */
function facebook_page_albums_get_album($album_id) {
	if (empty($album_id)) {
		return false;
	}
	$cache_name = 'album_' . $album_id;
	$result = wp_cache_get($cache_name, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP);
	if (empty($result)) {
		require_once('class-facebook-page-albums-apimanager.php');
		$api = new FacebookPageAlbumsAPIManager();
		$result = $api->get($album_id);
		if (!empty($result)) {
			//set cache
			wp_cache_set($cache_name, $result, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP, FACEBOOK_PAGE_ALBUMS_CACHE_TIMEOUT);
		}
	}

	return $result;
}


/**
 * Get photo list.
 *
 * @param  integer $album_id album id
 * @param  array $args arguments
 * @return array             photo list
 */
function facebook_page_albums_get_photo_list($album_id, $args=null) {
	if (empty($album_id)) {
		return false;
	}
	$cache_name = 'photo_list_' . $album_id;
	$result = wp_cache_get($cache_name, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP);
	if (empty($result)) {
		require_once('class-facebook-page-albums-apimanager.php');
		$api = new FacebookPageAlbumsAPIManager();
		$result = $api->get_photos($album_id, $args);
		if (!empty($result)) {
			//set cache
			wp_cache_set($cache_name, $result, FACEBOOK_PAGE_ALBUMS_CACHE_GROUP, FACEBOOK_PAGE_ALBUMS_CACHE_TIMEOUT);
		}
	}

	return $result;
}
?>
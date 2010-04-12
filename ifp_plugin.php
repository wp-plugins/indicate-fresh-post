<?php
/*
Plugin Name: Indicate Fresh Post
Plugin URI: http://www.tehnopedija.net/?p=1671
Description:  My first WP plugin that (by default) adds excalamation sign [!] to the recent posts, in fact posts not older than (by default 7) xx days.
Author: Adem Omerovic
Version: 0.2.0
Author URI: http://www.tehnopedija.net/
*/

/*  Copyright 2010  Adem Omerovic  (email: ademomer@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



//Adding filter to the WP hook
add_filter('the_title','indicate_fresh_post',10,1);

//Defining function
function indicate_fresh_post($ifp_new_title) {

	//Reading settings from ifp_settings.ini file
	//If you do something wrong in ifp_settings.ini or for some reason there is no file at all
	//plugin will still work with default settings loaded.
	@$ifp_ext_settings = parse_ini_file("ifp_settings.ini");
	if ((empty($ifp_ext_settings)) || (empty($ifp_ext_settings[time])) || (empty($ifp_ext_settings[sign])) || (empty($ifp_ext_settings[color])) || (empty($ifp_ext_settings[effect]))) {
			//Setting default values
			$ifp_time = (int) 7;
			$ifp_sign = "!";
			$ifp_color = "red";
			$ifp_effect = "blink";
		} else {
			//Time
			$ifp_time = absint($ifp_ext_settings[time]);
			//Sign
			$ifp_sign = esc_html($ifp_ext_settings[sign]);
			//Sign color
			$ifp_color = esc_html($ifp_ext_settings[color]);
			//Sign effect
			$ifp_effect = esc_html($ifp_ext_settings[effect]);
		};

	$ifp_detect_loop = in_the_loop();
	//Making sure that filter will be executed in the WP loop only thus preventing it make changes in categories names, pages naes and single page/post title
	if ((!is_singular()) && (!empty($ifp_detect_loop))) {
		$ifp_date_current = mktime (0,0,0,get_the_time("m"),get_the_time("d")+$ifp_time,get_the_time("y"));
		$ifp_date_posted = mktime(0,0,0,date("m"),date("d"),date("y"));
			if ($ifp_date_current >= $ifp_date_posted) {
				//This is style that will be added before the title
				$ifp_pre_title = '<span style="color:'.$ifp_color.'; font-weight:bold; text-decoration:'.$ifp_effect.';">'.$ifp_sign.'&nbsp;</span>';
				//This is style that will be added after the title. It is here for future plugin development
				$ifp_post_title = "";
				//This is modified WP title with our pre title and after title styles
				$ifp_new_title = $ifp_pre_title."".$ifp_new_title."".$ifp_post_title; 
				} else {
				$ifp_new_title = "".$ifp_new_title;
			}
		//echo $ifp_time; //when developing I ECHO varios variables to check if it is properly passed, leave it commented
		return $ifp_new_title;
	}
	//echo $ifp_time; //when developing I ECHO varios variables to check if it is properly passed, leave it commented
	return $ifp_new_title;
}
?>
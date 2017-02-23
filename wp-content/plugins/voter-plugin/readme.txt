=== Voter Plugin ===
Contributors: aheadzen
Tags: voter,review,woocommerce,buddypress,like,unlike,voting
Requires at least : 3.0.0
Tested up to: 4.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Voter plugin adds a recommendation engine (voting or like/unlike features) on any WordPress blog or website.


== Description ==

Voter plugin adds voting options for pages, posts, custom post types, comments, BuddyPress activity, groups, member profiles, WooCommerce products, bbPress topics and posts, and more.

Adds a recommendation system on your wordpress blog site:

 - Supports posts, pages, custom post types and comments.
 - Supports BuddyPress - Groups, Profiles, Activities and more.
 - Supports bbPress Posts
 - Supports WooCommerce Products and Reviews.

Voting options:

 - FaceBook style simple Like and Unlike
 - Up and Down buttons
 - Thumbs buttons
 - Custom Text, HTML or Image buttons

<h4>Features :</h4>
<ul>
<li>Multiple voting options </li>
<li>Uses AJAX for best experience</li>
<li>Widgets ready</li>
<li>ShortCodes ready</li>
<li>Fast and Lightweight. Works on shared hosting.</li>
<li>Enable or disable voting options for posts, pages, BuddyPress activity, groups, member profiles, WooCommerce products and more.</li>
<li>Optimized code for best performance</li>
<li>API for custom use in AJAX or Mobile apps.</li>
<li>Localisation ready.</li>
</ul>

<h4>Widgets</h4>
Go to Wordpress Admin > Appearance > Widgets > Top Listings Voter Plugin (drag & drop) as per you want to display.<br /><br />

<h4>Top voted Shotcode</h4>
Shortcode for top voted listing for posts,pages,products,profile & groups.<br />
`Get shortcode examples ::<br />
shortcode for posts :     [voter_plugin_top_voted type=post num=5]
shortcode for pages :     [voter_plugin_top_voted type=page num=5]
shortcode for custom:     [voter_plugin_top_voted type=custom_post_type num=5]
						where:: "custom_post_type" will be your custom post type like "movie",'ads','listing'
						you will get from wp-admin url like: "wp-admin/edit.php?post_type=movie"
						"post_type=movie" is your custom type you should user here.
						

shortcode for author :   [voter_plugin_top_voted type=top_voted_profile num=5 display=titleimage]
			-- It will consider sum of all votes for post,pages,products and custom post type posted by the author.
			-- you can also add "cats" to restricts output count total votes for specific categories/tags only. eg: cats=6,7,8
			eg : [voter_plugin_top_voted type=top_voted_profile num=5 display=titleimage cats=6,7,8]
			
			role=administrator - for user administrator type, you can use this with type=top_voted_profile
			role=author - for user author type, you can use this with type=top_voted_profile
			role=subscriber - for user subscriber type, you can use this with type=top_voted_profile

shortcode for products :  [voter_plugin_top_voted type=product num=5]
shortcode for profile :   [voter_plugin_top_voted type=profile num=5 display=titleimage]
shortcode for groups :    [voter_plugin_top_voted type=groups num=5]
shortcode for members :   [voter_plugin_top_voted type=profile num=5 display=titleimage]
`

Shortcode for author/member -- total votes
`
Example shotcode :: [vote_count author=id]

where author = User ID
`

Shortcode Options:
type :: post / page / custom_post_type / product / profile / groups / top_voted_profile

role :: administrator / author / editor / subscriber -->  you can use this with type=top_voted_profile

period :: [voter_plugin_top_voted type=post display=title num=5 period=7days]
	where period from :: 7days,15days,30days,90days,180days,365days

display :: Display format for listing, value may be title/image/titleimage -- default is "title"

cats :: Restricts output to a comma seperated list of ids of tags/categories.
[voter_plugin_top_voted type=post num=5 cats=6,7,8]

Note: To display top voted of specific category "type" & "cats" both should be added in shortcode otherwise it will not work.



****************************************************************
Example shortcode with all possible options -->

`[voter_plugin_top_voted type=post num=5 display=titleimage period=30days cat=3,4]`
****************************************************************

How to use Voter API
---------------------------------
 	Get voter details for post/page/product/group/member.....
	http://YOUR-SITE.com/?voterapi=1&pid=ID&username=USERNAME

	if try to get comment vote details you should add --> "&type=comment" in above link
	Type will apply for all like:
	type=post
	type=page
	type=custom_post
	type=product
	type=profile
	type=groups
	type=comment



<h4>Voting Button Shotcode</h4>
The shortcode which can be added in any post,product,page or cutom post type content.
`Get shortcode examples ::
[voter]
`

<h4> Showcase </h4>
[benefitmusic.org](http://benefitmusic.org)

Any problems? [Contact Us](http://aheadzen.com/contact/)

== Installation ==
1. Unzip and upload plugin folder to your /wp-content/plugins/ directory  OR Go to wp-admin > plugins > Add new Plugin & Upload plugin zip.
2. Go to wp-admin > Plugins(left menu) > Activate the plugin
3. See the plugin option link with plugin description on plugin activation page or directly access from wp-admin > Settings(left menu) > VOTER
4. Get translate your plugin to another language by google tutorial :: http://barry.coffeesprout.com/translating-po-files-using-google-translate/

== Screenshots ==
1. Plugin Activation
2. Plugin Settings
3. Blog Comments
4. Page/Post Detail width custom button option
5. Page/Post Detail width vote help option
6. Buddpress Member Profile
7. Buddpress Activity List
8. Buddypress Group Detail
9. Up & Down Button Voting for Woocommerce Product
10. Thumbs Up & Down Voting for Woocommerce Product
11. Login Settings
12. Top Voted Listing Widget
13. Buddypress Activity List
14. Buddypress Topics and Reply

== Configuration ==

1. Go to wp-admin > Settings(left menu) > VOTER, manage settings as per you want.
2. Default will be up & down voting system so you can change it to like/unlike voting
3. new database table will be added to manage voting data, make sure you should add it manually in case of user security permission.
4. Get translate your plugin to another language by google tutorial :: http://barry.coffeesprout.com/translating-po-files-using-google-translate/


== Changelog ==

= 1.0.0.0 =
* Fresh Public Release.

= 1.0.0.1 =
* BBpress topics page voting features added
* Login form & related options added

= 1.0.0.2 =
* On plugin deactivation all data is lost
* dialog js code will go into voter.js file
* Manual loading for js - jquery-ui.js

= 1.0.0.3 =
* Login form css changes
* registration page redirectin settings

= 1.0.0.4 =
* Login dialog should close on outside click

= 1.0.0.5 =
* Buddypress Activity & notification settings on forum topic voting
* voting up/donw related api also added

= 1.0.0.6 =
* Buddypress Activity & notification for posts,pages,comments,topics,groups,profile added...
* voting up/donw related api ERROR - for user login only - solved

= 1.0.0.7 =
* Buddypress Activity & notification for posts,pages,comments,topics,groups,profile added. Error for API solved.

= 1.0.0.8 =
* Notification for posts,pages,comments,topics,groups,profile related error solved. Now notification will display to poster account only.
* Notification will not added for user buddypress activity.


= 1.0.0.9 =
* Notification added for user buddypress activity.
* Login dialog form url settings as per plugin setting options.


= 1.1.0.0 =
*New Widget added for top voted listing for posts,pages,products,profile & groups.<br />
Widget name : "Top Listings Voter Plugin"<br />
Go to wp-admin > widgets > Top Listings Voter Plugin (drag & drop) as per you want to display.<br /><br />

*New Shortcode added for top voted listing for posts,pages,products,profile & groups.<br />
Get shortcode examples ::<br />
shortcode for posts :     [voter_plugin_top_voted type=post num=5]<br />
shortcode for pages :     [voter_plugin_top_voted type=page num=5]<br />
shortcode for products :  [voter_plugin_top_voted type=product num=5]<br />
shortcode for profile :   [voter_plugin_top_voted type=profile num=5]<br />
shortcode for groups :    [voter_plugin_top_voted type=groups num=5]<br />
shortcode for members :   [voter_plugin_top_voted type=profile num=5]<br />



= 1.1.0.1 =
* notification message changed (error solved)

= 1.1.0.2 =
* if buddypress not installed, gives some Warning - SOLVED

= 1.1.0.3 =
* Disable voter plugin settings for specific page templates > New feature added to plugin settings page.

= 1.1.0.4 =
* Notification & activity related changed for older version of buddypress done.

= 1.2.0.0 =
* Notification changes added for older version of buddypress.
* New Email notification option added while you like any post,page,group,member profile, comments,products, etc...

= 1.3.0.0 =
* delete vote, notification & activity related data while delete any post or forum topic.
* notification will be automatically marked as read while any creator or post author visit the detail page.

= 1.3.0.1 =
* buddpress older version forum topic notification & activity problem - solved.

= 1.3.1.1 =
* buddpress older version forum topic notification & activity problem - solved.

= 1.3.2.0 =
* buddpress older version forum topic notification & activity problem - solved.

= 1.3.3.0 =
* email content merged with notification function.

= 1.3.3.1 =
* login in dialog redirection for buddypress.

= 1.3.3.2 =
* optimization task for plugin notification and emails.

= 1.3.3.3 =
* forum topic & reply related settings done.
* plugin organization done.

= 1.4.0 =
* buddypress older version related settings done for activity,notificactions & emails

= 1.4.1 =
* post comments email sent problem was wrong - Solved to commenter id

= 1.4.2 =
* added activity,notification & email alert enable/disable related settings.
* pages,post ...like button not adding - problem solved.
* voting settings for comments was display on admin side - Problem solved.

= 1.4.3 =
* email subject related chage done.

= 2.0.0 =
* Pluing in OOPs format
* solved some errors of notifications.

= 2.0.1 =
* undefined function for notification.php file on line 58 the code is :: $reply_id = bbp_get_reply_id();
* Problem solved and and now OK.

= 2.1.0 =
* undefined function for notification.php file on line 209 the code is :: $activity_id = bp_activity_add($arg_arr);
* Problem solved and and now OK.
* added possible components and added condition so it will work if it is activated.

= 2.1.1 =
* Added new voting option type : "Helpful Option".
* Display the YES or NO option on frond end inplace of voting.
* Added conditions for buddypress & bbpress options like it will display only if buddypress or bbpress activated.
* Default up-down thumbs & button settings default set to -- disable.
* Top voted widget - PHP error  - SOLVED.

= 2.1.2 =
* Plugin Settings - wp-admin >> correction of titles.
* Buddpress & bbypress > problem of css - Correction done.

= 2.1.3 =
* Custom post type - notification display problem - SOLVED

= 2.1.4 =
* Localization (multiple language) po & mo file added
* Thumbs up & down - awaresome font added.
* bbPress wp-admin settings hide while buddypress not activated - Problem solved.

= 2.1.5 =
* Css changes for thumb up & down.
* button up & down font style added instead of background image.

= 2.1.5.1 =
* voting buttons css style problem solved.

= 2.2.0 =
* New shortcode added which can be added in any post,product,page or cutom post type content.
	The shotcode :: [voter]
* New shortcode to display top voted list.
	shotcode :: [voter_plugin_top_voted type=post num=5 period=7days]
	where period from :: 7days,15days,30days,90days,180days,365days
* New select "period" option added for top voted widget.

= 2.2.1 =
* New voting shotcode not working properly - SOLVED.

= 2.2.1.1 =
* some words missed in po file - ADDED.

= 2.2.2 =
* NEW option added for top voted widget and shortcode.
	the option name is "display" and value may be title/image/titleimage
	So new shortcode with image will be:
	shotcode :: [voter_plugin_top_voted type=post num=5 display=image period=7days]

= 2.2.3 =
* Thumb down css problem - SOLVED


= 2.2.4 =
* CSS problem on alert popup in top menu - SOLVED

= 2.2.5 =
* Category wise top voted added in Shortcode.
* Different voting display type selection for different places. Like different voting display for all post pages, different on comments and so on...

= 2.2.6 =
* Voter get api PHP error - SOLVED

= 2.2.7 =
* New Feature to display last 20 voters with voter link.
* Option to display/hide the voter list.

= 2.2.8 =
* Voting like/unlike total up & down added in return of API.
* Voting like/unlike not working as API - Solved.

= 2.2.9 =
* CSS chagnes.

= 2.2.10 =
* Error Correction

= 2.3.0 =
* Voting Plugi API - Cross-Origin Request Blocked problem solved.

= 2.3.1 =
* Voting Plugin -- API - voting like/unlike count not return for comments - Added.

= 2.3.2 =
* Notification text change.

= 2.3.3 =
* New update & change as per forum supports.
* wordpress login action hook added so social plugin will work on login form.
* Activity Delete while unlike the votes.

= 2.3.4 =
* Recent voted listing display problem - SOLVED.
* Activity -> comment reply - voting option added.
* top voter shortcode added more feature to Top voter, Recent voter user, Recently liked posts and Top Voted liked posts

------Display Recent Voter User List
[voter_plugin_top_voted type=post num=5 display=title period=30days display_type=recentvoters]

------Display Top Voter User List
[voter_plugin_top_voted type=post num=5 display=title period=700days display_type=topvoters]

------Display Recently liked Post List
[voter_plugin_top_voted type=post num=5 display=title period=700days display_type=recentposts]

------Display Top liked Post List
[voter_plugin_top_voted type=post num=5 display=title period=700days display_type=topvoted]

= 2.3.5 =
* Changed post voting like/unlike link function.

= 2.3.6 =
* WooCommerce Page content not displaying problem  - SOLVED.

= 2.3.7 =
* ERROR : While Javascript Disabled, Voting not working -  Solved
* Some change in ajax voting functions.

= 2.3.8 =
* activity share api change.

= 3.0.0 =
* Custom voter buttons - text, html and images.
* See the options from wp-admin > voter plugin settings.
* Css problems solved.

= 3.0.1 =
* Some error without buddypress plugin installe - SOLVED.

= 3.0.2 =
* Author Voter Count shortcode display change.

= 3.0.3 =
* Top voted author shortcode -- "cat" argument added so you can use like
shortcode for author :   [voter_plugin_top_voted type=top_voted_profile num=5 cats=1,2,3,4,5]

= 3.0.4 =
[voter_plugin_top_voted type=top_voted_profile role=subscriber num=5 display=title period=7days cats=10,11]

* role=administrator/author/subscriber - for user type, you can use this with type=top_voted_profile

= 3.0.5 =
* author profile role related fix.

= 3.0.6 =
* https not working  - solved.

= 3.0.7 =
* Voter notification email message & subject filter added.

= 3.0.8 =
* Voter notification email related settings added from Buddypress > Settings > Email settings section.

= 3.0.9 =
 Wordpress Version > 4.3 and BP Version > 1.9
* Top Voter widget "WP_Widget is depreciated" solved
* "bp_core_add_notification is depreciated" solved.

= 3.0.10 =
* jquery-ui.min.css - minified version added
* jquery-ui.min.js - minified version added

= 3.0.11 =
* jquery-ui.min.js - minified version include method changed.

= 3.0.12 =
* voter notification enable/disable option - text changed.

= 3.0.13 =
* voter email unsubscribe function settings changed.

= 3.0.14 =
* API error which add "Author Votes"  mesage via action hook which was created problem.

= 3.0.15 =
* top voted list - problem - solved.

= 3.0.16 =
* top voted list - css problem for some themems - problem solved.

= 3.0.17 =
* Top Voter widget > while selected "Display Type" =  "image" or "Title & Image" > created css problem for some themes - SOLVED.

= 3.0.18 =
* Notification Popup (at top right corner of admin menu bar) - the link on voter like notification was wrong - CORRECTION DONE
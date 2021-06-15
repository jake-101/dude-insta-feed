# IG Graph Feed
WordPress plugin to get latest images from Instagram user feeds.

Basically this plugin fetches images from Instagram, saves those to transient and next requests will be served from there. After transient has expired and deleted, new fetch from Instagram will be made and saved to transient. This implements very simple cache.

Handcrafted with love by [jake101](https://jake101.com), a Los Angeles based ecommerce / front end consultant.

## NOTE!

**Instructions and more readable code coming soon. It is based on [Dude Insta Feed](https://github.com/digitoimistodude/dude-insta-feed) and the front-end code works with very little change, but requires a FB Graph API token but doesn't generate it itself.**

## Getting Started

* Create a FB app on [developers.facebook.com](https://developers.facebook.com)
* Get your long lived app token from the [FB token tool](https://developers.facebook.com/tools/accesstoken/)
* Add the token and your Instagram user ID inside your theme's functions.php file

Example:
`add_filter('dude-insta-feed/access_token/user=xxxx', function () {return 'tokenjklasdjklasd';});`

## Please note before using
This plugin is not meant to be "plugin for everyone", it needs at least some basic knowledge about php and css to add it to your site and making it look beautiful.

This is a plugin in development for now, so it may update very often.

## License
IG Graph Feed is released under the GNU GPL 2 or later.



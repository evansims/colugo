# Colugo

* Contributors: [@evansims](/evansims)
* Donate link: http://evansims.com/projects/colugo
* Tags: colugo, tweetie, image, publishing, hosted

Colugo is a simple PHP script that allows you to host your tweeted images using Tweetie 2 for iPhone on your own web server.

## Description

Colugo is a simple PHP script that allows you to host your tweeted images using Tweetie 2 for iPhone on your own web server. It writes author (your @username), date and copyright details onto the image. It can plug into Shaun Inman's Lessn to autoshorten the tweeted link to your image.

Colugo includes Jason Kottke's Silkscreen font for the text overlay.
http://kottke.org/plus/type/silkscreen/

## Requirements

Contact your web host to determine if they meet these requirements.

* Web server with FTP and PHP priviledges.
* Host that supports HTTP file_get_contents() if you intend on using Colugo with Lessn.
* Host must support GD if you wish to have tweet info written onto your images.

# Installation

1. Create a new directory on your web server via FTP. CHMOD that directory with appropriate write permissions (0755 usually works.)
	
2. Extract the zip archive and open "u.php" in your preferred plain-text editor.
	
3. Assign the configuration values at the top of the script. (See below for configuration options.)
	
4. Upload your modified u.php and the remainder of the contents of the archive to the directory you created.

## Configuration Options
**$localBaseURL** — Should point to the absolute URL where your images are being stored. i.e., If your u.php script is in /i, it might be http://yourdomain.com/i/. Be sure to include the last slash.
  	
**$twitterUsername** and **$twitterPassword** — Your Twitter username and password. The script does not use these, as it does not communicate with Twitter directly; however, Colugo checks this information against the incoming image provided by Tweetie to ensure that you are indeed the one posting the image. Security mechanism.
		
**$licenseText** — If you'd like license information to be written onto the image, provide it here.
		
**$lessnEndpoint** — Optional. If you have Shaun Inman's Lessn installed, provide the URL path to the admin interface, i.e. http://yourdomain.com/g/-/. Be sure to include the last slash.
		
**$lessnAPI** — Optional. If you have Shaun Inman's Lessn installed, provide your API key. The API key can be found after you've logged into your admin interface, at the bottom of the page (it's in light grey, so you might not notice it  at first.)

## Configure Tweetie

1. Go to Tweetie's accounts listing interface, and tap "Settings" in the lower, left hand corner.
2. Tap Image Service, and select Custom.
3. Change the URL to point to where you uploaded the "u.php" file; i.e. http://yourdomain.com/i/u.php.

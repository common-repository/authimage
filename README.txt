
WP AuthImage Plugin (v3.0)
written by Keith McDuffee (2004-6-7)
http://www.gudlyf.com
gudlyf@realistek.com

---

CHANGELOG:

3.0 -- Much more effective (too much?) captcha generation, thanks to the
       freely available VeriWord PHP class package
       (http://www.phpclasses.org/browse/package/1768.html).  In this release,
       I've decided to forget about the phoenetic text option.  If you want
       that option, just continue to use version 2.1.1.
       Note that quite a bit has changed in this README, so if you want to
       upgrade, read this document fully.

---

REQUIREMENTS:

This hack assumes the following for your blog.  Anything else is up to
the user to hack for their own use.  These are the only reqs that have been
fully tested up to this point.

- WordPress (http://wordpress.org/) version 1.2 or above (includes 1.5).
- Apache 1.3 or above running on Linux.
- PHP 4 or above.
- GD library (http://www.boutell.com/gd/) and Freetype library
  (http://www.freetype.org/) for PHP.  See http://us2.php.net/gd for more
  information.
- Some sort of shell access to your web server.

You can test your version of PHP for GD and Freetype with the following
commands "from a shell":

    php -i | grep "GD Support"

should return:

<tr><td class="e">GD Support </td><td class="v">enabled </td></tr>

and:

    php -i | grep "FreeType Support"

should return:

<tr><td class="e">FreeType Support </td><td class="v">enabled </td></tr>

---

INSTALLATION:

Following the directions below with the included files should allow you to
add authorization image checking for comments posted on your site.  It displays
an image with a random word from a supplied list that the commenter has to enter in order for
their comment to go through.  This should cut down on any bots out there from
spamming your comments area and perhaps remove the need for comment moderation.

NOTE: It's important to make the indicated changes to BOTH the regular AND the
popup versions of the comment pages.  If you don't do that, spammers may
perhaps find a way to target the unedited version of the page.

1. Put the file "authimage.php" and the directory "authimage-inc" in your WP
   plugins directory.  Keep reading past step 7 if you want to use the
   JavaScript form checking.

2. FOR WORDPRESS VERSIONS < 1.5, look for this in 'wp-comments.php' AND 'wp-comments-popup.php'.  FOR WORDPRESS VERSION 1.5, loog for this in your theme's 'comments.php' and/or 'comments-popup.php':

<label for="url"><?php _e("<acronym title=\"Uniform Resource Identifier\">URI</acronym>"); ?></label>

  and add this after it:

<p>
          <input type="text" name="code" id="code" value="<?php echo ""; ?>" size="28" tabindex="4" />
          <label for="code"><?php _e("Enter this code: "); ?></label>
          <img src="<?php global $authimage; echo $authimage; ?>" width="155" height="50" alt="authimage" />
</p>

3. FOR WORDPRESS VERSIONS < 1.5: Look for this in 'wp-comments-post.php':

if (strlen($url) < 7)
        $url = '';

  and add this after it:

// authimage -- Check for valid sized code
$code = trim(strip_tags($_POST['code']));

FOR WORDPRESS VERSION 1.5: Look for this in 'wp-comments-post.php':

$comment_content      = $_POST['comment'];

  and add this after it:

$comment_code         = $_POST['code'];  // AuthImage

4. FOR WORDPRESS VERSIONS < 1.5: You have two options next, both require editing 'wp-comments-post.php':

  To allow comments to come into the moderation pool, look for the following
  lines in 'wp-comments-post.php':

if(check_comment($author, $email, $url, $comment, $user_ip)) {
        $approved = 1;
} else {
        $approved = 0;
}

  and add this afterwards:

// authimage -- Check if valid code.  If not valid, send to moderation.
if ( !checkAICode($code) )
        $approved = 0;

  -or- if you want to dump the comment altogether and warn the commenter
  that an invalid code was entered, look for the following lines in
  'wp-comments-post.php':

if ( '' == $comment )
	die( __('Error: please type a comment.') );

  and add this after it:

if ( !checkAICode($code) )
        die( __('Error: please enter the valid authorization code.') );

FOR WORDPRESS VERSION 1.5:

Look for the following lines n 'wp-comments-post.php':

if ( '' == $comment_content )
        die( __('Error: please type a comment.') );

  and add this after it:

// AuthImage
if ( !checkAICode($comment_code) )
        die( __('Error: please enter the valid authorization code.') );

5. Make sure you've activated the AuthImage plugin.

6. Make sure your 'my-hacks.php' file contains what is in 'authimage-hacks.php'.
   THIS IS STILL REQUIRED!

7. Enable the 'my-hacks.php legacy support' from your WP options.

8. You can configure lots of how the captcha image appears by editing the
   'authimage-inc/veriword.ini' file.  Read the PDF documentation at
   'authimage-inc/manualveriword.pdf' for a list of options.  You can also
   alter the dictionary used to generate words as well as the length of
   the word generated.

--

8. FOR WORDPRESS VERSIONS < 1.5: Edit 'wp-comments.php' AND 'wp-comments-popup.php'
   and change the following line.  FOR WORDPRESS VERSION 1.5, this line is in
   'comments.php' AND 'comments-popup.php' in your theme's directory:

<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php"
method="post" id="commentform">

  to read:

<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php"
method="post" id="commentform" onSubmit="return testValues(this)">

9. If you want to validate the email address, edit the plugin and uncomment
   the code that checks for email.  That's it!

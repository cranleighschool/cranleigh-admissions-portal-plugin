# Cranleigh Admissions Portal Wordpress Plugin
A Wordpress Plugin that integrates with Cranleigh Schools' Admissions Portal. 

## What does this plugin do?
Chiefly it is the 'go-between' between the Admissions Portal (Laravel) and our website(s) (WordPress). It is under continious development. At the moment it:
* Allows for Documents hosted on the Admissions Portal to be displayed seemlessly on the Website(s) using a shortcode, that will automatically fill in the title, description, thumbnail, and download links. Keeping the same style as our other downloads on the websites. (Which generally use Download Manager). 

## How to install...
1. Download this plugin as a [Zip File](https://github.com/cranleighschool/cranleigh-admissions-portal-plugin/archive/master.zip). 
2. Upload it as a [new Plugin into your Wordpress installation](https://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/). 
3. Activate It. 
4. Find "Admissions Portal" underneath the Settings Menu. And fill in the blanks!


## How do I use it? 
### Download Document Shortcode
When you're writing your content in WordPress Admin, simply put the shortcode `[admissions-doc slug="prospectus"]`. Where the `slug` matches the slug in your admissions portal: eg: `https://admissions.cranleigh.org/documents/prospectus`. 


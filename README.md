# Keywords Example
This is an example of a Keywords plugin that will allow the user to:
- Add Multiple Keywords to a post via a custom repeater field (case sensitive)
- Allow users to search posts for said keywords (not case sensitive)
- Allow users visability of the keywords via a custom column structure within the posts list

## Installation
Clone this repo into the wp-content/plugins folder of your wordpress install then within your dashboard plugins section, activate "PA Keywords"

## Using the Plugin
The plugin can be user by navigating to the posts page and either editing and existing post or adding another

Keywords are added via entering the keyword into the input field and clicking go. This will let you add multiple keywords and will be saved in this order.

The user then saves the post and keywords are visible in the post list page. The user can then view the keywords for each post from this section or can search the posts via the core search function.

## Alternative solutions explored
The WP core utilises a similar scheme using **TAGS**, there is coding within this plugin that you can uncomment in the classes/class.keywords.php:

`$this->loader->add_action( 'init',  $this, 'tags_all' );`

`$this->loader->add_action( 'restrict_manage_posts', $this, 'custom_taxonomy_filters' );`

`$this->loader->add_filter( 'get_terms_args', $this, 'order_terms_args' );`

### Limitiations with Tag Method
The search feature was unreliable and inflexible in what tags would be returned, however, applying a custom filter allowed to search by available tags. This would becoming un-managable with many keywords.

The tags, due to having slugs, could not be case sensitive when adding. Therefore 'TopGear' and 'TOPGEAR' were not allowed.

## Gulp Build
A gulp file has been added with some overkill on features. Sass has been commented out due to not using any css for this plugin, however the relevant files have been included to show structure

To run the plugin on a local install, ensure the plugin is installed on a local copy of WordPress and you are using some local environment server such as MAMP. Create an environment with url (in this case http://testlocal.local:8888). If the URL is different, please enter the relevant url in the gulp file.

To ensure gulp is working head to terminal and enter:

`npm install`
`gulp build`

This should automatically serve the build to your browser and provide you with an IP address that can be used by other devices to test. When changes are made to the php / css / js files all browsers on all connected devices will automatically change.

## Continuous Integration
This plugin was built in mind to deploy to the WP Plugin Directory using Travis CI. I have removed the login details required to do this for security. A Configuration file and scripts folder is set up to utilise for this. If not required, please remove.

## Testing
PHPUnit and WP-CLI were used and recommended for ongoing testing of this feature in order to expand. 

## Moving it forward
Usability wise, the plugin would benefit from a seperate edit page that allowed all keywords to be visable to the users with links directly to what post they lay in. From here, they would be editable and removed/updated from the posts they are attached with

The Repeater field would also be more UI friendly in terms of style and functionality. The mutiple input fields that prepend on addition, would be instead UI boxes (similar to the tag feature) that the user could delete on the fly







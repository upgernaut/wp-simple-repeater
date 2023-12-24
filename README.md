# wp-simple-repeater
Simple repeater class for WordPress

#Use it this way from your theme functions.php or plugin

```php
// Include your class file in your theme functions.php
require_once get_template_directory() . '/Repeater.php';

// or

// Include your class file in your plugin
require_once plugin_dir_path(__FILE__) . 'Repeater.php';

// Instantiate the class with parameters
$params = array(
    'metaBoxId' => 'yourMetaBoxId',
    'metaBoxRepeaterItemName' => 'yourMetaBoxId',
    'metaBoxPostType' => 'post',
    'metaBoxTitle' => 'yourTitle',
);

// Create an instance of the CustomMetaBox class
$custsomMetaBox = new CustomMetaBox($params);
```
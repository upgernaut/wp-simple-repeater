# wp-simple-repeater
Simple repeater class for WordPress

#Use it this way from your theme functions.php or plugin

```php
// Include your class file
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
<?php
class CustomMetaBox {

    private $params;
    private $metaBoxId;
    private $metaBoxTitle;
    private $metaBoxPostType;
    private $metaBoxRepeaterItemName;

    public function __construct(array $params) {
        add_action('admin_init', array($this, 'registerMetaBox'));
        add_action('save_post', array($this, 'saveMetaBox'));
        add_action('admin_footer', array($this, 'metaBoxFooter'));
        add_action('admin_head', array($this, 'metaBoxHeader'));

        $this->params = $params;
        $this->metaBoxId = $params['metaBoxId'] ?? 'single-repeater';
        $this->metaBoxTitle = $params['metaBoxTitle'] ?? 'Single Repeater';
        $this->metaBoxPostType = $params['metaBoxPostType'] ?? 'post';
        $this->metaBoxRepeaterItemName = $params['metaBoxRepeaterItemName'] ?? 'custom_repeater_item';

    }

    public function registerMetaBox() {
        add_meta_box($this->metaBoxId, $this->metaBoxTitle, array($this, 'metaBoxCallback'), $this->metaBoxPostType, 'normal', 'default');
    }

    public function metaBoxCallback($post) {
        $customRepeaterItem = get_post_meta($post->ID, $this->metaBoxRepeaterItemName. '_item', true);
        wp_nonce_field('repeaterBox', 'formType');
        ?>
        <table class="repeater-item-table">
            <tbody>
                <?php 
                if ($customRepeaterItem) {
                    foreach ($customRepeaterItem as $itemKey => $itemValue) {
                        $item1 = isset($itemValue['item1']) ? $itemValue['item1'] : '';
                        $item2 = isset($itemValue['item2']) ? $itemValue['item2'] : '';
                        ?>
                        <tr class="repeater-sub-row">                
                            <td>
                                <input type="text" name="<?php echo esc_attr($this->metaBoxRepeaterItemName . '_item['.$itemKey.'][item1]'); ?>" value="<?php echo esc_attr($item1); ?>" placeholder="Item 1">
                            </td>
                            <td>
                                <input type="text" name="<?php echo esc_attr($this->metaBoxRepeaterItemName . '_item['.$itemKey.'][item2]'); ?>" value="<?php echo esc_attr($item2); ?>" placeholder="Item 2"/>
                            </td>
                            <td>
                                <button class="repeater-remove-item button" type="button"><?php esc_html_e('Remove', 'repeater-meta-box'); ?></button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr class="repeater-sub-row">                
                        <td>
                            <input type="text" name="<?php echo $this->metaBoxRepeaterItemName; ?>_item[0][item1]" placeholder="Item 1">
                        </td>
                        <td>
                            <input type="text" name="<?php echo $this->metaBoxRepeaterItemName; ?>_item[0][item2]" placeholder="Item 2"/>
                        </td>
                        <td>
                            <button class="repeater-remove-item button" type="button"><?php esc_html_e('Remove', 'repeater-meta-box'); ?></button>
                        </td>
                    </tr>
                    <?php
                }
                ?>			
                <tr class="repeater-hide-tr">				
                    <td>
                        <input name="hide_<?php echo $this->metaBoxRepeaterItemName; ?>_item[rand_no][item1]" type="text" placeholder="Item 1"/>	
                    </td>
                    <td>
                        <input type="text" name="hide_<?php echo $this->metaBoxRepeaterItemName; ?>_item[rand_no][item2]" placeholder="Item 2"/>
                    </td>
                    <td>
                        <button class="repeater-remove-item button" type="button"><?php esc_html_e('Remove', 'repeater-meta-box'); ?></button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <button class="repeater-add-item button button-secondary" type="button"><?php esc_html_e('Add another', 'repeater-meta-box'); ?></button>
                    </td>
                </tr>
            </tfoot>
        </table>	
        <?php
    }

    public function saveMetaBox($postId) {
        if (!isset($_POST['formType']) && !wp_verify_nonce($_POST['formType'], 'repeaterBox')) {
            return;
        }

        if (!defined('DOING_AUTOSAVE')) {
            define('DOING_AUTOSAVE', true);
        }

        if (!current_user_can('edit_post', $postId)) {
            return false;
        }

        if (isset($_POST[$this->metaBoxRepeaterItemName . '_item'])) {
            update_post_meta($postId, $this->metaBoxRepeaterItemName . '_item', $_POST[$this->metaBoxRepeaterItemName . '_item']);
        } else {
            update_post_meta($postId, $this->metaBoxRepeaterItemName . '_item', '');
        }	
    }

    public function metaBoxFooter() {
        ?>
        <script type="text/javascript">		
            jQuery(document).ready(function($) {
                jQuery(document).on('click', '.repeater-remove-item', function() {
                    jQuery(this).parents('tr.repeater-sub-row').remove();
                }); 				
                jQuery(document).on('click', '.repeater-add-item', function() {
                    var p_this = jQuery(this);    
                    var row_no = parseFloat(jQuery('.repeater-item-table tr.repeater-sub-row').length);
                    var row_html = jQuery('.repeater-item-table .repeater-hide-tr').html().replace(/rand_no/g, row_no).replace(/hide_<?php echo $this->metaBoxRepeaterItemName ?>_item/g, '<?php echo $this->metaBoxRepeaterItemName ?>_item');
                    jQuery('.repeater-item-table tbody').append('<tr class="repeater-sub-row">' + row_html + '</tr>');    
                });
            });
        </script>
        <?php
    }

    public function metaBoxHeader() {
        ?>
        <style type="text/css">
            .repeater-item-table, .repeater-item-table .repeater-sub-row input[type="text"]{ width: 100%; }
            .repeater-hide-tr{ display: none; }
        </style>
        <?php
    }
}


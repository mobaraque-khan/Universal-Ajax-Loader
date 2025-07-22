<?php
if (!defined('ABSPATH')) exit;

class UAL_Admin {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'register_menu']);
    }

    public function register_settings() {
        add_option('ual_post_type', 'post');
        register_setting('ual_options_group', 'ual_post_type');

        add_option('ual_posts_per_page', 6);
        register_setting('ual_options_group', 'ual_posts_per_page');
    }

    public function register_menu() {
        add_options_page('Universal Ajax Loader Settings', 'Ajax Loader', 'manage_options', 'ual', [$this, 'settings_page']);
    }

    public function settings_page() {
        ?>
            <div class="ual-wrapper">
                <h2>⚙️ Universal Ajax Loader Settings</h2>
                <form method="post" action="options.php">
                    <?php settings_fields('ual_options_group'); ?>

                    <div class="ual-field">
                        <label for="ual_post_type">📄 Select Post Type</label>
                        <select name="ual_post_type" id="ual_post_type">
                            <?php
                            $post_types = get_post_types(['public' => true], 'objects');
                            $selected = get_option('ual_post_type', 'post');
                            foreach ($post_types as $post_type) {
                                echo '<option value="' . esc_attr($post_type->name) . '" ' . selected($selected, $post_type->name, false) . '>' . esc_html($post_type->labels->singular_name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="ual-field">
                        <label for="ual_posts_per_page">🔢 Posts Per Page</label>
                        <input type="number" name="ual_posts_per_page" id="ual_posts_per_page" value="<?php echo esc_attr(get_option('ual_posts_per_page', 6)); ?>" min="1" />
                    </div>

                    <?php submit_button('💾 Save Settings'); ?>
                </form>
                <hr style="margin:30px 0;">
                <div class="ual-field">
                    <label for="ual-shortcode"><strong>📋 Shortcode</strong></label>
                    <div style="display:flex; gap:10px;">
                        <input type="text" readonly id="ual-shortcode" value="[ajax_post_loader]" style="flex:1; padding:10px; border-radius:6px; border:1px solid #ccc;">
                        <button type="button" class="button button-secondary" onclick="ualCopyShortcode()">Copy</button>
                    </div>
                    <small>Use this shortcode on any page or post.</small>
                </div>
            </div>
            <script>
                function ualCopyShortcode() {
                    const shortcodeInput = document.getElementById('ual-shortcode');
                    shortcodeInput.select();
                    shortcodeInput.setSelectionRange(0, 99999); // For mobile
                    document.execCommand("copy");

                    // Visual feedback (optional)
                    alert('✅ Shortcode copied: ' + shortcodeInput.value);
                }
            </script>
        <?php
    }
}



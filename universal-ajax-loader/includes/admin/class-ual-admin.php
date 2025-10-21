<?php
if (!defined('ABSPATH')) exit;

class UAL_Admin {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_ual') return;
        
        wp_enqueue_style('ual-admin-style', plugin_dir_url(__FILE__) . '../../assets/admin/css/admin-style.css', [], '1.0.0');
        wp_enqueue_script('ual-admin-script', plugin_dir_url(__FILE__) . '../../assets/admin/js/admin-script.js', [], '1.0.0', true);
    }

    public function register_settings() {
        add_option('ual_post_type', 'post');
        register_setting('ual_options_group', 'ual_post_type');

        add_option('ual_posts_per_page', 6);
        register_setting('ual_options_group', 'ual_posts_per_page');

        add_option('ual_infinite_scroll', 0);
        register_setting('ual_options_group', 'ual_infinite_scroll');

        add_option('ual_post_layout', 'default');
        register_setting('ual_options_group', 'ual_post_layout');

        add_option('ual_product_layout', 'default');
        register_setting('ual_options_group', 'ual_product_layout');
    }

    public function register_menu() {
        add_options_page('Universal Ajax Loader Settings', 'Ajax Loader', 'manage_options', 'ual', [$this, 'settings_page']);
    }

    public function settings_page() {
        ?>
        <div class="wrap ual-admin-wrap">
            <div class="ual-header">
                <h1><span class="ual-icon">⚡</span> Universal Ajax Loader</h1>
                <p class="ual-subtitle">Configure your Ajax loading settings with ease</p>
            </div>

            <div class="ual-container">
                <div class="ual-main-content">
                    <div class="ual-card">
                        <div class="ual-card-header">
                            <h2><span class="dashicons dashicons-admin-settings"></span> Configuration</h2>
                            <p>Customize how your content loads</p>
                        </div>
                        
                        <form method="post" action="options.php" class="ual-form">
                            <?php settings_fields('ual_options_group'); ?>
                            
                            <div class="ual-form-grid">
                                <div class="ual-form-group">
                                    <label for="ual_post_type" class="ual-label">
                                        <span class="dashicons dashicons-admin-post"></span>
                                        Post Type
                                    </label>
                                    <select name="ual_post_type" id="ual_post_type" class="ual-select">
                                        <?php
                                        $post_types = get_post_types(['public' => true], 'objects');
                                        $selected = get_option('ual_post_type', 'post');
                                        foreach ($post_types as $post_type) {
                                            echo '<option value="' . esc_attr($post_type->name) . '" ' . selected($selected, $post_type->name, false) . '>' . esc_html($post_type->labels->singular_name) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <small class="ual-help">Choose which content type to load</small>
                                </div>

                                <div class="ual-form-group">
                                    <label for="ual_posts_per_page" class="ual-label">
                                        <span class="dashicons dashicons-grid-view"></span>
                                        Items Per Load
                                    </label>
                                    <input type="number" name="ual_posts_per_page" id="ual_posts_per_page" 
                                           value="<?php echo esc_attr(get_option('ual_posts_per_page', 6)); ?>" 
                                           min="1" max="50" class="ual-input" />
                                    <small class="ual-help">Number of items to load at once (1-50)</small>
                                </div>

                                <div class="ual-form-group">
                                    <label class="ual-label ual-toggle-label">
                                        <span class="dashicons dashicons-update"></span>
                                        Infinite Scroll
                                        <div class="ual-toggle">
                                            <input type="checkbox" name="ual_infinite_scroll" id="ual_infinite_scroll" 
                                                   value="1" <?php checked(get_option('ual_infinite_scroll', 0), 1); ?> />
                                            <span class="ual-toggle-slider"></span>
                                        </div>
                                    </label>
                                    <small class="ual-help">Auto-load content when scrolling to bottom</small>
                                </div>

                                <div class="ual-form-group">
                                    <label for="ual_post_layout" class="ual-label">
                                        <span class="dashicons dashicons-layout"></span>
                                        Post Layout
                                    </label>
                                    <select name="ual_post_layout" id="ual_post_layout" class="ual-select">
                                        <option value="default" <?php selected(get_option('ual_post_layout', 'default'), 'default'); ?>>Default</option>
                                    </select>
                                    <small class="ual-help">Choose layout style for posts</small>
                                </div>

                                <div class="ual-form-group">
                                    <label for="ual_product_layout" class="ual-label">
                                        <span class="dashicons dashicons-products"></span>
                                        Product Layout
                                    </label>
                                    <select name="ual_product_layout" id="ual_product_layout" class="ual-select">
                                        <option value="default" <?php selected(get_option('ual_product_layout', 'default'), 'default'); ?>>Default</option>
                                    </select>
                                    <small class="ual-help">Choose layout style for products</small>
                                </div>
                            </div>

                            <div class="ual-form-actions">
                                <?php submit_button('Save Changes', 'primary ual-btn-primary', 'submit', false); ?>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="ual-sidebar">
                    <div class="ual-card ual-shortcode-card">
                        <div class="ual-card-header">
                            <h3><span class="dashicons dashicons-shortcode"></span> Shortcode</h3>
                            <p>Copy and paste to use</p>
                        </div>
                        
                        <div class="ual-shortcode-container">
                            <div class="ual-shortcode-input-group">
                                <input type="text" readonly id="ual-shortcode" value="[ajax_post_loader]" class="ual-shortcode-input">
                                <button type="button" class="ual-copy-btn" onclick="ualCopyShortcode()">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </button>
                            </div>
                            <div class="ual-copy-feedback" id="ual-copy-feedback">Copied!</div>
                        </div>
                    </div>

                    <div class="ual-card ual-info-card">
                        <div class="ual-card-header">
                            <h3><span class="dashicons dashicons-info"></span> Quick Guide</h3>
                        </div>
                        <ul class="ual-info-list">
                            <li><strong>Step 1:</strong> Configure your settings above</li>
                            <li><strong>Step 2:</strong> Copy the shortcode</li>
                            <li><strong>Step 3:</strong> Paste it in any post or page</li>
                            <li><strong>Step 4:</strong> Enjoy seamless Ajax loading!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        function ualCopyShortcode() {
            const shortcodeInput = document.getElementById('ual-shortcode');
            const feedback = document.getElementById('ual-copy-feedback');
            
            shortcodeInput.select();
            shortcodeInput.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                feedback.classList.add('show');
                setTimeout(() => {
                    feedback.classList.remove('show');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy shortcode:', err);
            }
        }
        </script>
        <?php
    }
}
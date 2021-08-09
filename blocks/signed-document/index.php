<?php
class BlockSignedDocument {
    public $block_name;
    public $block_slug;
    public $block_description;
    public $block_svg;

    public function __construct() {
        $this->block_name = "Signed Document";
        $this->block_slug = "signed-document";
        $this->block_description = "A custom signed document block.";
        $this->block_svg = file_get_contents(__DIR__ . '/icon.svg');
    }
    public function initialize() {
        require_once __DIR__ . "/variables.php";
        add_action('acf/init', array($this, 'acf_block_init'));
    }
    public function acf_block_init() {
        if( function_exists('acf_register_block_type') ) {        
            acf_register_block_type(
                array(
                    'name'              => $this->block_slug,
                    'title'             => __($this->block_name),
                    'description'       => __($this->block_description),
                    'render_callback'   => array($this, 'acf_block_render_callback'),
                    'category'          => 'bootstrap',
                    'icon'              => $this->block_svg,
                    'keywords'          => array($this->block_slug),
                    'supports'          => array( 'mode' => false ),
                    'enqueue_assets'    => array($this, 'signed_document_script'),
                )
            );
        }
    }
    function signed_document_script() {
        if(!is_admin()) {
            wp_enqueue_style( 'cen-google-fonts', "https://fonts.googleapis.com/css2?family=Yellowtail&display=swap", array(), _S_VERSION );
            // wp_enqueue_script( $this->block_slug. "-signature", 'https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js', array(), '1.0.0', false );
            wp_enqueue_script( $this->block_slug. "-script", plugin_dir_url( __FILE__ ) . $this->block_slug.'.js', array(), '1.0.0', false );
        }
    }
    function acf_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
        $id = $this->block_slug.'-'.$block['id'];
        $className = $this->block_slug;

        if( !empty($block['anchor']) ) {
            $id = $block['anchor'];
        }
        if( !empty($block['className']) ) {
            $className .= ' ' . $block['className'];
        }
        if( !empty($block['align']) ) {
            $className .= ' align' . $block['align'];
        }
        ?>
        <section id="<?php echo esc_attr($id); ?>" class="blocks-<?php echo $this->block_slug; ?> <?php echo esc_attr($className); ?>">
            <?php if ($is_preview == '1') : ?>
            <div class="editor-view">
                <div>
                    <?php echo $this->block_svg; ?>
                </div>
                <div>
                    <b><?php echo $this->block_name; ?>:</b><br><?php echo $this->block_description; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="columns is-centered">
                <div class="column has-text-centered">
                    <div class="signed-efc">
                        <div class="signed-efc-inner">
                            <img data-src="<?php echo plugin_dir_url( __FILE__ ); ?>/image.php" src="<?php echo plugin_dir_url( __FILE__ ); ?>/document.jpg"/>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <input class="input signature" type="text" placeholder="Enter your name...">
                        </div>
                        <p class="help">Enter your name</p>
                        <div class="control">
                            <a href="#"  download="signed-efc.jpg" disabled class="button is-primary download-link">Download It</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </section>
    <?php
    }
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('ACF') ) {
    $BlockSignedDocument = new BlockSignedDocument();
    $BlockSignedDocument->initialize();
}

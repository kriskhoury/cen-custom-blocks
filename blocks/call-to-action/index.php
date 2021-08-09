<?php
class BlockCallToAction {
    public $block_name;
    public $block_slug;
    public $block_description;
    public $block_svg;

    public function __construct() {
        $this->block_name = "Call To Action";
        $this->block_slug = "call-to-action";
        $this->block_description = "A custom call-to-action block.";
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
                    'mode'              => 'edit',
                    'supports'          => array( 'mode' => true ),
                    'enqueue_assets'    => array($this, 'call_to_action_script'),
                )
            );   
        }
    }
    function call_to_action_script() {
        if(!is_admin()) {
            // wp_enqueue_script( $this->block_slug. "-script", plugin_dir_url( __FILE__ ) . $this->block_slug.'.js', array(), '1.0.0', false );
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

        $image = get_field('image');
        $title = get_field('title');
        $description = get_field('description');
        $button = get_field('button');
        ?>
        <section id="<?php echo esc_attr($id); ?>" class="blocks-<?php echo $this->block_slug; ?> <?php echo esc_attr($className); ?>">
            <div class="card">
                <?php if($image){ ?>
                <div class="card-image" style="background-image: url('<?php echo $image['sizes']['thumbnail']; ?>')">
                </div>
                <?php } ?>
                <div class="card-content">
                    <div class="content">
                        <b><?php echo $title; ?></b>
                        <p><?php echo $description; ?></p>
                        <div class="wp-block-buttons">
                            <div class="wp-block-button">
                                <a href="<?php echo $button['url']; ?>" class="wp-block-button__link has-white-color <?php echo $button['background_color']; ?> has-text-color has-background no-border-radius"><?php echo $button['label']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php 
    }
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('ACF') ) {
    $BlockCallToAction = new BlockCallToAction();
    $BlockCallToAction->initialize();
}

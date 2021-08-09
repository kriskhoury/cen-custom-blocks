<?php
class BlockThreeColumnNews {
    public $block_name;
    public $block_slug;
    public $block_description;
    public $block_svg;

    public function __construct() {
        $this->block_name = "Three Column News";
        $this->block_slug = "three-column-news";
        $this->block_description = "A custom three column news block.";
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
                    'enqueue_assets'    => array($this, 'three_column_news_script'),
                )
            );
        }
    }
    function three_column_news_script() {
        if(!is_admin()) {
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

        // FILTER BY PAGE COUNT
        $posts_per_page = 3;
        if(get_field('result_count')){
            $posts_per_page = get_field('result_count');
        }

        $args = array (
            'posts_per_page' => $posts_per_page,
            'post_type' => 'post',
        );

        // FILTER BY TAXONOMY
        if(get_field('filter_by_category')){
            $tax_query =   array();
            $tax_query[] = array(
                'taxonomy'      => 'category',
                'field'         => 'id',
                'terms'         => get_field('filter_by_category'),
            );
            $args['tax_query'] = $tax_query;
        }

        $query = new WP_Query($args);

        // BUTTON LABEL
        $button_label = "Read More";
        if(get_field('button_label')){
            $button_label = get_field('button_label');
        }
        // CARD HEIGHT
        $card_height = "600px";
        if(get_field('card_height')){
            $card_height = get_field('card_height');
        }
        // CARD COLOR
        $card_color = "#fff";
        if(get_field('card_color')){
            $card_color = get_field('card_color');
        }
        // CARD TITLE COLOR
        $card_title_color = "#797979";
        if(get_field('card_title_color')){
            $card_title_color = get_field('card_title_color');
        }
        ?>
        <section id="<?php echo esc_attr($id); ?>" class="blocks-<?php echo $this->block_slug; ?> <?php echo esc_attr($className); ?>">
            
            <?php if($posts_per_page >= 3){ ?>
            <div class="slider-buttons">
                <div class="slider-buttons-left">
                    <a href="#" class="slider-button-previous" style="background-image:  url('<?php echo plugin_dir_url( __FILE__ ); ?>arrow-left.svg')"></a>
                </div>
                <div class="slider-buttons-right">
                    <a href="#" class="slider-button-next" style="background-image:  url('<?php echo plugin_dir_url( __FILE__ ); ?>arrow-right.svg')"></a>
                </div>
            </div>
            <?php }?>
            <div class="slider-wrapper">
                <div class="columns" style="left: 0px;">
                <?php
                if ($query->have_posts()) : 
                  while ($query->have_posts()) {
                    $query->the_post();
                    $title = get_the_title($query->post->ID);
                    $date = get_the_date('D M j',$query->post->ID);
                    $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
                    $link = get_permalink();
                    $target = "";
                    if(!has_post_thumbnail()){
                        $featured_img_url = get_field('news_image','options');
                    }
                    if(get_field('direct_external_link',$query->post->ID)){
                        $link = get_field('direct_external_link',$query->post->ID);
                        $target = " target=_blank";
                    }
                    ?>
                  <div class="column is-4">
                    <div class="card" style="height: <?php echo $card_height; ?>px; background-color: <?php echo $card_color; ?>;">
                      <div class="card-image">
                        <time datetime="<?php echo $date; ?>"><?php echo $date; ?></time>
                        <figure class="image" style="background-image: url('<?php echo $featured_img_url; ?>');"></figure>
                      </div>
                      <div class="card-content">
                        <div class="content">
                          <h3 style="color: <?php echo $card_title_color; ?>"><?php echo $title; ?></h3>
                          <!-- <h6>by <?php the_author(); ?></h6> -->
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="wp-block-buttons">
                          <div class="wp-block-button">
                            <a href="<?php echo $link; ?>" <?php echo $target; ?> class="wp-block-button__link has-white-color has-dark-gray-background-color has-text-color has-background no-border-radius"><?php echo $button_label; ?></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  }
                wp_reset_postdata();
                endif; 
                ?>
                </div>
            </div>
        </section>
    <?php 
    }
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('ACF') ) {
    $BlockThreeColumnNews = new BlockThreeColumnNews();
    $BlockThreeColumnNews->initialize();
}

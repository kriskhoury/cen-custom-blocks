<?php
class BlockTeamCards {
    public $block_name;
    public $block_slug;
    public $block_description;
    public $block_svg;

    public function __construct() {
        $this->block_name = "Team Cards";
        $this->block_slug = "team-cards";
        $this->block_description = "A custom team-cards block.";
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

        $which_team = get_field('which_team');
        $columns = get_field('columns');
        $sort_by = get_field('sort_by');

        $args = array (
            'posts_per_page' => -1,
            'post_type' => 'person',
        );

        $tax_query = array();
        if($which_team){
            $tax_query[] = array(
                'taxonomy' => 'group',
                'field'    => 'term_id',
                'terms'    => $which_team,
            );
            $args['tax_query'] = $tax_query;
        }

        if($sort_by){
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
            $args['meta_key'] = $sort_by;
        }

        $query = new WP_Query($args);
        ?>
        <section id="<?php echo esc_attr($id); ?>" class="blocks-<?php echo $this->block_slug; ?> <?php echo esc_attr($className); ?>">
            <div class="columns is-multiline is-variable">
                <?php
                if ($query->have_posts()) : 
                    while ($query->have_posts()) {
                        $query->the_post();

                        $featured_image = get_the_post_thumbnail_url(get_the_ID(),'large');
                        $active = get_field('active',get_the_ID());
                        $professional_title = get_field('professional_title',get_the_ID());
                        $state = get_field('state',get_the_ID());

                        $email = get_field('email',get_the_ID());
                        $linkedin = get_field('linkedin',get_the_ID());
                        $twitter = get_field('twitter',get_the_ID());
                        $facebook = get_field('facebook',get_the_ID());

                        if($active){
                        ?>
                        <div class="column <?php echo $columns; ?>">
                            <div class="card">
                                <div class="card-image">
                                    <figure class="image">
                                        <img src="<?php echo $featured_image; ?>" alt="<?php echo the_title(); ?>">
                                    </figure>
                                </div>
                                <div class="card-content">
                                    <div class="content">
                                        <div><b><?php echo the_title(); ?></b></div>
                                        <div><?php echo $professional_title; ?></div>
                                        <!-- <div><?php echo convertState($state); ?></div> -->
                                    </div>
                                </div>
                                <footer class="card-footer">
                                    <div class="card-footer-item">
                                        <a href="<?php echo the_permalink(); ?>">Read Bio</a>
                                    </div>
                                    <?php if($email || $linkedin || $twitter || $facebook){ ?>
                                    <div class="card-footer-item">
                                        <ul class="social-media">
                                            <?php if($email){ ?>
                                            <li><a href="mailto:<?php echo $email; ?>" target=_blank title="Email"><i class="fas fa-envelope"></i></a></li>
                                            <?php } ?>
                                            <?php if($linkedin){ ?>
                                            <li><a href="<?php echo $linkedin; ?>" target=_blank title="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                                            <?php } ?>
                                            <?php if($twitter){ ?>
                                            <li><a href="<?php echo $twitter; ?>" target=_blank title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                            <?php } ?>
                                            <?php if($facebook){ ?>
                                            <li><a href="<?php echo $facebook; ?>" target=_blank title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </footer>
                            </div>
                        </div>
                        <?php
                        }
                    }
                else:
                ?>
                <div class="p-5 aligncenter">No team members to show yet!</div>
                <?php
                endif;
                ?>
            </div>
        </section>
    <?php 
    }
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('ACF') ) {
    $BlockTeamCards = new BlockTeamCards();
    $BlockTeamCards->initialize();
}

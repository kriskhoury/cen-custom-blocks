<?php
class BlockEventsCalendar {
    public $block_name;
    public $block_slug;
    public $block_description;
    public $block_svg;

    public function __construct() {
        $this->block_name = "Events Calendar";
        $this->block_slug = "events-calendar";
        $this->block_description = "A custom events calendar block.";
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
                    'enqueue_assets'    => array($this, 'events_calendar_script'),
                )
            );   
        }
    }
    function signed_document_script() {
        if(!is_admin()) {
            // wp_enqueue_script( $this->block_slug. "-script", plugin_dir_url( __FILE__ ) . $this->block_slug.'.js', array('vuejs'), '1.0.0', false );
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
          <div class="container-xl">
            <div class="row">
              <div class="col-12">
                <?php
                function change_date()
                {
                    $month =        (isset($_GET['_m'])) ? $_GET['_m'] : date("n");
                    $month_name =   date("F", mktime(0, 0, 0, $month, 10));
                    $year =         (isset($_GET['_y'])) ? $_GET['_y'] : date("Y");

                    $prev_month =   ($month == 1) ? 12 : $month-1;
                    $next_month =   ($month == 12) ? 1 : $month+1;
                    $prev_year =    ($month == 1) ? $year-1 : $year;
                    $next_year =    ($month == 12) ? $year+1 : $year;
                    $prev_path =    '?_m='.$prev_month.'&_y='.$prev_year;
                    $next_path =    '?_m='.$next_month.'&_y='.$next_year;
                    ?>
                    <div class="calendar-actions">
                      <form method="get">

                          <div class="columns">
                              <div class="column is-half is-offset-one-quarter">
                                  <nav class="level">
                                    <div class="level-item has-text-centered">
                                      <div>
                                        <a href="<?php echo $prev_path; ?>" class="btn btn-secondary">Prev</a>
                                      </div>
                                    </div>
                                    <div class="level-item has-text-centered">
                                      <div>
                                        <select name="_m" class="form-select">
                                        <?php
                                        for ($i=1; $i < 13; $i++) {
                                            $name = date('F', mktime(0, 0, 0, $i, 10));
                                            $selected = ($month == $i) ? 'selected' : '';
                                            echo '<option value="'.$i.'" '.$selected.'>'.$name.'</option>';
                                        }
                                        ?>
                                        </select>
                                        <select name="_y" class="form-select">  
                                          <?php
                                          $current_year = date('Y');
                                          $years = array();
                                          $years[] = $current_year-2;
                                          $years[] = $current_year-1;
                                          $years[] = $current_year;
                                          $years[] = $current_year+1;
                                          $years[] = $current_year+2;
                                          foreach ($years as $key => $value) {
                                            $selected = ($year == $value) ? 'selected' : '';
                                            echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
                                          }
                                          ?>
                                        </select>
                                        <input type="submit" value="Go" class="btn btn-primary" />
                                      </div>
                                    </div>
                                    <div class="level-item has-text-centered">
                                      <div>
                                        <a href="<?php echo $next_path; ?>" class="btn btn-secondary">Next</a>
                                      </div>
                                    </div>
                                  </nav>
                              </div>
                          </div>
                      </form>
                    </div>
                <?php
                }

                function check_day($day)
                {
                    $month =        (isset($_GET['_m'])) ? $_GET['_m'] : date("n");
                    $year =         (isset($_GET['_y'])) ? $_GET['_y'] : date("Y");

                    $html = '';

                    $date_now = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));

                    $meta_query =   array();
                    $meta_query[] = array('relation' => 'AND');
                    $meta_query[] = array(
                        'key'           => 'event_date',
                        'value'         => $date_now.' 00:00:00',
                        'compare'       => '=',
                        'type'          => 'DATE',
                    );

                    $args = array(
                      'post_type'             => 'event',
                      'meta_query'            => $meta_query,
                    );

                    $query = new WP_Query($args);

                    while ( $query->have_posts() ) {

                      $query->the_post();

                      $terms = wp_get_post_terms( $query->post->ID, 'event_type' )[0];
                      $class_array = array('event', "type-".$terms->slug);
                      $classes = implode(" ", $class_array);
                      $url = get_field('destination_url');

                      $html .= '<div class="'.$classes.'">';
                      if($url){
                        $html .= '<a href="'.$url.'">';
                      }
                      $html .= get_the_title($query->post->ID);
                      if($url){
                        $html .= '</a>';
                      }
                      $html .= '</div>';

                    }
                    wp_reset_postdata();
                    return $html;
                }

                function draw_calendar()
                {
                    $month =        (isset($_GET['_m'])) ? $_GET['_m'] : date("n");
                    $month_name =   date("F", mktime(0, 0, 0, $month, 10));
                    $year =         (isset($_GET['_y'])) ? $_GET['_y'] : date("Y");

                    $today =              date("d-m-Y");
                    $headings =           array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
                    $running_day =        date('w', mktime(0, 0, 0, $month, 1, $year));
                    $days_in_month =      date('t', mktime(0, 0, 0, $month, 1, $year));
                    $days_in_this_week =  1;
                    $day_counter =        0;
                    $dates_array =        array();

                    echo change_date();

                    echo '<table class="calendar">';
                    echo '<tr class="calendar-row">';
                    echo '<td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">', $headings).'</td>';
                    echo '</tr>';
                    echo '<tr class="calendar-row">';

                    for($x = 0; $x < $running_day; $x++):
                        echo '<td class="calendar-day-np"> </td>';
                        $days_in_this_week++;
                    endfor;

                    for($list_day = 1; $list_day <= $days_in_month; $list_day++):

                        $this_date =    date("d-m-Y", mktime(0, 0, 0, $month, $list_day, $year));
                        $isToday =      ($today == $this_date) ? ' today' : '';
                        $events =       check_day($list_day);
                        $isEmpty =      (empty($events)) ? ' calendar-day-empty' : '';

                        echo '<td class="calendar-day'.$isToday.''.$isEmpty.'">';
                        echo '<span class="day-number">'.$list_day.'</span>';
                        echo $events;
                        echo '</td>';

                        if($running_day == 6) :
                            echo '</tr>';
                            if(($day_counter+1) != $days_in_month) :
                                echo '<tr class="calendar-row">';
                            endif;
                            $running_day = -1;
                            $days_in_this_week = 0;
                        endif;
                        $days_in_this_week++; $running_day++; $day_counter++;
                    endfor;

                    if($days_in_this_week < 8) :
                        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                            echo '<td class="calendar-day-np"> </td>';
                        endfor;
                    endif;

                    echo '</tr>';
                    echo '</table>';

                    $tmpDate = date('Y-m-d');
                }

                draw_calendar();
                ?>
              </div>
            </div>
          </div>
        </section>
    <?php 
    }
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists('ACF') ) {
    $BlockEventsCalendar = new BlockEventsCalendar();
    $BlockEventsCalendar->initialize();
}

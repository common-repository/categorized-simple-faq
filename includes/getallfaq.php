<?php
if (!defined('ABSPATH'))
    exit;

class Categorised_Getallfaq
{

    public function __construct()
    {
        $this->simfaq = new Categorised_Simplefaqinit();
    }

    function Categorised_getallfaqshortcode($attribute)
    {
        extract(shortcode_atts(array(
            "limit" => '',
            "category" => '',
        ), $attribute));
        if ($limit) {
            $posts_per_page = $limit;
        } else {
            $posts_per_page = '-1';
        }
        if ($category) {
            $cat = $category;
        } else {
            $cat = '';
        }
        $singleopen = 'true';
        $transitionSpeed = '350';
        ob_start();
        $post_type = 'simfaq';
        $orderby = 'post_date';
        $order = 'ASC';
        $args = array(
            'post_type' => $post_type,
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => $posts_per_page,
        );
        if ($cat != "") {
            $args['tax_query'] = array(array('taxonomy' => 'simfaq_texonamy', 'field' => 'term_id', 'terms' => $cat));
        }
        ?>
        <style>
            :root {
                --ttl_sz:
                    <?php echo get_term_meta($cat, "fq_qfont_size", true) ? get_term_meta($cat, "fq_qfont_size", true) . "px" : "18px"; ?>
                ;
                --ttl_clr:
                    <?php echo get_term_meta($cat, "fq_qfont_color", true) ? get_term_meta($cat, "fq_qfont_color", true) : '#000000'; ?>
                ;
                --desc_sz:
                    <?php echo get_term_meta($cat, "fq_dfont_size", true) ? get_term_meta($cat, "fq_dfont_size", true) . "px" : "16px"; ?>
                ;
                --desc_clr:
                    <?php echo get_term_meta($cat, "fq_dfont_color", true) ? get_term_meta($cat, "fq_dfont_color", true) : '#2b2a2a'; ?>
                ;
            }
        </style>
        <?php

        $fq_show_title = get_term_meta($cat, 'fq_show_title', true);
        $fq_show_desc = get_term_meta($cat, 'fq_show_desc', true);

        $query = new WP_Query($args);
        $post_count = $query->post_count;
        if ($cat != "") {
            $quantityTermObject = get_term_by('id', absint($cat), 'simfaq_texonamy');
            $quantityTermName = $quantityTermObject->name;
            $quantityTermdesc = $quantityTermObject->description;
            // echo '<br>' . $quantityTermName;
        } else {
            $quantityTermName = "All FAQ";
            $quantityTermdesc = "";
            // echo $quantityTermName;
        }

        if ($fq_show_title) {
            echo '<h2>' . ucwords($quantityTermName) . "</h2>";
        }
        if ($fq_show_desc) {
            echo '<h4>' . ucwords($quantityTermdesc) . "</h4>";
        }
        $i = 1;
        if ($post_count > 0):
            ?>
            <div class="simple-faq-accordion" data-accordion-group>
                <?php while ($query->have_posts()):
                    $query->the_post();
                    ?>
                    <div data-accordion class="simple-faq-main">
                        <div data-control class="simple-faq-title">
                            <h4 class="title_cls">
                                <?php the_title(); ?>
                            </h4>
                        </div>
                        <div data-content>
                            <?php
                            if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {

                                the_post_thumbnail('thumbnail');
                            }
                            ?>
                            <div class="faq-content">
                                <?php the_content(); ?>
                            </div>
                            <div id="rslt"></div>
                        </div>
                    </div>
                    <?php
                    $i++;
                endwhile;
                ?>
            </div>
            <?php
        endif;
        wp_reset_query();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.simple-faq-accordion [data-accordion]').accordionfaq({
                    singleOpen: <?php echo $singleopen; ?>,
                    transitionEasing: 'ease',
                    transitionSpeed: <?php echo $transitionSpeed; ?>
                });
            });
        </script>
        <style>
            .simple-faq-title h4.title_cls,
            .simple-faq-accordion .simple-faq-main.open h4.title_cls {
                font-size: var(--ttl_sz);
                color: var(--ttl_clr);
            }

            .simple-faq-main .faq-content,
            .simple-faq-main.open .faq-content {
                font-size: var(--desc_sz);
                color: var(--desc_clr);
            }

            div.simple-faq-accordion .simple-faq-main {
                border: none;
                padding: 10px;
            }

            div.simple-faq-main>.simple-faq-title::after {
                top: 5px;
                right: 13px;
                height: 40px;
                width: 40px;
            }

            div.simple-faq-main.open>.simple-faq-title::after {
                top: -15px !important;
                height: 40px;
                width: 40px;
            }

            .simple-faq-accordion .simple-faq-main.open {
                box-shadow: 0px 0px 6px 1px rgba(0, 0, 0, 0.5);
            }
        </style>
        <?php
        return ob_get_clean();
    }
}

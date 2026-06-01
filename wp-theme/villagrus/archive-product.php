<?php get_header(); ?>

<?php
$shop_url  = get_permalink(wc_get_page_id('shop'));
$theme_uri = get_template_directory_uri();

// Current category filter
$current_cat = get_queried_object();
$current_cat_id = ($current_cat instanceof WP_Term) ? $current_cat->term_id : 0;

// Get all top-level product categories for filter pills
$all_cats = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true, 'orderby' => 'name']);

// Products query
$paged    = max(1, get_query_var('paged'));
$per_page = 24;

$args = [
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
];
if ($current_cat_id) {
    $args['tax_query'] = [[
        'taxonomy'         => 'product_cat',
        'field'            => 'term_id',
        'terms'            => $current_cat_id,
        'include_children' => true,
    ]];
}
$products_query = new WP_Query($args);
$total          = $products_query->found_posts;
?>

<!-- FILTER BAR -->
<div class="plp-filter-wrap">
  <div class="plp-filter-inner">
    <div class="plp-pills">
      <a href="<?php echo esc_url($shop_url); ?>"
         class="plp-pill<?php echo !$current_cat_id ? ' is-active' : ''; ?>">
        Alla produkter
      </a>
      <?php if ($all_cats && !is_wp_error($all_cats)): foreach ($all_cats as $cat): ?>
      <a href="<?php echo esc_url(get_term_link($cat)); ?>"
         class="plp-pill<?php echo ($current_cat_id === $cat->term_id) ? ' is-active' : ''; ?>">
        <?php echo esc_html($cat->name); ?>
        <span class="plp-pill-count"><?php echo (int)$cat->count; ?></span>
      </a>
      <?php endforeach; endif; ?>
    </div>
  </div>
</div>

<!-- META ROW -->
<div class="plp-meta">
  <div class="plp-count"><?php echo $total; ?> produkter</div>
  <div class="plp-sort">
    <select id="plpSort" onchange="window.location=this.value">
      <option value="<?php echo esc_url(add_query_arg('orderby', 'menu_order')); ?>">Sortera</option>
      <option value="<?php echo esc_url(add_query_arg('orderby', 'popularity')); ?>">Popularitet</option>
      <option value="<?php echo esc_url(add_query_arg('orderby', 'price')); ?>">Pris: lågt–högt</option>
      <option value="<?php echo esc_url(add_query_arg('orderby', 'price-desc')); ?>">Pris: högt–lågt</option>
    </select>
  </div>
</div>

<!-- PRODUCT GRID -->
<div class="plp-body">
  <div class="plp-grid" id="plpGrid">
    <?php if ($products_query->have_posts()): while ($products_query->have_posts()): $products_query->the_post();
      $product = wc_get_product(get_the_ID());
      if (!$product) continue;
      $cats     = wp_get_post_terms(get_the_ID(), 'product_cat', ['number' => 1]);
      $cat_name = $cats ? $cats[0]->name : '';
      $price    = $product->get_price();
      $img      = get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => '']);
    ?>
    <article class="plp-card reveal">
      <a href="<?php the_permalink(); ?>" class="plp-card__img-wrap">
        <?php if ($img): echo $img; else: ?>
        <img src="<?php echo esc_url(wc_placeholder_img_src('medium')); ?>" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
        <?php if ($cat_name): ?><span class="plp-card__badge"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
      </a>
      <div class="plp-card__body">
        <h3 class="plp-card__name"><?php the_title(); ?></h3>
        <p class="plp-card__frac"><?php echo wp_strip_all_tags($product->get_short_description()); ?></p>
        <?php if ($cat_name): ?><div class="plp-card__cat"><?php echo esc_html($cat_name); ?></div><?php endif; ?>
        <div class="plp-card__footer">
          <div class="plp-card__price">
            <?php if ($price): ?>från <strong><?php echo villagrus_format_price($price); ?></strong><?php endif; ?>
          </div>
          <a href="<?php the_permalink(); ?>" class="plp-card__cta">
            Välj produkt
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
        </div>
      </div>
    </article>
    <?php endwhile; wp_reset_postdata(); else: ?>
    <p style="grid-column:1/-1;text-align:center;padding:80px 0;color:var(--mid)">Inga produkter hittades.</p>
    <?php endif; ?>
  </div>

  <?php if ($total > $per_page): ?>
  <div class="plp-pagination">
    <?php
    echo paginate_links([
        'total'     => $products_query->max_num_pages,
        'current'   => $paged,
        'prev_text' => '←',
        'next_text' => '→',
    ]);
    ?>
  </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>

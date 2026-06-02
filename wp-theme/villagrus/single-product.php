<?php get_header(); ?>

<?php
the_post();
global $product;
$product = wc_get_product(get_the_ID());
if (!$product) { get_footer(); exit; }

$theme_uri    = get_template_directory_uri();
$name         = $product->get_name();
$description  = $product->get_description() ?: $product->get_short_description();
$price        = $product->get_price();
$cats         = wp_get_post_terms(get_the_ID(), 'product_cat', ['number' => 1]);
$cat_name     = $cats ? $cats[0]->name : '';
$cat_url      = $cats ? get_term_link($cats[0]) : get_permalink(wc_get_page_id('shop'));
$img_src      = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: wc_placeholder_img_src('large');
$is_variable  = $product->is_type('variable');
?>

<!-- BREADCRUMB -->
<div class="pdp-breadcrumb" style="position:sticky;top:var(--header-h,64px);z-index:89;background:var(--cream);border-bottom:1px solid var(--border);">
  <div class="pdp-breadcrumb__inner" style="max-width:1200px;margin:0 auto;padding:0 48px;height:40px;display:flex;align-items:center;gap:8px;font-family:var(--sans);font-size:.72rem;color:var(--mid);">
    <a href="<?php echo esc_url(home_url('/')); ?>" style="color:var(--mid);text-decoration:none;">Hem</a>
    <span>›</span>
    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" style="color:var(--mid);text-decoration:none;">Produkter</a>
    <?php if ($cat_name): ?>
    <span>›</span>
    <a href="<?php echo esc_url($cat_url); ?>" style="color:var(--mid);text-decoration:none;"><?php echo esc_html($cat_name); ?></a>
    <?php endif; ?>
    <span>›</span>
    <span style="color:var(--black);"><?php echo esc_html($name); ?></span>
  </div>
</div>

<!-- PRODUCT MAIN -->
<section class="pdp-main" style="max-width:1200px;margin:0 auto;padding:64px 48px;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start;">

  <!-- IMAGE -->
  <div class="pdp-gallery">
    <div class="pdp-gallery__main" style="background:var(--stone);border-radius:var(--radius);overflow:hidden;aspect-ratio:1;display:flex;align-items:center;justify-content:center;padding:32px;">
      <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($name); ?>" style="width:100%;height:100%;object-fit:contain;" id="pdpMainImg">
    </div>
    <?php
    $gallery_ids = $product->get_gallery_image_ids();
    if ($gallery_ids):
    ?>
    <div style="display:flex;gap:12px;margin-top:16px;">
      <div style="width:80px;height:80px;background:var(--stone);border-radius:var(--radius);overflow:hidden;cursor:pointer;border:2px solid var(--black);">
        <img src="<?php echo esc_url($img_src); ?>" style="width:100%;height:100%;object-fit:contain;padding:8px;">
      </div>
      <?php foreach ($gallery_ids as $gid):
        $gsrc = wp_get_attachment_image_url($gid, 'thumbnail');
      ?>
      <div style="width:80px;height:80px;background:var(--stone);border-radius:var(--radius);overflow:hidden;cursor:pointer;border:1px solid var(--border);">
        <img src="<?php echo esc_url($gsrc); ?>" style="width:100%;height:100%;object-fit:contain;padding:8px;">
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- INFO -->
  <div class="pdp-info">
    <?php if ($cat_name): ?>
    <div class="section-label" style="margin-bottom:12px;"><?php echo esc_html($cat_name); ?></div>
    <?php endif; ?>

    <h1 style="font-family:var(--serif);font-size:clamp(1.8rem,3vw,2.8rem);font-weight:400;color:var(--black);line-height:1.15;margin:0 0 24px;">
      <?php echo esc_html($name); ?>
    </h1>

    <?php if ($description): ?>
    <div class="pdp-description" style="font-family:var(--sans);font-size:.92rem;line-height:1.7;color:#444;margin-bottom:32px;">
      <?php echo wp_kses_post($description); ?>
    </div>
    <?php endif; ?>

    <!-- PRICE + ADD TO CART -->
    <div class="pdp-purchase">
      <?php if ($is_variable): ?>
        <?php
        $variations      = $product->get_available_variations();
        $attributes      = $product->get_variation_attributes();
        $min_price       = $product->get_variation_price('min');
        $max_price       = $product->get_variation_price('max');
        ?>
        <div class="pdp-price" style="margin-bottom:24px;">
          <span style="font-family:var(--sans);font-size:.82rem;color:var(--mid);">från</span>
          <span style="font-family:var(--serif);font-size:2rem;font-weight:600;color:var(--black);"><?php echo villagrus_format_price($min_price); ?></span>
          <?php if ($max_price != $min_price): ?>
          <span style="font-family:var(--sans);font-size:.82rem;color:var(--mid);">— <?php echo villagrus_format_price($max_price); ?></span>
          <?php endif; ?>
        </div>

        <form class="pdp-form" id="pdpVariationForm">
          <?php foreach ($attributes as $attr_name => $options):
            $label = wc_attribute_label($attr_name);
          ?>
          <div class="pdp-attr" style="margin-bottom:20px;">
            <div style="font-family:var(--sans);font-size:.78rem;font-weight:500;color:var(--black);margin-bottom:10px;text-transform:uppercase;letter-spacing:.06em;">
              <?php echo esc_html($label); ?>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
              <?php foreach ($options as $opt): ?>
              <label style="cursor:pointer;">
                <input type="radio" name="attribute_<?php echo esc_attr(sanitize_title($attr_name)); ?>"
                       value="<?php echo esc_attr($opt); ?>" style="display:none;"
                       class="pdp-variant-radio">
                <span class="pdp-variant-btn" style="display:inline-block;padding:8px 18px;border:1px solid var(--border);border-radius:var(--radius);font-family:var(--sans);font-size:.82rem;color:var(--black);transition:all .15s;cursor:pointer;">
                  <?php echo esc_html($opt); ?>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>

          <input type="hidden" name="variation_id" id="pdpVariationId" value="">
          <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>">

          <div class="pdp-variation-price" id="pdpVariationPrice" style="font-family:var(--serif);font-size:1.6rem;color:var(--black);margin-bottom:24px;min-height:2rem;"></div>

          <button type="submit" class="pdp-add-btn" style="display:flex;align-items:center;justify-content:center;gap:12px;width:100%;padding:16px 32px;background:var(--cta);color:#fff;border:none;border-radius:var(--radius);font-family:var(--sans);font-size:.88rem;font-weight:500;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:background .2s;">
            Lägg i varukorg
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="18" height="18"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          </button>
        </form>

      <?php else: ?>
        <div class="pdp-price" style="margin-bottom:24px;">
          <span style="font-family:var(--serif);font-size:2rem;font-weight:600;color:var(--black);"><?php echo villagrus_format_price($price); ?></span>
        </div>
        <?php woocommerce_template_single_add_to_cart(); ?>
      <?php endif; ?>
    </div>

    <!-- DELIVERY INFO -->
    <div style="margin-top:32px;padding:20px;background:var(--stone);border-radius:var(--radius);font-family:var(--sans);font-size:.82rem;color:var(--mid);line-height:1.6;">
      <strong style="color:var(--black);">🚚 Hemleverans</strong> — leverans med kranbil direkt till din adress.<br>
      Fraktpris beräknas i kassan baserat på ditt postnummer.
    </div>
  </div>
</section>

<!-- RELATED PRODUCTS -->
<?php
$related_ids = wc_get_related_products(get_the_ID(), 4);
if ($related_ids):
  $related = array_map('wc_get_product', $related_ids);
  $related = array_filter($related);
?>
<section style="max-width:1200px;margin:0 auto;padding:0 48px 120px;">
  <div class="section-label" style="margin-bottom:12px;">Liknande produkter</div>
  <h2 class="section-title" style="margin-bottom:40px;">Du kanske<br><em>också gillar</em></h2>
  <div class="plp-grid">
    <?php foreach ($related as $rel_prod): ?>
    <article class="plp-card">
      <a href="<?php echo esc_url($rel_prod->get_permalink()); ?>" class="plp-card__img-wrap">
        <?php echo $rel_prod->get_image('medium', ['class' => '']); ?>
        <?php
        $rcats = wp_get_post_terms($rel_prod->get_id(), 'product_cat', ['number' => 1]);
        if ($rcats): ?><span class="plp-card__badge"><?php echo esc_html($rcats[0]->name); ?></span><?php endif;
        ?>
      </a>
      <div class="plp-card__body">
        <h3 class="plp-card__name"><?php echo esc_html($rel_prod->get_name()); ?></h3>
        <div class="plp-card__footer">
          <div class="plp-card__price">från <strong><?php echo villagrus_format_price($rel_prod->get_price()); ?></strong></div>
          <a href="<?php echo esc_url($rel_prod->get_permalink()); ?>" class="plp-card__cta">
            Välj <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<script>
(function() {
  const form = document.getElementById('pdpVariationForm');
  if (!form) return;

  const variations = <?php echo wp_json_encode($variations ?? []); ?>;
  const radios     = form.querySelectorAll('.pdp-variant-radio');
  const priceEl    = document.getElementById('pdpVariationPrice');
  const varIdEl    = document.getElementById('pdpVariationId');

  // Style selected variant button
  radios.forEach(function(r) {
    r.addEventListener('change', function() {
      const name = r.getAttribute('name');
      form.querySelectorAll('[name="' + name + '"] ~ .pdp-variant-btn').forEach(function(b) {
        b.style.borderColor = 'var(--border)';
        b.style.background  = 'transparent';
      });
      r.nextElementSibling.style.borderColor = 'var(--black)';
      r.nextElementSibling.style.background  = 'var(--black)';
      r.nextElementSibling.style.color       = '#fff';
      matchVariation();
    });
  });

  function matchVariation() {
    const selected = {};
    form.querySelectorAll('.pdp-variant-radio:checked').forEach(function(r) {
      selected[r.getAttribute('name')] = r.value;
    });
    const match = variations.find(function(v) {
      return Object.keys(selected).every(function(k) {
        return !v.attributes[k] || v.attributes[k] === selected[k];
      });
    });
    if (match && match.display_price) {
      const formatted = new Intl.NumberFormat('sv-SE').format(match.display_price) + ' kr';
      priceEl.textContent = formatted;
      varIdEl.value = match.variation_id;
    } else {
      priceEl.textContent = '';
      varIdEl.value = '';
    }
  }

  // Add to cart
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const productId   = form.querySelector('[name="product_id"]').value;
    const variationId = varIdEl.value;
    if (!variationId) { alert('Välj ett alternativ'); return; }

    const btn = form.querySelector('.pdp-add-btn');
    btn.textContent = 'Lägger till…';

    fetch(villagrusData.ajaxUrl, {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        action: 'villagrus_add_to_cart',
        nonce: villagrusData.nonce,
        product_id: productId,
        variation_id: variationId,
        quantity: 1,
      })
    })
    .then(r => r.json())
    .then(function(data) {
      if (data.success) {
        const count = document.getElementById('cartCount');
        if (count) {
          count.textContent = data.data.cart_count;
          count.classList.toggle('is-visible', data.data.cart_count > 0);
        }
        btn.textContent = '✓ Tillagd i varukorg';
        setTimeout(function() { btn.textContent = 'Lägg i varukorg'; }, 2000);
      } else {
        btn.textContent = 'Kunde inte lägga till';
      }
    });
  });
})();
</script>

<?php get_footer(); ?>

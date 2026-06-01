<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="VillaGrus" style="height:32px;width:auto;object-fit:contain;filter:invert(1) brightness(2);margin-bottom:20px;display:block;">
      <p>Naturens material direkt hem till dig. Kvalitetsprodukter inom dekorsten, singel, bergskross och jord sedan 2014.</p>
      <div class="footer-social">
        <a href="#" class="social-link" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/></svg></a>
        <a href="#" class="social-link" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
      </div>
    </div>
    <div class="footer-col">
      <h5>Produkter</h5>
      <ul>
        <?php
        $cats = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true, 'number' => 6]);
        if ($cats && !is_wp_error($cats)):
            foreach ($cats as $cat):
                echo '<li><a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
            endforeach;
        endif;
        ?>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Information</h5>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/om-oss/')); ?>">Om oss</a></li>
        <li><a href="<?php echo esc_url(home_url('/leveransinformation/')); ?>">Leveransinformation</a></li>
        <li><a href="<?php echo esc_url(home_url('/kopvillkor/')); ?>">Köpvillkor</a></li>
        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Kontakt</h5>
      <ul>
        <li><a href="mailto:info@villagrus.se">info@villagrus.se</a></li>
        <li>Mån–Fre 08–17</li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> VillaGrus AB. Alla rättigheter förbehållna.</p>
    <div class="payment-icons">
      <span class="pay-badge klarna">Klarna</span>
      <span class="pay-badge">Visa</span>
      <span class="pay-badge">Mastercard</span>
      <span class="pay-badge">Swish</span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

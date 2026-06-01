<?php get_header(); ?>

<main class="site-main" style="min-height:60vh;padding:120px 48px;">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article>
      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </article>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

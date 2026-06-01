<?php get_header(); ?>

<main style="max-width:760px;margin:0 auto;padding:96px 48px 120px;">
  <?php while (have_posts()): the_post(); ?>
    <h1 style="font-family:var(--serif);font-size:clamp(2rem,4vw,3rem);font-weight:400;margin-bottom:40px;"><?php the_title(); ?></h1>
    <div style="font-family:var(--sans);font-size:1rem;line-height:1.75;color:#333;">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

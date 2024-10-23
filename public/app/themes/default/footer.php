
        <footer class="w-content">
            <!-- @todo-wordpress -->
            <div class="text-sm text-right">©<?php echo date('Y'); ?> Company Name All Rights Reserved</div>
        </footer>
    </div>

    <?php wp_footer(); ?>

    <?php if (WP_DEBUG) : ?>
        <?php // makes svgs work with the dev server ?>
        <script defer src="<?php echo ThemeHelpers::asset('js/svgxuse.min.js'); ?>"></script>
    <?php endif; ?>

</body>
</html>

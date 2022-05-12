        </main>

        <footer class="w-content">
            <!-- @todo-wordpress -->
            <div class="text-sm text-right">©<?php echo date('Y'); ?> Company Name All Rights Reserved</div>
        </footer>
    </div>

    <?php wp_footer(); ?>

    <?php // @todo-wordpress remove is no IE support is needed ?>
    <?php // make svg's with use referencing an external image work in IE ?>
    <script defer src="<?php echo ThemeHelpers::assetPath('build/svgxuse.min.js'); ?>"></script>

</body>
</html>

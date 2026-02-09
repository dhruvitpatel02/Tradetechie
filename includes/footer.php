    </main>

    <footer style="background: var(--color-surface); border-top: 1px solid var(--color-border); padding: 2rem 0; margin-top: 3rem;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 style="margin-bottom: 1rem;"><i class="bi bi-graph-up-arrow"></i> <?php echo SITE_NAME; ?></h5>
                    <p class="text-muted">Learn, Practice, and Master Stock Market Trading</p>
                </div>
                <div class="col-md-4">
                    <h6 style="margin-bottom: 1rem;">Quick Links</h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>" style="color: var(--color-text-muted);">Home</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>learn/" style="color: var(--color-text-muted);">Learn</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>dashboard.php" style="color: var(--color-text-muted);">Dashboard</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 style="margin-bottom: 1rem;">Developer</h6>
                    <p class="text-muted" style="margin-bottom: 0.5rem;">
                        <strong>Dhruvit Vegad</strong>
                    </p>
                    <p class="text-muted" style="margin-bottom: 0.5rem;">
                        <i class="bi bi-envelope"></i> dhruvit.vegad2002@gmail.com
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-geo-alt"></i> Rajkot
                    </p>
                </div>
            </div>
            <hr style="border-color: var(--color-border); margin: 1.5rem 0;">
            <div class="text-center text-muted">
                <p style="margin: 0;">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
</body>
</html>

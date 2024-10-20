<div class="wrap">
    <form method="post" action="options.php">
        <?php
        settings_fields('kiptrak_settings_group');
        do_settings_sections('kiptrak_settings_group');
        ?>
        <table class="form-table" style="width: 100%; margin-bottom: 20px;">
            <tr valign="top">
                <th scope="row" style="padding: 10px 0; font-weight: 600; font-size: 1.1em;">
                    Kiptrak Site URL
                </th>
                <td style="padding: 10px 0;">
                    <input type="url" name="kiptrak_backend_url"
                        value="<?php echo esc_attr(get_option('kiptrak_backend_url')); ?>" class="regular-text"
                        style="width: 100%; padding: 8px; border: 1px solid #ccd0d4; border-radius: 4px;" />
                    <p class="description" style="margin-top: 5px; color: #555;">
                        Enter the URL of the Kiptrak backend app you want to connect to.
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
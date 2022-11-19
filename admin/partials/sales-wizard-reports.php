<?php
wp_enqueue_style('sales-wizard-app');
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Contacts List', 'sales-wizard-app'); ?></h1>
    <form action="" method="post">
        <input type="hidden" name="page" value="contacts_list_table">
        <?php wp_nonce_field('contacts_list_table', 'contacts_list_table'); ?>
        <?php
		if (isset($_GET['contact_sent'])) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p> <?php _e('contacts has been sent successfully!','sales-wizard-app');?></p>
            </div>
        <?php
        }
		require_once plugin_dir_path(dirname( __FILE__)).'class-sales-wizard-app-contacts.php';
		$table = new sales_wizard_app_contacts();
        $table->prepare_items();
        $table->search_box(__('Search'), 'search-box-id');
        $table->display();
        ?>
    </form>
</div>
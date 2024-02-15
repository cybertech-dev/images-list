<?php

if (!defined("ABSPATH")) {
    exit();
}

function wpdil_images_list_menu() {
    add_menu_page('List Images', 'List Images', 'manage_options', 'wpdock_images_list', 'wpdil_page', 'dashicons-format-gallery');
}
add_action('admin_menu', 'wpdil_images_list_menu');

function wpdil_page() {
    echo '<div class="wrap">';
    echo '<h1>List Images</h1>';
    echo '<table id="wpdock_il_table" class="display" style="width:100%">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th class="no-export">Thumbnail</th>';
    echo '<th>Filename</th>';
    echo '<th>Type</th>';
    echo '<th>Linked</th>';
    echo '<th>Filesize (KB)</th>';
    echo '<th>Alt</th>';
    echo '<th class="no-export"></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_status' => 'inherit',
        'posts_per_page' => -1
    );



    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $mime_type = get_post_mime_type($id);
            $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            $thumbnail = wp_get_attachment_thumb_url($id);
            $filename = basename(get_attached_file($id));
            $filepath = get_attached_file($id);
            $filesize = filesize($filepath) / 1024;

            $args = array(
                'post_type' => array('post', 'page'),
                'post_status' => 'publish',
                's' => wp_get_attachment_url($id),  // search by image URL
                'fields' => 'ids'  // only return IDs
            );

            $related_posts = new WP_Query($args);
            $used = $related_posts->found_posts > 0 ? "Yes" : "No";
            $used2 = wpdil_check_if_linked($id);


            echo '<tr class="item-'.esc_attr($id).'">';
            echo '<td>' . esc_html($id) . '</td>';
            echo '<td><img src="' . esc_url($thumbnail) . '" class="il-thumbnail-image" alt="' . esc_attr($alt) . '"></td>';
            echo '<td class="editable-cell"><span class="editable-text et-filename">' . esc_html($filename) . '</span><input type="text" data-id="' . esc_attr($id) . '" class="il-input il-editable il-editable-filename" value="' . esc_attr($filename) . '"></span></td>';

            echo '<td>' . esc_html($mime_type) . '</td>';
            echo '<td>'.esc_html($used2).'</td>';
            echo '<td><span class="size">' . round(esc_html($filesize), 2) . '</span><span> KB</span></td>';

            echo '<td class="editable-cell"><span class="editable-text">' . esc_html($alt) . '</span><input type="text" data-id="' . esc_attr($id) . '" class="il-input il-editable il-editable-alt" value="' . esc_attr($alt) . '"></td>';

            echo '<td>
    <div class="actions">
        <div class="edit" data-id="' . esc_attr($id) . '"><svg version="1.1" class="edit_submit"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.985 511.985" style="enable-background:new 0 0 511.985 511.985;" xml:space="preserve">
<g>
	<g>
		<path d="M500.088,83.681c-15.841-15.862-41.564-15.852-57.426,0L184.205,342.148L69.332,227.276
			c-15.862-15.862-41.574-15.862-57.436,0c-15.862,15.862-15.862,41.574,0,57.436l143.585,143.585
			c7.926,7.926,18.319,11.899,28.713,11.899c10.394,0,20.797-3.963,28.723-11.899l287.171-287.181
			C515.95,125.265,515.95,99.542,500.088,83.681z"></path>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg><svg class="edit_svg" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg" id="fi_9458316"><g id="Layer_2" data-name="Layer 2"><path d="m14.32 4-10.87 10.82a1.68 1.68 0 0 0 -.46.82l-1.15 4.58a1.24 1.24 0 0 0 1.21 1.55 1.63 1.63 0 0 0 .31 0l4.58-1.15a1.77 1.77 0 0 0 .81-.46l10.87-10.91z"></path><path d="m22 3-1.42-1.42a2.82 2.82 0 0 0 -3.89 0l-1.31 1.31 5.3 5.3 1.32-1.31a2.75 2.75 0 0 0 0-3.88z"></path></g></svg></div>
        <div class="cancel_row"><div class="cancel_container"><div class="cancel" data-id="' . esc_attr($id) . '">
<svg class="edit_cancel"  viewBox="0 0 24 24" fill="none" focusable="false" aria-hidden="true" class="SvgIcon"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.207 6.207a1 1 0 0 0-1.414-1.414L12 10.586 6.207 4.793a1 1 0 0 0-1.414 1.414L10.586 12l-5.793 5.793a1 1 0 1 0 1.414 1.414L12 13.414l5.793 5.793a1 1 0 0 0 1.414-1.414L13.414 12l5.793-5.793z" fill="currentColor"></path></svg></div>
</div></div>
    </div>
    </td>';
            echo '</tr>';
        }
        wp_reset_postdata();
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function wpdil_check_if_linked($attachment_id){
    global $wpdb;

    $full_size_url = wp_get_attachment_url($attachment_id);

    // Collect all sizes of the image (including full)
    $image_urls = array($full_size_url);

    $metadata = wp_get_attachment_metadata($attachment_id);
    if (!empty($metadata) && isset($metadata['sizes'])) {
        foreach ($metadata['sizes'] as $size => $size_data) {
            $image_urls[] = wp_get_attachment_image_src($attachment_id, $size)[0];
        }
    }

    $sql = "SELECT ID FROM $wpdb->posts WHERE post_type IN ('post', 'page') AND post_status = 'publish' AND (post_content LIKE %s";
    for ($i = 1; $i < count($image_urls); $i++) {
        $sql .= " OR post_content LIKE %s";
    }
    $sql .= ") LIMIT 1";

    // Adding wildcards for all the image URLs
    $image_urls_wildcard = array_map(function($url) use ($wpdb) {
        return '%' . $wpdb->esc_like($url) . '%';
    }, $image_urls);


    $posts = $wpdb->get_results($wpdb->prepare($sql, $image_urls_wildcard));// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

    $used = !empty($posts) ? "Yes" : "No";
    return $used;
}

function wpdil_update_image_details() {
    check_ajax_referer('il_nonce', 'nonce');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $alt = sanitize_text_field($_POST['alt']);

    if($id) {
        update_post_meta($id, '_wp_attachment_image_alt', $alt);
        //wp_send_json_success(array('success' => true));
    } else {
        //wp_send_json_error(array('success' => false));
    }

    $new_basename = isset($_POST['filename']) ? sanitize_file_name($_POST['filename']) : '';

    if (!$id || !$new_basename) {
        wp_send_json_error(['message' => 'Invalid image ID or filename.']);
    }

    $current_path = get_attached_file($id);
    $path_parts = pathinfo($current_path);

    $new_path = $path_parts['dirname'] . '/' . $new_basename . '.' . $path_parts['extension'];
    $old_filename = $path_parts['filename'].'.'.$path_parts['extension'];
    $new_filename = $new_basename.'.'.$path_parts['extension'];





    // Rename the file
    if (rename($current_path, $new_path)) {
        // Update metadata in the database
        update_attached_file($id, $new_path);
        wpdil_update_filename_in_posts($id,$old_filename, $new_filename);
        wpdil_regenerate_thumbnail_for_attachment($id,$new_basename);
        //wpdil_replace_filename_in_metadata($id,$old_filename, $new_filename);
        wp_send_json_success(array('success' => true,'message' => $new_filename));
    } else {
        wp_send_json_error(['message' => 'Error renaming the file.']);
    }




}
add_action('wp_ajax_update_image_details', 'wpdil_update_image_details');

add_action('wp_ajax_change_image_filename', 'wpdil_update_image_filename');

function wpdil_update_image_filename() {
    check_ajax_referer('il_nonce', 'nonce');

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $new_basename = isset($_POST['filename']) ? sanitize_file_name($_POST['filename']) : '';

    if (!$id || !$new_basename) {
        wp_send_json_error(['message' => 'Invalid image ID or filename.']);
    }

    $current_path = get_attached_file($id);
    $path_parts = pathinfo($current_path);
    $new_path = $path_parts['dirname'] . '/' . $new_basename . '.' . $path_parts['extension'];

    // Rename the file
    if (rename($current_path, $new_path)) {
        // Update metadata in the database
        update_attached_file($id, $new_path);

        wp_send_json_success(['message' => 'Filename changed successfully.']);
    } else {
        wp_send_json_error(['message' => 'Error renaming the file.']);
    }
}

function wpdil_update_filename_in_posts($id, $old_filename, $new_filename) {
    global $wpdb;


    $subdir = wpdil_get_attachment_subdir($id);


    $old_file_url = $subdir . '/' . $old_filename;
    $new_file_url = $subdir . '/' . $new_filename;


    $wpdb->query($wpdb->prepare(
        "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, %s, %s)",
        $old_file_url,
        $new_file_url
    ));


    if ($wpdb->last_error) {
        return array('success' => false, 'message' => $wpdb->last_error);
    }
}

function wpdil_replace_filename_in_metadata($id, $old_filename, $new_filename) {
    global $wpdb;


    $current_metadata = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = '_wp_attachment_metadata'", $attachment_id));

    $updated_meta = $wpdb->query($wpdb->prepare(
        "UPDATE $wpdb->postmeta 
        SET meta_value = REPLACE(meta_value, %s, %s) 
        WHERE post_id = %d AND meta_key = '_wp_attachment_metadata'",
        $old_filename,
        $new_filename,
        $id
    ));
}

function wpdil_get_attachment_subdir($attachment_id) {
    $file_path = get_attached_file($attachment_id);

    // Return empty if the file path couldn't be retrieved
    if (!$file_path) {
        return '';
    }

    // Normalize the path
    $normalized_path = str_replace('\\', '/', $file_path);

    // Get WordPress uploads dir path
    $uploads_dir = wp_upload_dir();
    $base_upload_dir = str_replace('\\', '/', $uploads_dir['basedir']);

    // Extract the subdir portion from the normalized path
    $subdir = str_replace($base_upload_dir, '', $normalized_path);
    $subdir = dirname($subdir);

    return $subdir;
}

function wpdil_regenerate_thumbnail_for_attachment($attachment_id, $new_filename_without_extension) {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $metadata = wp_get_attachment_metadata($attachment_id);

    if (!$metadata) {
        return new WP_Error('not_found', 'Metadata not found');
    }

    $old_upload_dir = wp_upload_dir();
    $old_base_dir = trailingslashit($old_upload_dir['basedir']) . dirname($metadata['file']);
    $old_base_url = trailingslashit($old_upload_dir['baseurl']) . dirname($metadata['file']);

    // Delete old thumbnails
    foreach ($metadata['sizes'] as $size => $size_data) {
        $old_thumbnail_file = trailingslashit($old_base_dir) . $size_data['file'];
        $old_thumbnail_url = trailingslashit($old_base_url) . $size_data['file'];
        $new_thumbnail_url = trailingslashit($old_base_url) . $new_filename_without_extension . '-' . $size_data['width'] . 'x' . $size_data['height'] . '.' . pathinfo($size_data['file'], PATHINFO_EXTENSION);

        if (file_exists($old_thumbnail_file)) {
            @unlink($old_thumbnail_file);
        }

        $wpdb->query($wpdb->prepare(
            "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, %s, %s)",
            $old_thumbnail_url,
            $new_thumbnail_url
        ));
    }

    // Update the file in metadata
    $metadata['file'] = trailingslashit(dirname($metadata['file'])) . $new_filename_without_extension . '.' . pathinfo($metadata['file'], PATHINFO_EXTENSION);

    // Update the sizes in metadata
    foreach ($metadata['sizes'] as $size => $size_data) {
        $metadata['sizes'][$size]['file'] = $new_filename_without_extension . '-' . $size_data['width'] . 'x' . $size_data['height'] . '.' . pathinfo($size_data['file'], PATHINFO_EXTENSION);
    }

    // Save the new metadata
    wp_update_attachment_metadata($attachment_id, $metadata);

    $file = get_attached_file($attachment_id);


    // Generate new image sizes

    $new_metadata = wp_generate_attachment_metadata($attachment_id, $file);

    // Update the metadata information for the attachment
    wp_update_attachment_metadata($attachment_id, $new_metadata);

    return true;
}
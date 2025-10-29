<?php

$template = get_post( $template_id );

echo do_blocks( $template->post_content );
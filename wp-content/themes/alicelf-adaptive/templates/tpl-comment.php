<ol>
    <?php wp_list_comments(); ?>
</ol>
<?php
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$arguments = array(
    'id_form'           => 'alicelf-commentform',
    'id_submit'         => 'alicelf-submit',
    'title_reply'       => __( 'Leave a Reply' ),
    'title_reply_to'    => __( 'Leave a Reply to %s' ),
    'cancel_reply_link' => __( 'Cancel Reply' ),
    'label_submit'      => __( 'Post Comment' ),

    'comment_field' =>  '
    <div class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) .
    '</label><textarea  id="comment" name="comment" cols="45" rows="8" aria-required="true" class="form-control "></textarea><br>
    </div>',

    'must_log_in' => '<p class="must-log-in">' .
    sprintf(
        __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
        wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
    ) . '</p>',

    'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
        __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
        admin_url( 'profile.php' ),
        $user_identity,
        wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
    ) . '</p>',

    'comment_notes_before' => '<div class="comment-notes alert alert-info">' .
    __( 'Your email address will not be published.' ) .
    '</div>',

    'comment_notes_after' => '',

    'fields' => apply_filters( 'comment_form_default_fields', array(

            'author' =>
            '<div class="row">
            <div class="comment-form-author col-sm-6"><label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .'" size="30"' . $aria_req . ' class="form-control " />
            </div>',

            'email' =>'<div  class="comment-form-email col-sm-6"><label for="email">'. __( 'Email', 'domainreference' ) . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input class="form-control" id="email" name="email" type="text" value="'.esc_attr(  $commenter['comment_author_email'] ).'" size="30"' . $aria_req . ' />
            </div
            ></div>',

            'url' => false
        )
    ),
);?>

<?php comment_form($arguments); ?>

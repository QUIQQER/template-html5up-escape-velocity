<?php

/**
 * Emotion
 */

\QUI\Utils\Site::setRecursivAttribute( $Site, 'image_emotion' );


/**
 * Second content
 */

if ( $Project->getConfig( 'html5up_escape_velocity.content.left_content_recursive' ) ) {
    \QUI\Utils\Site::setRecursivAttribute( $Site, 'extra_content' );
}


/**
 * Footer contact
 */

$sendContact = function() use ($Project)
{
    if ( !isset( $_POST['contact'] ) ) {
        return;
    }

    $mailTo = $Project->getConfig('html5up_escape_velocity.contact.mailTo');

    if ( empty( $mailTo ) ) {
        return;
    }


    if ( !isset( $_POST['name'] ) ||
         !isset( $_POST['email'] ) ||
         !isset( $_POST['message'] ) )
    {
        throw new \QUI\Exception(
            \QUI::getLocale()->get(
                'quiqqer/template-html5up-escape-velocity',
                'error.could.not.send.mail'
            )
        );
    }

    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $message = $_POST['message'];

    if ( empty( $name ) )
    {
        throw new \QUI\Exception(
            \QUI::getLocale()->get(
                'quiqqer/template-html5up-escape-velocity',
                'error.contact.email.name.is.empty'
            )
        );
    }

    if ( empty( $email ) )
    {
        throw new \QUI\Exception(
            \QUI::getLocale()->get(
                'quiqqer/template-html5up-escape-velocity',
                'error.contact.email.is.empty'
            )
        );
    }

    if ( empty( $message ) )
    {
        throw new \QUI\Exception(
            \QUI::getLocale()->get(
                'quiqqer/template-html5up-escape-velocity',
                'error.contact.email.message.is.empty'
            )
        );
    }

    if ( !\QUI\Utils\Security\Orthos::checkMailSyntax( $email ) )
    {
        throw new \QUI\Exception(
            \QUI::getLocale()->get(
                'quiqqer/template-html5up-escape-velocity',
                'error.contact.email.wrong.syntax'
            )
        );
    }

    $mailtext = "Contact from {$_SERVER['HTTP_HOST']}

From: $name
E-Mail: $email

Message: $message

";

    $Mail = new \QUI\Mail();
    $Mail->send(array(
        'MailTo'  => $mailTo,
        'Subject' => 'Contact from '. $_SERVER['HTTP_HOST'],
        'Body'    => $mailtext
    ));
};

if ( isset( $_POST['contact'] ) )
{

    try
    {
        $sendContact();

        $Engine->assign( 'contact_success_message', \QUI::getLocale()->get(
            'quiqqer/template-html5up-escape-velocity',
            'success.contact.email.send'
        ) );

    } catch ( \QUI\Exception $Exception )
    {
        $Engine->assign( 'contact_error_message', $Exception->getMessage() );
    }
}

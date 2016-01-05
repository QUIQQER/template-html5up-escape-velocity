<?php

/**
 * Emotion
 */

\QUI\Utils\Site::setRecursivAttribute( $Site, 'image_emotion' );

/**
 * Project Logo
 */

$logo = false;
$configLogo = $Project->getConfig('html5up-escape-velocity.settings.logo');

if (QUI\Projects\Media\Utils::isMediaUrl($configLogo)) {
    $logo = $configLogo;
}

/**
 * min header ?
 */

$minHeader = false;

switch ($Template->getLayoutType()) {
    case 'layout/rightSidebar':
        $minHeader = $Project->getConfig('html5up-escape-velocity.headerSettings.minHeaderRightSidebar');
        break;

    case 'layout/leftSidebar':
        $minHeader = $Project->getConfig('html5up-escape-velocity.headerSettings.minHeaderLeftSidebar');
        break;

    case 'layout/noSidebar':
        $minHeader = $Project->getConfig('html5up-escape-velocity.headerSettings.minHeaderNoSidebar');
        break;

}

/**
 * colors
 */

$colorMain = '#e97770';

if ($Project->getConfig('html5up-escape-velocity.colorSettings.colorMain')) {
    $colorMain = $Project->getConfig('html5up-escape-velocity.colorSettings.colorMain');
}


echo $Project->getConfig('html5up-escape-velocity.settings.minHeaderLeftSidebar');
/**
 * own site type?
 */

$Engine->assign(array(
    'logo'                     => $logo,
    'BricksManager'            => \QUI\Bricks\Manager::init(),
    'showContact'              => $Project->getConfig('html5up-escape-velocity.footer.showcontact'),
    'showVelocityFooter'       => $Project->getConfig('html5up-escape-velocity.footer.showVelocityFooter'),
    'showVelocityFooterHeader' => $Project->getConfig('html5up-escape-velocity.footer.showVelocityFooterHeader'),
    'showSiteContentTitle'     => $Project->getConfig('html5up-escape-velocity.settings.showSiteContentTitle'),
    'showSiteFooterTitle'      => $Project->getConfig('html5up-escape-velocity.settings.showSiteFooterTitle'),
    'minHeader'                => $minHeader,
    'colorMain'                => $colorMain,
    'ownSiteType'   =>
        strpos($Site->getAttribute('type'), 'quiqqer/template-html5up-escape-velocity:') !== false
            ? 1 : 0,
    'quiTplType'    => $Project->getConfig('html5up-escape-velocity.settings.standardType'),
));






/**
 * Second content
 */

if ( $Project->getConfig( 'html5up-escape-velocity.content.left_content_recursive' ) ) {
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

    $mailTo = $Project->getConfig('html5up-escape-velocity.contact.mailTo');

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

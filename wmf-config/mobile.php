<?php

# WARNING: This file is publically viewable on the web.
# # Do not put private data here.

if ( $wmgMobileFrontend ) {
	require_once( "$IP/extensions/MobileFrontend/MobileFrontend.php" );
	$wgMFNoindexPages = false;
	$wgMFNearby = $wmgMFNearby && $wmgEnableGeoData;
	$wgMFPhotoUploadEndpoint = $wmgMFPhotoUploadEndpoint;
	$wgMFPhotoUploadWiki = $wmgMFPhotoUploadWiki;
	$wgMFPhotoUploadAppendToDesc = $wmgMFPhotoUploadAppendToDesc;
	$wgMFRemotePostFeedbackUsername = $wmgMFRemotePostFeedbackUsername;
	$wgMFRemotePostFeedbackPassword = $wmgMFRemotePostFeedbackPassword;
	$wgMFRemotePostFeedback = true;
	$wgMFRemotePostFeedbackUrl = "http://www.mediawiki.org/w/api.php";
	$wgMFRemotePostFeedbackArticle = "Project:Mobile site feedback";
	$wgMFFeedbackFallbackURL = '//en.m.wikipedia.org/wiki/Wikipedia:Contact_us';

	$wgHooks['MobileFrontendOverrideFeedbackLinks'][] = 'MobileFrontendFeedbackConfig';
	function MobileFrontendFeedbackConfig( $feedbackSource, $referringArticle ) {
		global $wgLanguageCode, $wgDBname, $wgMFFeedbackLinks;

		$infoEmails = array(
			'af',
			'ar',
			'ca',
			'cs',
			'da',
			'de',
			'el',
			//'en', Ommitted on purpose
			'es',
			'et',
			'fa',
			'fi',
			'fr',
			'he',
			'hi',
			'hr',
			'hu',
			'it',
			'ja',
			'ko',
			'ml',
			'nds',
			'nl',
			'no',
			'pl',
			'pt',
			'ro',
			'ru',
			'sk',
			'sr',
			'sv',
			'tr',
			'vi',
			'zh',
		);

		$lang = ( in_array( $wgLanguageCode, $infoEmails ) ) ? $wgLanguageCode : 'en';
		/** Get email subjects **/
		$subjectPreface = "[Mobile feedback] ";
		$generalSubject = $subjectPreface . wfMessage( 'mobile-frontend-leave-feedback-general-link-text' )->inLanguage( $lang )->escaped();
		$articlePersonalSubject = $subjectPreface . wfMessage( 'mobile-frontend-leave-feedback-article-personal-link-text' )->inLanguage( $lang )->escaped();
		$articleFactualSubject = $subjectPreface . wfMessage( 'mobile-frontend-leave-feedback-article-factual-link-text' )->inLanguage( $lang )->escaped();
		$articleOtherSubject = $subjectPreface . wfMessage( 'mobile-frontend-leave-feedback-article-other-link-text' )->inLanguage( $lang )->escaped();
		$technicalSubject = $subjectPreface . wfMessage( 'mobile-frontend-leave-feedback-technical-link-text' )->inLanguage( $lang )->escaped();

		/** Build links **/
		$emailStub = "info-$lang";
		$emailContext = "Source: " . $feedbackSource . "\nReferring page: " . $referringArticle . "\n";

		// factual error link
		if ( $wgDBname == 'enwiki' ) {
			$articleFactualLink = "//en.m.wikipedia.org/wiki/Wikipedia:Contact_us/Article_problem/Factual_error";
		} else {
			$articleFactualLink = "mailto:$emailStub@wikimedia.org?subject=$articleFactualSubject&body=$emailContext";
		}

		// all other links - only en uses suffix routing
		$generalLink = "mailto:$emailStub@wikimedia.org?subject=$generalSubject&body=$emailContext";
		if ( $lang == 'en' ) {
			$articlePersonalLink = "mailto:$emailStub-q@wikimedia.org?subject=$articlePersonalSubject&body=$emailContext";
			$articleOtherLink = "mailto:$emailStub-o@wikimedia.org?subject=$articleOtherSubject&body=$emailContext";
		} else {
			$articlePersonalLink = "mailto:$emailStub@wikimedia.org?subject=$articlePersonalSubject&body=$emailContext";
			$articleOtherLink = "mailto:$emailStub@wikimedia.org?subject=$articleOtherSubject&body=$emailContext";
		}

		$technicalBody = wfMessage( 'mobile-frontend-leave-feedback-email-body' )->inLanguage( $lang )->escaped()
			. "\nUser-agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
		$technicalBody .= $emailContext;
		$technicalLink = "mailto:mobile-feedback-l@lists.wikimedia.org?subject=$technicalSubject&body=$technicalBody";

		$wgMFFeedbackLinks = array(
			'Technical' => $technicalLink, // Technical feedback
			'General' => $generalLink, // General feedback
			'ArticlePersonal' => $articlePersonalLink, // Regarding me, a person, or a company I work for
			'ArticleFactual' => $articleFactualLink, // Regarding a factual error
			'ArticleOther' => $articleOtherLink, // Regarding another problem
		);
		return true;
	}  # MobileFrontendFeedbackConfig()

	if ( $wmgMobileFrontendLogo ) {
		$wgMobileFrontendLogo = $wmgMobileFrontendLogo;
	}
	if ( $wmgMFRemovableClasses ) {
		$wgMFRemovableClasses = $wmgMFRemovableClasses;
	}
	if ( $wmgMFCustomLogos ) {
		if ( isset( $wmgMFCustomLogos['copyright'] ) ) {
			$wmgMFCustomLogos['copyright'] = str_replace( '{wgExtensionAssetsPath}', $wgExtensionAssetsPath, $wmgMFCustomLogos['copyright'] );
		}
		$wgMFCustomLogos = $wmgMFCustomLogos;
	}
}


// If a URL template is set for MobileFrontend, use it.
if ( $wmgMobileUrlTemplate ) {
	$wgMobileUrlTemplate = $wmgMobileUrlTemplate;
}

if ( $wmgZeroRatedMobileAccess ) {
	require_once( "$IP/extensions/ZeroRatedMobileAccess/ZeroRatedMobileAccess.php" );
}

if ( $wmgZeroDisableImages ) {
	if ( isset( $_SERVER['HTTP_X_SUBDOMAIN'] ) && strtoupper( $_SERVER['HTTP_X_SUBDOMAIN'] ) == 'ZERO' ) {
		$wgZeroDisableImages = $wmgZeroDisableImages;
	}
}

// Enable loading of desktop-specific resources from MobileFrontend
if ( $wmgMFEnableDesktopResources ) {
	$wgMFEnableDesktopResources = true;
}

// Enable appending of TM (text) / (R) (icon) on site name in footer.
// See bug 41141 though, we may wish to disable on some sites.
$wgMFTrademarkSitename = true;

$wgMFLogEvents = $wmgMFLogEvents;

// Enable Schemas for event logging (jdlrobson; 07-Feb-2012)
if ( $wgMFLogEvents && $wmgUseEventLogging ) {
	$wgResourceModules['mobile.watchlist.schema'] = array(
		'class' => 'ResourceLoaderSchemaModule',
		'schema' => 'MobileBetaWatchlist',
		'revision' => 5281061,
		'targets' => 'mobile',
		'mobileTargets' => array( 'stable', 'beta', 'alpha' ),
	);

	$wgResourceModules['mobile.uploads.schema'] = array(
		'class' => 'ResourceLoaderSchemaModule',
		'schema' => 'MobileWebUploads',
		'revision' => 5281063,
		'targets' => 'mobile',
		'mobileTargets' => array( 'stable', 'beta', 'alpha' ),
	);
}

// Force HTTPS for login/account creation
$wgMFForceSecureLogin = $wmgMFForceSecureLogin;

// Point to Common's Special:LoginHandshake page
$wgMFLoginHandshakeUrl = $wmgMFLoginHandshakeUrl;

// Enable X-Analytics logging
$wgMFEnableXAnalyticsLogging = $wmgMFEnableXAnalyticsLogging;

if ( $wmgMFVaryResources ) {
	$wgMFVaryResources = true;
	// Point mobile load.php requests to a special path on bits that gets X-Device headers
	$wgHooks['EnterMobileMode'][] = function() {
		global $wgLoadScript;
		$wgLoadScript = str_replace( 'bits.wikimedia.org/', 'bits.wikimedia.org/m/', $wgLoadScript );
		return true;
	};
}

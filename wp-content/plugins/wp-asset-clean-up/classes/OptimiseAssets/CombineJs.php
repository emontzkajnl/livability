<?php
/** @noinspection MultipleReturnStatementsInspection */

namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\MainFront;
use WpAssetCleanUp\FileSystem;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\ObjectCache;

/**
 * Class CombineJs
 * @package WpAssetCleanUp\OptimiseAssets
 */
class CombineJs
{
	/**
	 * @var string
	 */
	public static $jsonStorageFile = 'js-combined{maybe-extra-info}.json';

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
     * @noinspection NestedAssignmentsUsageInspection
     */
	public static function doCombine($htmlSource)
	{
		if ( ! Misc::isDOMDocumentOn() ) {
			return $htmlSource;
		}

		global $wp_scripts;
		$wpacuRegisteredScripts = $wp_scripts->registered;

		$combineLevel = 2;

		$isDeferAppliedOnBodyCombineGroupNo = false;

		// $uriToFinalJsFile will always be relative ONLY within WP_CONTENT_DIR . self::getRelPathJsCacheDir()
		// which is usually "wp-content/cache/asset-cleanup/js/"

		// "true" would make it avoid checking the cache and always use the DOM Parser / RegExp
		// for DEV purposes ONLY as it uses more resources
		$finalCacheList = array();
		$skipCache = false;

		if (isset($_GET['wpacu_no_cache']) || wpacuIsDefinedConstant('WPACU_NO_CACHE')) {
			$skipCache = true;
		}

        // If the cache is not skipped, read the information from the cache as it's much faster
        if (! $skipCache) {
			// Speed up processing by getting the already existing final JS file URI
			// This will avoid parsing the HTML DOM and determine the combined URI paths for all the JS files
			$finalCacheList = OptimizeCommon::getAssetCachedData( self::$jsonStorageFile, OptimizeJs::getRelPathJsCacheDir(), 'js' );
		}

		if ( $skipCache || empty($finalCacheList) ) {
			/*
			 * NO CACHING TRANSIENT; Parse the DOM
			*/
			// Nothing in the database records or the retrieved cached file does not exist?
			OptimizeCommon::clearAssetCachedData(self::$jsonStorageFile);

			$combinableList = array();

			$jQueryMigrateInBody = false;
			$jQueryLibInBodyCount = 0;

			$minifyJsInlineTagsIsNotEnabled = ! (in_array(Main::instance()->settings['minify_loaded_js_for'], array('inline', 'all')) && MinifyJs::isMinifyJsEnabled());

			if ($minifyJsInlineTagsIsNotEnabled) {
				$domTag = Misc::initDOMDocument();

				// Strip irrelevant tags to boost the speed of the parser (e.g. NOSCRIPT / SCRIPT(inline) / STYLE)
				// Sometimes, inline CODE can be too large, and it takes extra time for loadHTML() to parse
				$htmlSourceAlt = preg_replace( '@<script(| (type=(\'|"|)text/(javascript|template|html)(\'|"|)))>.*?</script>@si', '', $htmlSource );
				$htmlSourceAlt = preg_replace( '@<(style|noscript)[^>]*?>.*?</\\1>@si', '', $htmlSourceAlt );
				$htmlSourceAlt = preg_replace( '#<link([^<>]+)/?>#iU', '', $htmlSourceAlt );

				if (Main::instance()->isFrontendEditView) {
                    $htmlSourceAlt = OptimizeCommon::clearFrontendFormFromHtmlSourceForProcessing($htmlSourceAlt);
				}

				if ($htmlSourceAlt === '') {
					$htmlSourceAlt = $htmlSource;
				}

				$domTag->loadHTML( $htmlSourceAlt );
			} else {
				$domTag = OptimizeCommon::getDomLoadedTag($htmlSource, 'combineJs');
			}

			// Only keep combinable JS files
			foreach ( array( 'head', 'body' ) as $docLocationScript ) {
				$groupIndex = 1;

				$docLocationElements = $domTag->getElementsByTagName($docLocationScript)->item(0);
				if ($docLocationElements === null) { continue; }

				// High accuracy (e.g. it ignores tags inside HTML comments, conditional or not)
				$scriptTags = $docLocationElements->getElementsByTagName('script');
				if ($scriptTags === null) { continue; }

				if (Main::instance()->settings['combine_loaded_js_defer_body']) {
					ObjectCache::wpacu_cache_set('wpacu_html_dom_body_tag_for_js', $docLocationElements);
				}

				foreach ($scriptTags as $tagObject) {
					$scriptAttributes = array();

					if ( ! empty($tagObject->attributes) ) {
						foreach ( $tagObject->attributes as $attrObj ) {
							$scriptAttributes[ $attrObj->nodeName ] = trim( $attrObj->nodeValue );
						}
					}

					$scriptNotCombinable = false; // default (usually, most of the SCRIPT tags can be optimized)

					// Check if the CSS file has any 'data-wpacu-skip' attribute; if it does, do not alter it
					if (isset($scriptAttributes['data-wpacu-skip'])
                    ) {
						$scriptNotCombinable = true;
					}

					$handleToCheck  = isset($scriptAttributes['data-wpacu-script-handle']) ? $scriptAttributes['data-wpacu-script-handle'] : ''; // Maybe: JS Inline (Before, After)
					$hasSrc         = isset($scriptAttributes['src']) && trim($scriptAttributes['src']); // No valid SRC attribute? It's not combinable (e.g. an inline tag)
					$isPluginScript = isset($scriptAttributes['data-wpacu-plugin-script']); // Only of the user is logged-in (skip it as it belongs to the Asset CleanUp (Pro) plugin)

					if (! $scriptNotCombinable && (! $hasSrc || $isPluginScript)) {
						// Inline tag? Skip it in the BODY
						if ($docLocationScript === 'body') {
							continue;
						}

						// Because of jQuery, we will not have the list of all inline scripts and then the combined files as it is in BODY
						if ($docLocationScript === 'head') {
							if ($handleToCheck === '' && isset($scriptAttributes['id'])) {
								$replaceToGetHandle = '';
								if (strpos($scriptAttributes['id'], '-js-extra') !== false)        { $replaceToGetHandle = '-js-extra';        }
								if (strpos($scriptAttributes['id'], '-js-before') !== false)       { $replaceToGetHandle = '-js-before';       }
								if (strpos($scriptAttributes['id'], '-js-after') !== false)        { $replaceToGetHandle = '-js-after';        }
								if (strpos($scriptAttributes['id'], '-js-translations') !== false) { $replaceToGetHandle = '-js-translations'; }

								if ($replaceToGetHandle) {
									$handleToCheck = str_replace( $replaceToGetHandle, '', $scriptAttributes['id'] ); // Maybe: JS Inline (Data)
								}
								}

							// Once an inline SCRIPT (with few exceptions below), except the ones associated with an enqueued script tag (with "src") is stumbled upon, a new combined group in the HEAD tag will be formed
							if ($handleToCheck && OptimizeCommon::appendInlineCodeToCombineAssetType('js')) {
								$getInlineAssociatedWithHandle = OptimizeJs::getInlineAssociatedWithScriptHandle($handleToCheck, $wpacuRegisteredScripts, 'handle');

								if ( ($getInlineAssociatedWithHandle['data'] || $getInlineAssociatedWithHandle['before'] || $getInlineAssociatedWithHandle['after'])
								     || in_array(trim($tagObject->nodeValue), array($getInlineAssociatedWithHandle['data'], $getInlineAssociatedWithHandle['before'], $getInlineAssociatedWithHandle['after']))
								     || (strncmp(trim($tagObject->nodeValue), '/* <![CDATA[ */', 15) === 0 && Misc::endsWith(trim($tagObject->nodeValue), '/* ]]> */')) ) {

									// It's associated with the enqueued scripts, or it's a (standalone) CDATA inline tag added via wp_localize_script()
									// Skip it instead and if the CDATA is not standalone (e.g. not associated with any script tag), the loop will "stay" in the same combined group
									continue;
								}
							}

							$scriptNotCombinable = true;
						}
					}

					$isInGroupType = 'standard';
					$isJQueryLib = $isJQueryMigrate = false;

					// Has SRC and $isPluginScript is set to false OR it does not have "data-wpacu-skip" attribute
					if (! $scriptNotCombinable) {
						$src = (string)$scriptAttributes['src'];

						if (self::skipCombine($src, $handleToCheck)) {
							$scriptNotCombinable = true;
						}

						// Avoid any errors when code like the following one is used:
						// wp.i18n.setLocaleData( localeData, domain );
						// Because the inline JS is not appended to the combined JS, /wp-includes/js/dist/i18n.(min).js has to be called earlier (outside the combined JS file)
						if ( ! OptimizeCommon::appendInlineCodeToCombineAssetType('js') && (strpos($src, '/wp-includes/js/dist/i18n.') !== false) ) {
							$scriptNotCombinable = true;
						}

						if (isset($scriptAttributes['data-wpacu-to-be-preloaded-basic']) && $scriptAttributes['data-wpacu-to-be-preloaded-basic']) {
							$scriptNotCombinable = true;
						}

						// Was it optimized and has the URL updated? Check the Source URL
						if (! $scriptNotCombinable && isset($scriptAttributes['data-wpacu-script-rel-src-before']) && $scriptAttributes['data-wpacu-script-rel-src-before'] && self::skipCombine($scriptAttributes['data-wpacu-script-rel-src-before'], $handleToCheck)) {
							$scriptNotCombinable = true;
						}

						$isJQueryLib     = isset($scriptAttributes['data-wpacu-jquery-core-handle']);
						$isJQueryMigrate = isset($scriptAttributes['data-wpacu-jquery-migrate-handle']);

						if (isset($scriptAttributes['async'], $scriptAttributes['defer'])) { // Has both "async" and "defer"
							$isInGroupType = 'async_defer';
						} elseif (isset($scriptAttributes['async'])) { // Has only "async"
							$isInGroupType = 'async';
						} elseif (isset($scriptAttributes['defer'])) { // Has only "defer"
							// Does it have "defer" attribute, it's combinable (all checks were already done), loads in the BODY tag and "combine_loaded_js_defer_body" is ON? Keep it to the combination list
							$isCombinableWithBodyDefer = (! $scriptNotCombinable && $docLocationScript === 'body' && Main::instance()->settings['combine_loaded_js_defer_body']);

							if (! $isCombinableWithBodyDefer) {
								$isInGroupType = 'defer'; // Otherwise, add it to the "defer" group type
							}
						}
					}

					if ( ! $scriptNotCombinable ) {
						// It also checks the domain name to make sure no external scripts would be added to the list
						if ( $localAssetPath = OptimizeCommon::getLocalAssetPath( $src, 'js' ) ) {
							$scriptExtra = array();

							if ( isset( $scriptAttributes['data-wpacu-script-handle'], $wpacuRegisteredScripts[ $scriptAttributes['data-wpacu-script-handle'] ]->extra ) && OptimizeCommon::appendInlineCodeToCombineAssetType('js') ) {
								$scriptExtra = $wpacuRegisteredScripts[ $scriptAttributes['data-wpacu-script-handle'] ]->extra;

								$anyScriptTranslations = method_exists('wp_scripts', 'print_translations')
									? wp_scripts()->print_translations( $scriptAttributes['data-wpacu-script-handle'], false )
									: false;

								if ( $anyScriptTranslations ) {
									$scriptExtra['translations'] = $anyScriptTranslations;
								}
							}

							// Standard (could be multiple groups per $docLocationScript), Async & Defer, Async, Defer
							$groupByType = ($isInGroupType === 'standard') ? $groupIndex : $isInGroupType;

							if ($docLocationScript === 'body') {
								if ($isJQueryLib || strpos($localAssetPath, '/wp-includes/js/jquery/jquery.js') !== false) {
									$jQueryLibInBodyCount++;
								}

								if ($isJQueryMigrate || strpos($localAssetPath, '/wp-includes/js/jquery/jquery-migrate') !== false) {
									$jQueryLibInBodyCount++;
									$jQueryMigrateInBody = true;
								}
							}

							$combinableList[$docLocationScript][$groupByType][] = array(
								'src'   => $src,
								'local' => $localAssetPath,
								'info'  => array(
									'is_jquery'         => $isJQueryLib,
									'is_jquery_migrate' => $isJQueryMigrate
								),
								'extra' => $scriptExtra
							);

							if ($docLocationScript === 'body' && $jQueryLibInBodyCount === 2) {
								$jQueryLibInBodyCount = 0; // reset it
								$groupIndex ++; // a new JS group will be created if jQuery & jQuery Migrate are combined in the BODY
							}
						}
					} else {
						$groupIndex ++; // a new JS group will be created (applies to "standard" ones only)
					}
				}
			}

			// Could be pages such as maintenance mode with no external JavaScript files
			if (empty($combinableList)) {
				return $htmlSource;
			}

			$finalCacheList = array();

			foreach ($combinableList as $docLocationScript => $combinableListGroups) {
				$groupNo = 1;

				foreach ($combinableListGroups as $groupType => $groupFiles) {
					// Any groups having one file? Then it's not really a group and the file should load on its own
					// Could be one extra file besides the jQuery & jQuery Migrate group or the only JS file called within the HEAD
					if (count($groupFiles) < 2) {
						continue;
					}

					$localAssetsPaths = $groupScriptSrcs = array();
					$localAssetsExtra = array();
					$jQueryIsIncludedInGroup = false;

					foreach ($groupFiles as $groupFileData) {
						if ($groupFileData['info']['is_jquery'] || strpos($groupFileData['local'], '/wp-includes/js/jquery/jquery.js') !== false) {
							$jQueryIsIncludedInGroup = true;

							// Is jQuery in the BODY without jQuery Migrate loaded?
							// Isolate it as it needs to be the first to load in case there are inline scripts calling it before the combined group(s)
							if ($docLocationScript === 'body' && ! $jQueryMigrateInBody) {
								continue;
							}
						}

						$src                    = $groupFileData['src'];
						$groupScriptSrcs[]      = $src;
						$localAssetsPaths[$src] = $groupFileData['local'];
						$localAssetsExtra[$src] = $groupFileData['extra'];
					}

					$maybeDoJsCombine = self::maybeDoJsCombine(
						$localAssetsPaths,
						$localAssetsExtra,
						$docLocationScript
					);

					// Local path to combined CSS file
					$localFinalJsFile = $maybeDoJsCombine['local_final_js_file'];

					// URI (e.g. /wp-content/cache/asset-cleanup/[file-name-here.js]) to the combined JS file
					$uriToFinalJsFile = $maybeDoJsCombine['uri_final_js_file'];

					if (! is_file($localFinalJsFile)) {
						return $htmlSource; // something is not right as the file wasn't created, we will return the original HTML source
					}

                    foreach ($groupScriptSrcs as $originalIndexKey => $src) {
                        $indexKey = $originalIndexKey; // default

                        if (strpos($src, OptimizeCommon::getRelPathPluginCacheDir()) === false) {
                            $localFilePath = OptimizeCommon::getLocalAssetPath($src, 'js');

                            // Unique Mark: SHA1 of the file contents + a unique ID (in rare cases when a different file has the same content)
                            $indexKey      = sha1_file($localFilePath) . '_'. uniqid('', true);
                        }

                        $src = str_replace('{site_url}', '', OptimizeCommon::getSourceRelPath($src, true));

                        unset($groupScriptSrcs[$originalIndexKey]);
                        $groupScriptSrcs[$indexKey] = $src;
                    }

					$finalCacheList[$docLocationScript][$groupNo] = array(
						'uri_to_final_js_file' => $uriToFinalJsFile,
						'script_srcs'          => $groupScriptSrcs
					);

					if (in_array($groupType, array('async_defer', 'async', 'defer'))) {
						if ($groupType === 'async_defer') {
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'async';
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'defer';
						} else {
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = $groupType;
						}
					}

					// Apply 'defer="defer"' to combined JS files from the BODY tag (if enabled), except the combined jQuery & jQuery Migrate Group
					if ($docLocationScript === 'body' && ! $jQueryIsIncludedInGroup && Main::instance()->settings['combine_loaded_js_defer_body']) {
						if ($isDeferAppliedOnBodyCombineGroupNo === false) {
							// Only record the first one
							$isDeferAppliedOnBodyCombineGroupNo = $groupNo;
						}

						$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'defer';
					}

					$groupNo ++;
				}
			}

			OptimizeCommon::setAssetCachedData(self::$jsonStorageFile, OptimizeJs::getRelPathJsCacheDir(), wp_json_encode($finalCacheList));
		}

		if (! empty($finalCacheList)) {
			$cdnUrls = OptimizeCommon::getAnyCdnUrls();
			$cdnUrlForJs = isset($cdnUrls['js']) ? $cdnUrls['js'] : false;

            $clearCombinedJsCache = false; // default

			foreach ( $finalCacheList as $docLocationScript => $cachedGroupsList ) {
				foreach ($cachedGroupsList as $groupNo => $cachedValues) {
					$htmlSourceBeforeGroupReplacement = $htmlSource;

					$uriToFinalJsFile = $cachedValues['uri_to_final_js_file'];
					$filesSources = $cachedValues['script_srcs'];

					// Basic Combining (1) -> replace "first" tag with the final combination tag (there would be most likely multiple groups)
					// Enhanced Combining (2) -> replace "last" tag with the final combination tag (most likely one group)
					$indexReplacement = ($combineLevel === 2) ? (count($filesSources) - 1) : 0;

					$finalTagUrl = OptimizeCommon::filterWpContentUrl($cdnUrlForJs) . OptimizeJs::getRelPathJsCacheDir() . $uriToFinalJsFile;

					$finalJsTagAttrsOutput = '';
					$extraAttrs = array();

					if ( ! empty($cachedValues['extra_attributes']) ) {
						$extraAttrs = $cachedValues['extra_attributes'];
						foreach ($extraAttrs as $finalJsTagAttr) {
							$finalJsTagAttrsOutput .= ' '.$finalJsTagAttr.'=\''.$finalJsTagAttr.'\' ';
						}
						$finalJsTagAttrsOutput = trim($finalJsTagAttrsOutput);
					}

					// No async or defer? Add the preloading for the combined JS from the BODY
					if ( ! $finalJsTagAttrsOutput && $docLocationScript === 'body' ) {
						$finalJsTagAttrsOutput = ' data-wpacu-to-be-preloaded-basic=\'1\' ';
                        wpacuDefineConstant('WPACU_REAPPLY_PRELOADING_FOR_COMBINED_JS');
					}

					// e.g. For developers that might want to add custom attributes such as data-cfasync="false"
					$finalJsTag = apply_filters(
						'wpacu_combined_js_tag',
						'<script '.$finalJsTagAttrsOutput.' '.Misc::getScriptTypeAttribute().' id=\'wpacu-combined-js-'.$docLocationScript.'-group-'.$groupNo.'\' src=\''.$finalTagUrl.'\'></script>',
						array(
							'attrs'        => $extraAttrs,
							'doc_location' => $docLocationScript,
							'group_no'     => $groupNo,
							'src'          => $finalTagUrl
						)
					);

					// Reference: https://stackoverflow.com/questions/2368539/php-replacing-multiple-spaces-with-a-single-space
					$finalJsTag = preg_replace('!\s+!', ' ', $finalJsTag);

					$scriptTagsStrippedNo = 0;

					$scriptTags = OptimizeJs::getScriptTagsFromSrcs($filesSources, $htmlSource);

					foreach ($scriptTags as $groupScriptTagIndex => $scriptTag) {
						$replaceWith = ($groupScriptTagIndex === $indexReplacement) ? $finalJsTag : '';
						$htmlSourceBeforeTagReplacement = $htmlSource;

                        $srcTagAttr = Misc::getValueFromTag($scriptTag, 'src');
                        $srcRelCleanPath = trim(OptimizeCommon::getSourceRelPath($srcTagAttr, true));

                        $indexKey = array_search($srcRelCleanPath, $filesSources);

                        // Check the SHA1 value of the file if it's in the original location (not within the caching directory)
                        if ($indexKey !== false &&
                            strpos($indexKey, '_') !== false &&
                            strpos($srcRelCleanPath, OptimizeCommon::getRelPathPluginCacheDir()) === false) {
                            list($sha1File) = explode('_', $indexKey);

                            if ($sha1File !== sha1_file(OptimizeCommon::getLocalAssetPath($srcRelCleanPath, 'js'))) {
                                // The contents of one of the files from the combined CSS were changed
                                $clearCombinedJsCache = true;
                                break 3;
                            }
                        }

						// 1) Strip any inline code associated with tag
						// 2) Finally, strip the actual tag
						$htmlSource = self::stripTagAndAnyInlineAssocCode( $scriptTag, $wpacuRegisteredScripts, $replaceWith, $htmlSource );

						if ($htmlSource !== $htmlSourceBeforeTagReplacement) {
							$scriptTagsStrippedNo ++;
						}
						}

					// At least two tags have to be stripped from the group to consider doing the group replacement
					// If the tags weren't replaced it's likely there were changes to their structure after they were cached for the group merging
					if (count($filesSources) !== $scriptTagsStrippedNo) {
						$htmlSource = $htmlSourceBeforeGroupReplacement;

                        $clearCombinedJsCache = true;
					}
				}
			}

            if ($clearCombinedJsCache) {
                OptimizeCommon::clearAssetCachedData(self::$jsonStorageFile);
                }
		}

		// Only relevant if "Defer loading JavaScript combined files from <body>" in "Settings" - "Combine CSS & JS Files" - "Combine loaded JS (JavaScript) into fewer files"
		// and there is at least one combined deferred tag

		if ( ! empty($finalCacheList['body']) && Main::instance()->settings['combine_loaded_js_defer_body'] ) {
			// CACHE RE-BUILT
			if ($isDeferAppliedOnBodyCombineGroupNo > 0 && $domTag = ObjectCache::wpacu_cache_get('wpacu_html_dom_body_tag_for_js')) {
				$strPart = "id='wpacu-combined-js-body-group-".$isDeferAppliedOnBodyCombineGroupNo."' ";

				if (strpos($htmlSource, $strPart) === false) {
					return $htmlSource; // something is funny, do not continue
				}

				list(,$htmlAfterFirstCombinedDeferScript) = explode($strPart, $htmlSource);
				$htmlAfterFirstCombinedDeferScriptMaybeChanged = $htmlAfterFirstCombinedDeferScript;
				$scriptTags = $domTag->getElementsByTagName('script');
			} else {
				// FROM THE CACHE
				foreach ($finalCacheList['body'] as $bodyCombineGroupNo => $values) {
					if (isset($values['extra_attributes']) && in_array('defer', $values['extra_attributes'])) {
						$isDeferAppliedOnBodyCombineGroupNo = $bodyCombineGroupNo;
						break;
					}
				}

				if (! $isDeferAppliedOnBodyCombineGroupNo) {
					// Not applicable to any combined group
					return $htmlSource;
				}

				$strPart = 'id=\'wpacu-combined-js-body-group-'.$isDeferAppliedOnBodyCombineGroupNo.'\'';

				$htmlAfterFirstCombinedDeferScriptMaybeChanged = false;

				if (strpos($htmlSource, $strPart) !== false) {
					list( , $htmlAfterFirstCombinedDeferScript ) = explode( $strPart, $htmlSource );
					$htmlAfterFirstCombinedDeferScriptMaybeChanged = $htmlAfterFirstCombinedDeferScript;
				}

				// It means to combine took place for any reason (e.g. only one JS file loaded in the HEAD and one in the BODY)
				if (! isset($htmlAfterFirstCombinedDeferScript)) {
					return $htmlSource;
				}

				$domTag = Misc::initDOMDocument();

				// Strip irrelevant tags to boost the speed of the parser (e.g. NOSCRIPT / SCRIPT(inline) / STYLE)
				// Sometimes, inline CODE can be too large, and it takes extra time for loadHTML() to parse
				$htmlSourceAlt = preg_replace( '@<script(| type=\'text/javascript\'| type="text/javascript")>.*?</script>@si', '', $htmlAfterFirstCombinedDeferScript );
				$htmlSourceAlt = preg_replace( '@<(style|noscript)[^>]*?>.*?</\\1>@si', '', $htmlSourceAlt );
				$htmlSourceAlt = preg_replace( '#<link([^<>]+)/?>#iU', '', $htmlSourceAlt );

				if (Main::instance()->isFrontendEditView) {
					$htmlSourceAlt = OptimizeCommon::clearFrontendFormFromHtmlSourceForProcessing($htmlSourceAlt);
				}

				// No other SCRIPT left, stop here in this case
				if (strpos($htmlSourceAlt, '<script') === false) {
					return $htmlSource;
				}

				$domTag->loadHTML( $htmlSourceAlt );
				$scriptTags = $domTag->getElementsByTagName('script');
			}

			if ( $scriptTags === null ) {
				return $htmlSource;
			}

			foreach ($scriptTags as $tagObject) {
				if (empty($tagObject->attributes)) { continue; }

				$scriptAttributes = array();

				foreach ( $tagObject->attributes as $attrObj ) {
					$scriptAttributes[ $attrObj->nodeName ] = trim( $attrObj->nodeValue );
				}

				// No "src" attribute? Skip it (most likely an inline script tag)
				if (! (isset($scriptAttributes['src']) && $scriptAttributes['src'])) {
					continue;
				}

				// Skip it as "defer" is already set
				if (isset($scriptAttributes['defer'])) {
					continue;
				}

				// Has "src" attribute and "defer" is not applied? Add it
				if ($htmlAfterFirstCombinedDeferScriptMaybeChanged !== false) {
					$htmlAfterFirstCombinedDeferScriptMaybeChanged = trim( preg_replace(
						'#\ssrc(\s+|)=(\s+|)(|"|\'|\s+)(' . preg_quote( $scriptAttributes['src'], '/' ) . ')(\3)#si',
						' src=\3\4\3 defer=\'defer\'',
						$htmlAfterFirstCombinedDeferScriptMaybeChanged
					) );
				}
			}

			if ($htmlAfterFirstCombinedDeferScriptMaybeChanged && $htmlAfterFirstCombinedDeferScriptMaybeChanged !== $htmlAfterFirstCombinedDeferScript) {
				$htmlSource = str_replace($htmlAfterFirstCombinedDeferScript, $htmlAfterFirstCombinedDeferScriptMaybeChanged, $htmlSource);
			}
		}

		libxml_clear_errors();

		// Finally, return the HTML source
		return $htmlSource;
	}

	/**
	 * @param string $src
	 * @param string $handle
     *
	 * @return bool
     * @noinspection ParameterDefaultValueIsNotNullInspection
     */
	public static function skipCombine($src, $handle = '')
	{
		// In case the handle was appended
		if ($handle !== '' && in_array($handle, MainFront::instance()->getSkipAssets('scripts'))) {
			return true;
		}

		$regExps = array(
			'#/wp-content/bs-booster-cache/#'
		);

		if (Main::instance()->settings['combine_loaded_js_exceptions'] !== '') {
			$loadedJsExceptionsPatterns = trim(Main::instance()->settings['combine_loaded_js_exceptions']);

			if (strpos($loadedJsExceptionsPatterns, "\n") !== false) {
				// Multiple values (one per line)
				foreach (explode("\n", $loadedJsExceptionsPatterns) as $loadedJsExceptionsPattern) {
					$regExps[] = '#'.trim($loadedJsExceptionsPattern).'#';
				}
			} else {
				// Only one value?
				$regExps[] = '#'.trim($loadedJsExceptionsPatterns).'#';
			}
		}

		// No exceptions set? Do not skip combination
		if (empty($regExps)) {
			return false;
		}

		foreach ($regExps as $regExp) {
			if ( @preg_match( $regExp, $src ) || ( strpos($src, $regExp) !== false ) ) {
				// Skip combination
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $localAssetsPaths
	 * @param $localAssetsExtra
	 * @param $docLocationScript
	 *
	 * @return array
     * @noinspection NestedAssignmentsUsageInspection
     */
	public static function maybeDoJsCombine($localAssetsPaths, $localAssetsExtra, $docLocationScript)
	{
		// Only combine if $shaOneCombinedUriPaths.js does not exist
		// If "?ver" value changes on any of the assets or the asset list changes in any way
		// then $shaOneCombinedUriPaths will change too and a new JS file will be generated and loaded

		// Change $assetsContents as paths to fonts and images that are relative (e.g. ../, ../../) have to be updated
		$uriToFinalJsFile = $localFinalJsFile = $finalJsContents = '';

		foreach ($localAssetsPaths as $assetHref => $localAssetsPath) {
			if ($jsContent = trim(FileSystem::fileGetContents($localAssetsPath))) {
				// Does it have a source map? Strip it
				if (strpos($jsContent, '//# sourceMappingURL=') !== false) {
					$jsContent = OptimizeCommon::stripSourceMap($jsContent, 'js');
				}

				$pathToAssetDir = OptimizeCommon::getPathToAssetDir($assetHref);

				$contentToAddToCombinedFile = '';

				if (apply_filters('wpacu_print_info_comments_in_cached_assets', true)) {
					$contentToAddToCombinedFile = '/*!' . str_replace( Misc::getWpRootDirPathBasedOnPath($localAssetsPath), '/', $localAssetsPath ) . "*/\n";
				}

				// This includes the extra from 'data' (CDATA added via wp_localize_script()) & 'before' as they are both printed BEFORE the SCRIPT tag
				$contentToAddToCombinedFile .= self::maybeWrapBetweenTryCatch(self::appendToCombineJs('translations', $localAssetsExtra, $assetHref, $pathToAssetDir), $assetHref);
				$contentToAddToCombinedFile .= self::maybeWrapBetweenTryCatch(self::appendToCombineJs('before', $localAssetsExtra, $assetHref, $pathToAssetDir), $assetHref);
				$contentToAddToCombinedFile .= self::maybeWrapBetweenTryCatch(OptimizeJs::maybeDoJsFixes($jsContent, $pathToAssetDir . '/'), $assetHref) . "\n";
				// This includes the inline 'after' the SCRIPT tag
				$contentToAddToCombinedFile .= self::maybeWrapBetweenTryCatch(self::appendToCombineJs('after', $localAssetsExtra, $assetHref, $pathToAssetDir), $assetHref);

				$finalJsContents .= $contentToAddToCombinedFile;
			}
		}

		if ($finalJsContents !== '') {
			$finalJsContents = trim($finalJsContents);
			$shaOneForCombinedJs = sha1($finalJsContents);

			$uriToFinalJsFile = $docLocationScript . '-' . $shaOneForCombinedJs . '.js';
			$localFinalJsFile  = WP_CONTENT_DIR . OptimizeJs::getRelPathJsCacheDir() . $uriToFinalJsFile;

			if (! is_file($localFinalJsFile)) {
				FileSystem::filePutContents( $localFinalJsFile, $finalJsContents );
			}
		}

		return array(
			'uri_final_js_file'   => $uriToFinalJsFile,
			'local_final_js_file' => $localFinalJsFile
		);
	}

	/**
	 * @param $addItLocation
	 * @param $localAssetsExtra
	 * @param $assetHref
	 * @param $pathToAssetDir
	 *
	 * @return string
	 */
	public static function appendToCombineJs($addItLocation, $localAssetsExtra, $assetHref, $pathToAssetDir)
	{
		$extraContentToAppend = '';
		$doJsMinifyInline = MinifyJs::isMinifyJsEnabled() && in_array(Main::instance()->settings['minify_loaded_js_for'], array('inline', 'all'));

		if ($addItLocation === 'before') {
			// [Before JS Content]
			if (isset($localAssetsExtra[$assetHref]['data']) && ($dataValue = $localAssetsExtra[$assetHref]['data'])
                && self::isInlineJsCombineable($dataValue) && trim($dataValue) !== '') {
                $cData = $doJsMinifyInline ? MinifyJs::applyMinification( $dataValue ) : $dataValue;
                $cData = OptimizeJs::maybeDoJsFixes( $cData, $pathToAssetDir . '/' );
                $extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [inline: cdata] */' : '';
                $extraContentToAppend .= $cData;
                $extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [/inline: cdata] */' : '';
                $extraContentToAppend .= "\n";
			}

			if ( ! empty($localAssetsExtra[$assetHref]['before']) ) {
				$inlineBeforeJsData = '';

				foreach ($localAssetsExtra[$assetHref]['before'] as $beforeData) {
					if (! is_bool($beforeData) && self::isInlineJsCombineable($beforeData)) {
						$inlineBeforeJsData .= $beforeData . "\n";
					}
				}

				if (trim($inlineBeforeJsData)) {
					$inlineBeforeJsData = OptimizeJs::maybeAlterContentForInlineScriptTag( $inlineBeforeJsData, $doJsMinifyInline );
					$inlineBeforeJsData = OptimizeJs::maybeDoJsFixes( $inlineBeforeJsData, $pathToAssetDir . '/' );
					$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [inline: before] */' : '';
					$extraContentToAppend .= $inlineBeforeJsData;
					$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [/inline: before] */' : '';
					$extraContentToAppend .= "\n";
				}
			}
			// [/Before JS Content]
		} elseif ($addItLocation === 'after') {
			// [After JS Content]
			if ( ! empty($localAssetsExtra[$assetHref]['after']) ) {
				$inlineAfterJsData = '';

				foreach ($localAssetsExtra[$assetHref]['after'] as $afterData) {
					if (! is_bool($afterData) && self::isInlineJsCombineable($afterData)) {
						$inlineAfterJsData .= $afterData."\n";
					}
				}

				if ( trim($inlineAfterJsData) ) {
					$inlineAfterJsData = OptimizeJs::maybeAlterContentForInlineScriptTag( $inlineAfterJsData, $doJsMinifyInline );
					$inlineAfterJsData = OptimizeJs::maybeDoJsFixes( $inlineAfterJsData, $pathToAssetDir . '/' );
					$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [inline: after] */' : '';
					$extraContentToAppend .= $inlineAfterJsData;
					$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [/inline: after] */' : '';
					$extraContentToAppend .= "\n";
				}
			}
			// [/After JS Content]
		} elseif ($addItLocation === 'translations' && isset($localAssetsExtra[$assetHref]['translations']) && $localAssetsExtra[$assetHref]['translations']) {
			$inlineAfterJsData = OptimizeJs::maybeAlterContentForInlineScriptTag( $localAssetsExtra[$assetHref]['translations'], $doJsMinifyInline );
			$inlineAfterJsData = OptimizeJs::maybeDoJsFixes( $inlineAfterJsData, $pathToAssetDir . '/' );
			$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [inline: translations] */' : '';
			$extraContentToAppend .= $inlineAfterJsData;
			$extraContentToAppend .= apply_filters('wpacu_print_info_comments_in_cached_assets', true) ? '/* [/inline: translations] */' : '';
			$extraContentToAppend .= "\n";
		}

		return $extraContentToAppend;
	}

	/**
	 * @param $jsCode
	 * @param $sourceUrl
	 *
	 * @return string
	 */
	public static function maybeWrapBetweenTryCatch($jsCode, $sourceUrl)
	{
		if ($jsCode && Main::instance()->settings['combine_loaded_js_try_catch']) {
			return <<<JS
try {
	{$jsCode}
} catch (err) {
	console.log("Asset CleanUp - There is a JavaScript error related to the following source: {$sourceUrl} - Error: " + err.message);
}
JS;
		}

		return $jsCode;
	}

	/**
	 * @param $scriptTag
	 * @param $wpacuRegisteredScripts
	 * @param $replaceWith
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function stripTagAndAnyInlineAssocCode($scriptTag, $wpacuRegisteredScripts, $replaceWith, $htmlSource)
	{
		if (OptimizeCommon::appendInlineCodeToCombineAssetType('js')) {
			$scriptExtrasValue      = OptimizeJs::getInlineAssociatedWithScriptHandle($scriptTag, $wpacuRegisteredScripts, 'tag', 'value');

			$scriptExtraTranslationsValue  = (isset($scriptExtrasValue['translations']) && $scriptExtrasValue['translations']) ? $scriptExtrasValue['translations']   : '';
			$scriptExtraCdataValue         = (isset($scriptExtrasValue['data'])   && $scriptExtrasValue['data'])   ? $scriptExtrasValue['data']   : '';
			$scriptExtraBeforeValue        = (isset($scriptExtrasValue['before']) && $scriptExtrasValue['before']) ? $scriptExtrasValue['before'] : '';
			$scriptExtraAfterValue         = (isset($scriptExtrasValue['after'])  && $scriptExtrasValue['after'])  ? $scriptExtrasValue['after']  : '';

			$scriptExtrasHtml       = OptimizeJs::getInlineAssociatedWithScriptHandle($scriptTag, $wpacuRegisteredScripts, 'tag', 'html');
			preg_match_all('#data-wpacu-script-handle=([\'])' . '(.*)' . '(\1)#Usmi', $scriptTag, $outputMatches);
			$scriptHandle = (isset($outputMatches[2][0]) && $outputMatches[2][0]) ? trim($outputMatches[2][0], '"\'') : '';

			$scriptExtraTranslationsHtml = (isset($scriptExtrasHtml['translations']) && $scriptExtrasHtml['translations']) ? $scriptExtrasHtml['translations'] : '';
			$scriptExtraCdataHtml        = (isset($scriptExtrasHtml['data'])   && $scriptExtrasHtml['data'])   ? $scriptExtrasHtml['data']   : '';
			$scriptExtraBeforeHtml       = (isset($scriptExtrasHtml['before']) && $scriptExtrasHtml['before']) ? $scriptExtrasHtml['before'] : '';
			$scriptExtraAfterHtml        = (isset($scriptExtrasHtml['after'])  && $scriptExtrasHtml['after'])  ? $scriptExtrasHtml['after']  : '';

			if ($scriptExtraTranslationsValue || $scriptExtraCdataValue || $scriptExtraBeforeValue || $scriptExtraAfterValue) {
				if ( $scriptExtraCdataValue && self::isInlineJsCombineable($scriptExtraCdataValue) ) {
					$htmlSource = str_replace($scriptExtraCdataHtml, '', $htmlSource );
				}

				if ($scriptExtraTranslationsValue) {
					$repsBefore = array(
						$scriptExtraTranslationsHtml => '',
						str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $scriptHandle . '\' ', $scriptExtraTranslationsHtml ) => '',
						'>'."\n".$scriptExtraTranslationsValue."\n".'</script>' => '></script>',
						$scriptExtraTranslationsValue."\n" => ''
					);
					$htmlSource = str_replace(array_keys($repsBefore), array_values($repsBefore), $htmlSource );
				}

				if ($scriptExtraBeforeValue && self::isInlineJsCombineable($scriptExtraBeforeValue)) {
					$repsBefore = array(
						$scriptExtraBeforeHtml => '',
						str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $scriptHandle . '\' ', $scriptExtraBeforeHtml ) => '',
						'>'."\n".$scriptExtraBeforeValue."\n".'</script>' => '></script>',
						$scriptExtraBeforeValue."\n" => ''
					);
					$htmlSource = str_replace(array_keys($repsBefore), array_values($repsBefore), $htmlSource );
				}

				if ($scriptExtraAfterValue && self::isInlineJsCombineable($scriptExtraAfterValue)) {
					$repsBefore = array(
						$scriptExtraAfterHtml => '',
						str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $scriptHandle . '\' ', $scriptExtraAfterHtml ) => '',
						'>'."\n".$scriptExtraAfterValue."\n".'</script>' => '></script>',
						$scriptExtraAfterValue."\n" => ''
					);
					$htmlSource = str_replace(array_keys($repsBefore), array_values($repsBefore), $htmlSource);
				}
			}
		}

		// Finally, strip/replace the tag
		return str_replace( array($scriptTag."\n", $scriptTag), $replaceWith, $htmlSource );
	}

	/**
	 * This is to prevent certain inline JS to be appended to the combined JS files in order to avoid lots of disk space (sometimes a few GB) of JS combined files
	 *
	 * @param $jsInlineValue
	 *
	 * @return bool
	 */
	public static function isInlineJsCombineable($jsInlineValue)
	{
		// The common WordPress nonce
		if (strpos($jsInlineValue, 'nonce') !== false) {
			return false;
		}

		// WooCommerce Cart Fragments
		if (strpos($jsInlineValue, 'wc_cart_hash_') !== false && strpos($jsInlineValue, 'cart_hash_key') !== false) {
			return false;
		}

		if (substr(trim($jsInlineValue), 0, 1) === '{' && substr(trim($jsInlineValue), -1, 1) === '}') {
			@json_decode($jsInlineValue);

			if (wpacuJsonLastError() === JSON_ERROR_NONE) {
				return false; // it's a JSON format (e.g. type="application/json" from "wordpress-popular-posts" plugin)
			}
		}

		return true; // default
	}
}

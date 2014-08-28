<?php

/**
 * @file plugins/viewableFiles/imgsBookReaderFiles/Imgs4BookReaderSubmissionFilePlugin.inc.php
 *
 * Copyright (c) 2014 FU Berlin, Germany
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class Imgs4BookReaderSubmissionFilePlugin
 * @ingroup plugins_viewableFiles_imgsBookReaderFiles
 *
 * @brief Class for Imgs4BookReaderSubmissionFile plugin
 */

import('classes.plugins.ViewableFilePlugin');

class Imgs4BookReaderSubmissionFilePlugin extends ViewableFilePlugin {
	/**
	 * @see Plugin::register()
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('CatalogBookHandler::detail_view', array($this, 'callbackImage'));
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Callback that renders the detail image.
	 *
	 * @param $hookName string
	 * @param $args array
	 * @return string
	 */
	function callbackImage($hookName, $args) {
		$publishedMonograph =& $args[1];
		$submissionFile =& $args[2];
		$fileId = $args[3];
		$revision =$args[4];
		if ($this->canHandle($publishedMonograph, $submissionFile)) {
			import('plugins.viewableFiles.imgsBookReaderFiles.ImgFileManager');
			$monographFileManager = new ImgFileManager($publishedMonograph->getContextId(), $publishedMonograph->getId());
			return $monographFileManager->downloadImgFile($fileId, $revision, false, null);
		}
	}
	
	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.viewableFiles.imgsBookReaderFiles.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.viewableFiles.imgsBookReaderFiles.description');
	}

	/**
	 * @copydoc ViewableFilePlugin::canHandle
	 */
	function canHandle($publishedMonograph, $submissionFile) {
		return ($submissionFile->getFileType() == 'application/zip');
	}

	/**
	 * @see ViewableFilePlugin::displaySubmissionFile
	 */
	function displaySubmissionFile($publishedMonograph, $submissionFile) {
		$request = $this->getRequest();
		$templateMgr = TemplateManager::getManager($this->getRequest());
		$templateMgr->assign('pluginJSPath', $this->getJSPath($request));
		$templateMgr->assign('pluginCSSPath', $this->getCSSPath($request));
		
		$filePath = $submissionFile->getFilePath();
		$path_parts = pathinfo($filePath);
		$imgRootPath = $path_parts['dirname']."/". $path_parts['filename']."/imgs";
		$num_files = 0;
		if (is_readable($imgRootPath)) {
			$num_files = count(glob($imgRootPath.'/*'));
			//$iterator = new GlobIterator('*.xml');
			//$num_files = $iterator->count();
		}
		// zero based
		$templateMgr->assign('pluginBookLeafs', $num_files -1 );
		return parent::displaySubmissionFile($publishedMonograph, $submissionFile);
	}

	/**
	 * returns the base path for JS included in this plugin.
	 * @param $request PKPRequest
	 * @return string
	 */
	function getJSPath($request) {
		return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
	}
	
		/**
	 * returns the base path for CSS included in this plugin.
	 * @param $request PKPRequest
	 * @return string
	 */
	function getCSSPath($request) {
		return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/css';
	}
}

?>

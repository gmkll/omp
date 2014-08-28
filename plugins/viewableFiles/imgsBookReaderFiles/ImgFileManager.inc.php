<?php

/**
 * @file plugins/viewableFiles/imgsBookReaderFiles/ImgFileManager.inc.php
 *
 * Copyright (c) 2014 FU Berlin, Germany
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ImgFileManager
 * @ingroup plugins_viewableFiles_imgsBookReaderFiles
 *
 * @brief Helper class for view-backed image file management tasks.
 *

 */

import('lib.pkp.classes.file.BaseSubmissionFileManager');

class ImgFileManager extends BaseSubmissionFileManager {
	/**
	 * Constructor.
	 * @param $contextId int
	 * @param $submissionId int
	 */
	function ImgFileManager($contextId, $submissionId) {
		parent::BaseSubmissionFileManager($contextId, $submissionId);
	}


	/**
	 * Download a file.
	 * @param $fileId int the file id of the file to download
	 * @param $revision int the revision of the file to download
	 * @param $inline boolean print file as inline instead of attachment, optional
	 * @param $filename string The client-side download filename (optional)
	 * @return boolean
	 */
	function downloadImgFile($fileId, $revision = null, $inline = false, $filename = null) {
		$returner = false;
		$submissionFile = $this->_getFile($fileId, $revision);
		if (isset($submissionFile)) {
			// Make sure that the file belongs to the submission.
			if ($submissionFile->getSubmissionId() != $this->getSubmissionId()) fatalError('Invalid file id!');
			//ImgFileManager::recordView($submissionFile);
			// Send the file to the user.
			$filePath = $submissionFile->getFilePath();
			$mediaType = $submissionFile->getFileType();
			$path_parts = pathinfo($filePath);
			
			$str = parse_str(PKPRequest::getQueryString(), $output);
			$imgIndex = null;
			if (array_key_exists('img', $output)) {
				$imgIndex= $output['img'];
			}
			$imgRootPath = $path_parts['dirname']."/". $path_parts['filename']."/imgs";
			$imgPath = $imgRootPath."/cemog-buch".$imgIndex.".jpg"; 
			$fileType=null;
			if (is_readable($imgPath)) {
				// its an image
				$fileType = image_type_to_mime_type(exif_imagetype($imgPath));
			}
			$returner = parent::downloadFile($imgPath, $fileType, 
				$inline, $filename);
		}
		return $returner;
	}
	
	/**
	 * Record a file view in database.
	 * @param $submissionFile SubmissionFile
	 */
	function recordView(&$submissionFile) {
		// Mark the file as viewed by this user.
		$sessionManager = SessionManager::getManager();
		$session = $sessionManager->getUserSession();
		$user = $session->getUser();
		if (is_a($user, 'User')) {
			$viewsDao = DAORegistry::getDAO('ViewsDAO');
			$viewsDao->recordView(
			ASSOC_TYPE_SUBMISSION_FILE, $submissionFile->getFileIdAndRevision(),
			$user->getId()
			);
		}
	}
	
	/**
	 * Internal helper method to retrieve file
	 * information by file ID.
	 * @param $fileId integer
	 * @param $revision integer
	 * @return SubmissionFile
	 */
	function _getFile($fileId, $revision = null) {
		$submissionFileDao = DAORegistry::getDAO('SubmissionFileDAO'); /* @var $submissionFileDao SubmissionFileDAO */
		if ($revision) {
			return $submissionFileDao->getRevision($fileId, $revision);
		} else {
			return $submissionFileDao->getLatestRevision($fileId);
		}
	}
}

?>

<?php
/**
 * @defgroup plugins_viewableFiles_imgsBookReaderFiles Images for BookReader Viewer submission file plugin
 */

/**
 * @file plugins/viewableFiles/imgsBookReaderFiles/index.php
 *
 * Copyright (c) 2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_viewableFiles_jimgsBookReaderFiles
 * @brief Wrapper for pdf submission file plugin.
 *
 */

require_once('Imgs4BookReaderSubmissionFilePlugin.inc.php');

return new Imgs4BookReaderSubmissionFilePlugin();

?>

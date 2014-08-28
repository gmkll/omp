{**
 * plugins/viewableFile/imgsBookReaderFiles/display.tpl
 *
 * Copyright (c) 2014 FU Berlin, Germany
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a Bookreader galley.
 *}

{url|assign:"imgBaseUrl" op="detail_view" path=$publishedMonograph->getId()|to_array:$submissionFile->getAssocId():$submissionFile->getFileIdAndRevision() escape=false}{* Assoc ID is publication format *}

{url|assign:"aboutBookUrl" op="book" path=$publishedMonograph->getId()}{* Assoc ID is publication format *}
{assign var="pageTitleTranslated" value=$publishedMonograph->getLocalizedFullTitle()}
{if !$pageTitleTranslated}{translate|assign:"pageTitleTranslated" key=$pageTitle}{/if}
<script type="text/javascript"><!--{literal}
$(document).ready(function(){
 
	var cw;
	// imgBaseUrl e.g. http://localhost:9076/omp/index.php/testpress/catalog/download/15/5/22-1
	
	function openBookReader() {
		cw= window.open("BookReader","");
		var html = $("#BookReaderWrapper").html();
		cw.document.write("<html><head>");
		cw.document.writeln('<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}/lib/pkp/lib/bookreader/BookReader.css"/>');
		cw.document.writeln('<!-- Custom CSS overrides -->');
		cw.document.writeln('<link rel="stylesheet" type="text/css" href="{/literal}{$pluginCSSPath}{literal}/BookReaderOMP.css"/>');
		cw.document.writeln(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.writeln(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.writeln(unescape("%3Cscript src='http://www.archive.org/bookreader/dragscrollable.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.writeln(unescape("%3Cscript src='http://www.archive.org/bookreader/jquery.colorbox-min.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.writeln(unescape("%3Cscript src='http://www.archive.org/bookreader/jquery.ui.ipad.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.writeln(unescape("%3Cscript src='http://www.archive.org/bookreader/jquery.bt.min.js' type='text/javascript'%3E%3C/script%3E"));
		
		cw.document.writeln(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/lib/bookreader/BookReader.js' type='text/javascript'%3E%3C/script%3E"));
		
		cw.document.writeln(unescape("%3Cscript type='text/javascript'%3E") +
		" var imgBaseUrl = '{/literal}{$imgBaseUrl|escape:'javascript'}{literal}';" + 
		" var bookTitle =  '{/literal}{$pageTitleTranslated|escape:'javascript'}{literal}';" + 
		" var aboutBookUrl =  '{/literal}{$aboutBookUrl|escape:'javascript'}{literal}';" + 
		" var bookLeafs =  '{/literal}{$pluginBookLeafs|escape:'javascript'}{literal}';" + 
		unescape("%3C/script%3E"));
		
		cw.document.writeln(unescape("%3Cscript src='{/literal}{$pluginJSPath}{literal}/inlineBookReader.js' type='text/javascript'%3E%3C/script%3E"));
		cw.document.write ("</head><body>");
		cw.document.writeln(html);
		cw.document.writeln("</body></html>");
		cw.document.close();
	}
		
	function closeChildWindow() {
		if (!cw) {
		  alert("There is no BookReader window open.");
		}
		else {
		  cw.close();
		  cw = undefined;
		}
	  }
	function openChildWindow() {
		if (cw) {
		  alert("We already have BookReader open.");
		}
		else {
		  openBookReader();
		}
	}
	document.getElementById('btnOpen').onclick = openChildWindow;
    document.getElementById('btnClose').onclick = closeChildWindow;
	
});
	// -->    {/literal}
</script>
{translate|assign:"noPluginText" key="submission.bookreader.pluginMissing"}
<div id="BookReaderWrapper">
	<div id="BookReader">
	{* pluginBookLeafs:{$pluginBookLeafs} *}
		<noscript>
		<p>
		    <div id="pluginMissing">{$noPluginText|escape:"javascript"}</div>
			The BookReader requires JavaScript to be enabled. Please check that your browser supports JavaScript and 
			that it is enabled in the browser settings.  
		</p>
		</noscript>
	</div>
</div>
<input type='button' id='btnOpen' value='Open BookReader'>
<input type='button' id='btnClose' value='Close BookReader'>


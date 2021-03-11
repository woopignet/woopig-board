var smf_formSubmitted = false;
var lastKeepAliveCheck = new Date().getTime();
var smf_editorArray = new Array();

// Some very basic browser detection - from Mozilla's sniffer page.
var ua = navigator.userAgent.toLowerCase();

var is_opera = ua.indexOf('opera') != -1;
var is_opera5 = ua.indexOf('opera/5') != -1 || ua.indexOf('opera 5') != -1;
var is_opera6 = ua.indexOf('opera/6') != -1 || ua.indexOf('opera 6') != -1;
var is_opera7 = ua.indexOf('opera/7') != -1 || ua.indexOf('opera 7') != -1;
var is_opera8 = ua.indexOf('opera/8') != -1 || ua.indexOf('opera 8') != -1;
var is_opera9 = ua.indexOf('opera/9') != -1 || ua.indexOf('opera 9') != -1;
var is_opera95 = ua.indexOf('opera/9.5') != -1 || ua.indexOf('opera 9.5') != -1;
var is_opera96 = ua.indexOf('opera/9.6') != -1 || ua.indexOf('opera 9.6') != -1;
var is_opera10 = (ua.indexOf('opera/9.8') != -1 || ua.indexOf('opera 9.8') != -1 || ua.indexOf('opera/10.') != -1 || ua.indexOf('opera 10.') != -1) || ua.indexOf('version/10.') != -1;
var is_opera95up = is_opera95 || is_opera96 || is_opera10;

var is_ff = (ua.indexOf('firefox') != -1 || ua.indexOf('iceweasel') != -1 || ua.indexOf('icecat') != -1 || ua.indexOf('shiretoko') != -1 || ua.indexOf('minefield') != -1) && !is_opera;
var is_gecko = ua.indexOf('gecko') != -1 && !is_opera;

var is_chrome = ua.indexOf('chrome') != -1;
var is_safari = ua.indexOf('applewebkit') != -1 && !is_chrome;
var is_webkit = ua.indexOf('applewebkit') != -1;

var is_ie = ua.indexOf('msie') != -1 && !is_opera;
var is_ie4 = is_ie && ua.indexOf('msie 4') != -1;
var is_ie5 = is_ie && ua.indexOf('msie 5') != -1;
var is_ie50 = is_ie && ua.indexOf('msie 5.0') != -1;
var is_ie55 = is_ie && ua.indexOf('msie 5.5') != -1;
var is_ie5up = is_ie && !is_ie4;
var is_ie6 = is_ie && ua.indexOf('msie 6') != -1;
var is_ie6up = is_ie5up && !is_ie55 && !is_ie5;
var is_ie6down = is_ie6 || is_ie5 || is_ie4;
var is_ie7 = is_ie && ua.indexOf('msie 7') != -1;
var is_ie7up = is_ie6up && !is_ie6;
var is_ie7down = is_ie7 || is_ie6 || is_ie5 || is_ie4;

var is_ie8 = is_ie && ua.indexOf('msie 8') != -1;
var is_ie8up = is_ie8 && !is_ie7down;

var is_iphone = ua.indexOf('iphone') != -1 || ua.indexOf('ipod') != -1;
var is_android = ua.indexOf('android') != -1;

var ajax_indicator_ele = null;

// The purpose of this code is to fix the height of overflow: auto blocks, because some browsers can't figure it out for themselves.
function smf_codeBoxFix()
{
	var codeFix = document.getElementsByTagName('code');
	for (var i = codeFix.length - 1; i >= 0; i--)
	{
		if (is_webkit && codeFix[i].offsetHeight < 20)
			codeFix[i].style.height = (codeFix[i].offsetHeight + 20) + 'px';

		else if (is_ff && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0))
			codeFix[i].style.overflow = 'scroll';

		else if ('currentStyle' in codeFix[i] && codeFix[i].currentStyle.overflow == 'auto' && (codeFix[i].currentStyle.height == '' || codeFix[i].currentStyle.height == 'auto') && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0) && (codeFix[i].offsetHeight != 0))
			codeFix[i].style.height = (codeFix[i].offsetHeight + 24) + 'px';
	}
}

// Toggles the element height and width styles of an image.
function smc_toggleImageDimensions()
{
	var oImages = document.getElementsByTagName('IMG');
	for (oImage in oImages)
	{
		// Not a resized image? Skip it.
		if (oImages[oImage].className == undefined || oImages[oImage].className.indexOf('bbc_img resized') == -1)
			continue;

		oImages[oImage].style.cursor = 'pointer';
		oImages[oImage].onclick = function() {
			this.style.width = this.style.height = this.style.width == 'auto' ? null : 'auto';
		};
	}
}

// Adds a button to a certain button strip.
function smf_addButton(sButtonStripId, bUseImage, oOptions)
{
	var oButtonStrip = document.getElementById(sButtonStripId);
	var aItems = oButtonStrip.getElementsByTagName('li');

	// Remove the 'last' class from the last item.
	if (aItems.length > 0)
	{
		var oLastItem = aItems[aItems.length - 1];
		oLastItem.className = oLastItem.className.replace(/\s*last/, 'position_holder');
	}

	// Add the button.
	var oButtonStripList = oButtonStrip.getElementsByTagName('ul')[0];
	var oNewButton = document.createElement('li');
	oNewButton.className = 'last';
	setInnerHTML(oNewButton, '<a href="' + oOptions.sUrl + '" ' + ('sCustom' in oOptions ? oOptions.sCustom : '') + '><span' + ('sId' in oOptions ? ' id="' + oOptions.sId + '"': '') + '>' + oOptions.sText + '</span></a>');

	oButtonStripList.appendChild(oNewButton);
}

// Adds hover events to list items. Used for a versions of IE that don't support this by default.
var smf_addListItemHoverEvents = function()
{
	var cssRule, newSelector;

	// Add a rule for the list item hover event to every stylesheet.
	for (var iStyleSheet = 0; iStyleSheet < document.styleSheets.length; iStyleSheet ++)
		for (var iRule = 0; iRule < document.styleSheets[iStyleSheet].rules.length; iRule ++)
		{
			oCssRule = document.styleSheets[iStyleSheet].rules[iRule];
			if (oCssRule.selectorText.indexOf('LI:hover') != -1)
			{
				sNewSelector = oCssRule.selectorText.replace(/LI:hover/gi, 'LI.iehover');
				document.styleSheets[iStyleSheet].addRule(sNewSelector, oCssRule.style.cssText);
			}
		}

	// Now add handling for these hover events.
	var oListItems = document.getElementsByTagName('LI');
	for (oListItem in oListItems)
	{
		oListItems[oListItem].onmouseover = function() {
			this.className += ' iehover';
		};

		oListItems[oListItem].onmouseout = function() {
			this.className = this.className.replace(new RegExp(' iehover\\b'), '');
		};
	}
}

// Add hover events to list items if the browser requires it.
if (is_ie6down && 'attachEvent' in window)
	window.attachEvent('onload', smf_addListItemHoverEvents);
	
$(document).on("pagecontainertransition", function () {
	var section = location.hash ? location.hash : null;
	if (section != null) {
		var activePage = $.mobile.pageContainer.pagecontainer("getActivePage");
		$.mobile.defaultHomeScroll = activePage.find(section).offset().top;
	}
});
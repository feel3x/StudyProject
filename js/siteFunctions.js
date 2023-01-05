//an array of the URL histories within the panels
history = {};

/**
    Hides elements of the content. Used for a visual effect when navigating the app
    @param {Element} content - The element containing the children to disappear.
    */
function disappearElements(content) {
    var allElements = content.children;
    for (var i = 0; i < allElements.length; i++) {
        allElements[i].classList.add("disappearElements");
    }
}

/**
    Adds a loading spinner to the given content. Used for a visual effect when navigating the app
    @param {Element} content - The element to add the loading spinner to.
    @returns {Element} The added loading spinner element.
    */
function addLoader(content) {
    var addedDiv = document.createElement('div');
    $(addedDiv).hide(0);
    addedDiv.innerHTML = document.getElementById('loaderComponent').innerHTML;
    content.appendChild(addedDiv);
    $(addedDiv).fadeIn(1000);

    return addedDiv;

}

/**
    Pops up a message on the screen with the given title, text, and callback for the button.
    @param {string} title - The title of the message.
    @param {string} text - The text of the message.
    @param {function} buttonCallback - The callback function for the button.
    @param {boolean} isError - Whether the message is an error message or not.
    @returns {Element} The added message element.
    */
function popMessage(title, text, buttonCallback, isError) {
    var addedDiv = document.createElement('div');
    var color = isError ? "danger" : "success";
    addedDiv.className = "messageDiv btn btn-" + color;
    addedDiv.style.zIndex = 99999999;
    $(addedDiv).hide(0);
    addedDiv.innerHTML = document.getElementById('errorMessage').innerHTML;
    addedDiv.getElementsByClassName("errorTitle")[0].innerHTML = title;
    var content = addedDiv.getElementsByClassName("errorContent")[0];
    content.innerHTML = text;
    var button = addedDiv.getElementsByClassName("errorButton")[0];
    $(button).click(function () {
        buttonCallback();
        $(addedDiv).remove();
    });

    document.body.appendChild(addedDiv);
    $(addedDiv).fadeIn(300);

    return addedDiv;
}

/**
    Print the contents of a specific element. Usd to print Lists without the App UI

    @param {HTMLElement} element - The element to print the contents of.
    */
function printContent(element) {
    var panel = element.closest(".floatingPanel");
    var printSection = panel.getElementsByClassName("sectionToPrint")[0];
    var printContents = printSection.innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

/**
    Find the nearest panel element to a given element.
    @param {HTMLElement} element - The element to search from.
    @returns {HTMLElement} The nearest panel element.
    */
function getPanel(element) {
    return element.closest(".floatingPanel");
}

var prevContent = {};
/**
    Refresh the contents of a panel element.
    @param {string} panelId - The ID of the panel element to refresh.
    @param {object} post - The POST data to send with the refresh request.
    @param {boolean} showLoader - Whether to show a loading spinner while the panel is being refreshed.
    */
function refreshPanel(panelId, post, showLoader = false) {
    if (panelId != null) {
        console.log(panelId);
        var lastUrl = history[panelId][history[panelId].length - 1];
        if (lastUrl != undefined) {
            var content = document.getElementById(panelId).getElementsByClassName('panelContent')[0];

            var inputs = content.getElementsByTagName('input');
            var inputValues = {};

            for (var i = 0; i < inputs.length; i++) {

                inputValues[inputs[i].name] = inputs[i].value;
                if (inputs[i].type == "checkbox") {
                    if (inputs[i].checked) {
                        inputValues[inputs[i].name] = "checked";
                    }
                }
            }
            if (showLoader) {
                addLoader(content);
            }
            var headerTitle = document.getElementById(panelId).getElementsByClassName('panelTitle')[0];
            $(content).load(lastUrl, post, function (result) {
                result = $.trim(result.replace(/\s+/g, ' '));
                var newTitle = content.getElementsByClassName("title")[0];
                if (newTitle == null) {
                    newTitle = content.getElementsByTagName("title")[0].innerHTML;
                }
                else {
                    newTitle = newTitle.innerHTML;
                }
                headerTitle.innerHTML = newTitle;
                var inputs = content.getElementsByTagName('input');
                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].name != "") {
                        inputs[i].value = inputValues[inputs[i].name];
                        //remember the checked checkboxes to keep them after refreshing
                        if (inputs[i].type == "checkbox") {
                            inputs[i].checked = inputValues[inputs[i].name] == "checked" ? true : false;
                        }
                    }
                }
                newResult = result;
                if ((panelId in prevContent) && prevContent[panelId] != result) {
                    content.innerHTML = newResult;
                }
                prevContent[panelId] = result;

            });

        }
    }
}

/**
    Navigate to a new page.
    @param {HTMLElement} element - The element that was clicked to find the corresponding panel this element is inside of.
    @param {string} url - The URL to navigate to.
    @param {object} post - The POST data to send with the request.
    */
function goTo(element, url, post) {

    var panelId;
    if (element != null) {
        var content = element.closest('.panelContent');
        if (content != null) {
            var panel = content.closest('.floatingPanel');
            var headerTitle = panel.getElementsByClassName('panelTitle')[0];
            panelId = panel.id;
        }
        else {
            panelId = "root";
        }
    }
    else {
        panelId = "root";
    }
    url = hasQueryParams(url) ? url + "&panelId=" + panelId : url + "?panelId=" + panelId;
    if (!(panelId in history)) {
        history[panelId] = [];
    }
    history[panelId].push(url);

    if (panelId in prevContent) {
        delete prevContent[panelId];
    }

    if (content != null) {
        if (history[panelId].length > 1) {
            panel.getElementsByClassName('backButton')[0].style.display = "block";
        }
        disappearElements(content);
        setTimeout(function () { addLoader(content); }, 100);
        setTimeout(function () {
            $(content).load(url, post, function (result) {
                var newTitle = content.getElementsByClassName("title")[0];
                if (newTitle == null) {
                    newTitle = content.getElementsByTagName("title")[0].innerHTML;
                }
                else {
                    newTitle = newTitle.innerHTML;
                }
                headerTitle.innerHTML = newTitle;

                $(content).hide(0);
                $(content).fadeIn(1000);
            });
        }, 200);
    }
    else {
        var rootContent = document.getElementById('rootContent');
        if (history["root"].length > 1) {
            document.getElementById('back_button').style.display = "block";
        }
        disappearElements(rootContent);

        setTimeout(function () { addLoader(rootContent); }, 100);
        setTimeout(function () {
            $(rootContent).load(url, post, function (result) {
                var newTitle = rootContent.getElementsByClassName("title")[0];
                if (newTitle == null) {
                    newTitle = "";
                }
                else {
                    newTitle = newTitle.innerHTML;
                }
                document.getElementById('rootTitle').innerHTML = newTitle;

                $(rootContent).hide(0);
                $(rootContent).fadeIn(1000);
            });
        }, 200);
    }
}

/**
    Check if a URL has query parameters.
    @param {string} url - The URL to check.
    @returns {boolean} True if the URL has query parameters, false otherwise.
    */
function hasQueryParams(url) {
    return url.includes('?');
}

/**
    Navigate to the previous page in history of the root page or the panel.
    @param {string} element - The ID of the element to navigate. If not provided, the root element will be used.
    */
function goBack(element) {
    var elementId = element;
    if (element != null && element != undefined) {
        element = document.getElementById(element);
        var content = element.getElementsByClassName('panelContent')[0];
    }
    else {
        elementId = "root";
    }
    if (elementId in history) {
        if (history[elementId].length > 1) {
            urlArray = history[elementId].slice();
            goTo(content, urlArray[history[elementId].length - 2]);
            urlArray.pop();
            history[elementId] = urlArray;
        }
    }
}

//Toggle fullscreen on the first click on the page, as the app is preferably used in fullscreen. Don't do it again as to not annoy the user.
$(document).ready(function () {
    $("#fullscreen_button").on("click", function () {
        document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen && document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen()
    });
});
var firstClick = 1;
$(document).on("click", function () {
    if (firstClick > 0) {
        firstClick--;
        $("#fullscreen_button").click();
    }
});
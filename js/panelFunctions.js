history = {};

function disappearElements(content) {
    var allElements = content.children;
    for (var i = 0; i < allElements.length; i++) {
        allElements[i].classList.add("disappearElements");
    }
}

function addLoader(content) {
    var addedDiv = document.createElement('div');
    $(addedDiv).hide(0);
    addedDiv.innerHTML = document.getElementById('loaderComponent').innerHTML;
    content.appendChild(addedDiv);
    $(addedDiv).fadeIn(1000);

    return addedDiv;

}

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
function printContent(element) {
    var panel = element.closest(".floatingPanel");
    var printSection = panel.getElementsByClassName("sectionToPrint")[0];
    var printContents = printSection.innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

function getPanel(element) {
    return element.closest(".floatingPanel");
}

var prevContent = {};
var refreshingContent = {};
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
                if(inputs[i].type == "checkbox")
                {
                    if(inputs[i].checked)
                    {
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
                        console.log(inputs[i].name + " : " + inputValues[inputs[i].name]);
                        inputs[i].value = inputValues[inputs[i].name];
                        if(inputs[i].type == "checkbox")
                        {
                            inputs[i].checked = inputValues[inputs[i].name] == "checked"?true:false;
                        }
                    }
                }
                newResult = result;
                if ((panelId in prevContent) && prevContent[panelId] != result) {
                    var dmp = new diff_match_patch();
                    var diff = dmp.diff_main(prevContent[panelId], result);
                    newResult = diff[0][1];
                    closeTagNeeded = false;
                    for (var i = 1; i < diff.length; i++) {
                        if (closeTagNeeded) {
                            var inOf = diff[i][1].indexOf("<");
                            if (inOf > -1) {
                                diff[i][1] = diff[i][1].slice(0, inOf) + "</span>" + diff[i][1].slice(inOf);
                            }
                            closeTagNeeded = false;
                        }
                        if (diff[i][0] == 0) {
                            newResult = newResult + diff[i][1];
                        }
                        else if (diff[i][0] == 1) {
                            var insertIndex = -1;
                            for (var j = newResult.length; j > 0; j--) {
                                if (newResult.charAt(j) == ">") {
                                    insertIndex = j;
                                    break;
                                }
                            }
                            if (insertIndex != -1) {
                                closeTagNeeded = true;
                                newResult = newResult.slice(0, (j + 1)) + "<span class='highlightedElement'>" + newResult.slice((j + 1)) + diff[i][1];
                            }
                        }
                    }
                    content.innerHTML = newResult;
                }
                prevContent[panelId] = result;

            });

        }
    }
    if (panelId in refreshingContent) {
        delete refreshingContent[panelId];
    }
}

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
    url = hasQueryParams(url)?url+"&panelId="+ panelId:url+"?panelId="+panelId;
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
        setTimeout(function() {addLoader(content); }, 100);
        setTimeout(function() {$(content).load(url, post, function (result) {
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
        });}, 200);
    }
    else {
        var rootContent = document.getElementById('rootContent');
        if (history["root"].length > 1) {
            document.getElementById('back_button').style.display = "block";
        }
        disappearElements(rootContent);

        setTimeout(function() { addLoader(rootContent);},100);
        setTimeout(function() {$(rootContent).load(url, post, function (result) {
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
        });},200);
    }
}


function hasQueryParams(url) {
return url.includes('?');
}

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


$(document).ready(function () {
    $("#fullscreen_button").on("click", function () {
        document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen && document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen()
    });
});
var firstClick = 1;
$(document).on("click", function () {
    if(firstClick>0)
    {
        firstClick--;
        $("#fullscreen_button").click(); 
    }
});
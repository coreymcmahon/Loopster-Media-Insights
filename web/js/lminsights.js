
/**
 *
 */
function successGetFanCount(data) {
  alert("The page currently has " + data["likes"] + " fans.");
}

/**
 *
 */
function getFanCount(url,id) {
  var endpoint = "https://graph.facebook.com/";

  $("#row-id-" + id + " .icon-column img")[0].setAttribute("src","/images/loading.gif");
  
  // Break up the URL and get the parts we want from it
  var endIndex = url.indexOf("?");
  if (endIndex > 0)
    url = url.substr(0,url.indexOf("?"));
  url = url.substr(url.lastIndexOf("/") + 1,url.length - url.lastIndexOf("/") + 1);

  // Create a function to pass to the JSON method as the success handler
  var tmp = function () {
      return function (data) {
          $("#row-id-" + id + " .icon-column img")[0].setAttribute("src","/images/FacebookIcon.gif");
          successGetFanCount(data);
      }
  } (id);

  // Perform the AJAX request
  $.getJSON(endpoint + url + "&callback=?", tmp);
}
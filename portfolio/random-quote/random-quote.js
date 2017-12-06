//Ensures each quote is random (solution found here: https://forum.freecodecamp.org/t/not-getting-random-quotes-from-quotes-on-design/112123)
$(document).ready(function() {
  $.ajaxSetup({
    cache: false
  });
});

//Initialise variable to allow me to use quote outside of newQuote function
var quoteText = "";
//function that will return a random quote from https://quotesondesign.com/api-v4-0/
var newQuote = function() {
 $('.quote').empty(); //removes previous quote
  $.getJSON("https://quotesondesign.com/wp-json/posts?filter[orderby]=rand&filter[posts_per_page]=1", function(a) {
    $(".quote").append("<h3>" + a[0].content + "</h3><p class='author'>&#45; " + a[0].title + "</p>");
    quoteText = $(".quote").text();
  });
};

var tweetQuote = function() {
  if (quoteText.length < 140) {
    window.open("https://twitter.com/intent/tweet?text=" + quoteText);
    } else {
      window.open("https://twitter.com/intent/tweet?text=" + quoteText.slice(0, 136) + "...");
    }
  }

/* Function below was attempting to load a new background each time a new quote was requested *
* Need to research XMLHttpRequest to get this to work *
var newImage = function() {
  $('#background').css('background-image', 'none');
  $('#background').css('background-image', 'url("https://source.unsplash.com/random/1920×1440")');
};
*/

//Ensures quote is on page at load
$(document).ready(newQuote);
//Displays new quote when
$(document).on('click', "#getQuote", newQuote);
           //.on('click', "#getQuote", newImage);
//Tweet quote when clicked
$(document).on('click', "#tweetQuote", tweetQuote);

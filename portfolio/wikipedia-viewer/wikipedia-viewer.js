$(document).ready(function() {
  //runs a search when search button is pressed
  $('#wiki-submit-button').click(function() {
    $("#wiki-results").empty(); //empty page of results for subsequent searches
    getResults($('#wiki-input').val());
  });
  //runs a search when enter key is pressed while form is active
  $("#wiki-input").keypress(function(event) {
    if (event.which == 13) {
      $("#wiki-results").empty(); //empty page of results for subsequent searches
      getResults($('#wiki-input').val());
     }
  });

  //Prevents page reloading upon clicking submit button
  $("#wiki-search").submit(function(e) {
    e.preventDefault();
  });
});

function getResults(search) {
  //alert(search);
  var apiLink = "https://en.wikipedia.org/w/api.php?action=opensearch&search="+search+"&limit=3&prop=revisions&rvprop=content&format=json&callback=?";
  $.getJSON(apiLink, function(searchResults){
   for (var i = 0; i < searchResults[2].length; i++) {
     $("#wiki-results").append("<a href='"+searchResults[3][i]+"' target='_blank'>"+
                            "<div class='wiki-result-item'>"+
                            "<h3>"+searchResults[1][i]+"</h3>"+
                            "<p>"+searchResults[2][i]+"</p>");
   }
  });
  //alert(apiLink);
}

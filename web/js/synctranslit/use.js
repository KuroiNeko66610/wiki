$(document).ready(function(){
 $("#tag-name").syncTranslit({
  destination: "tag-slug",
  caseStyle: "lower",
  urlSeparator: "-"
 });
});
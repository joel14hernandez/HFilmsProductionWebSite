
$(document).ready(function(){
  $(".menu").click(function(){
    $("#mobileNav").hide();
  });
});
var x=0;
$(document).ready(function(){
    var images=["photos/baby1.jpg","photos/baby2.jpg","photos/baby3.jpg","photos/baby4.jpg","photos/baby5.jpg","photos/baby6.jpg","photos/baby8.jpg"]
    x=Math.floor((Math.random() * 7));
    var img=images[x]
    $("#bodyHome").prepend("<img src="+img+">");
})
 
$('.photo').magnificPopup({
    delegate:'a',
  type: 'image',
  // other option
    image: {
      verticalFit: true,
      horizontalFit:true
  }
});

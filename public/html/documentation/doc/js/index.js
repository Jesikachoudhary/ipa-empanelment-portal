$('#leftsidebar .list li').click(function(){
  var $this = $(this);
  if(!$this.is('active open'))
  {
    $('#leftsidebar .list li').removeClass('active open').removeData("top");
    $this.addClass('active open').data("top", $this.offset().top);
  }
});


// ThemeMakker website live chat js 
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/59f5afbbbb0c3f433d4c5c4c/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
})();
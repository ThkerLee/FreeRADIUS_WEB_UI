$(document).ready(function() {
	$("#getIPdiv").click(function(){
			 var  groupname = document.getElementById('GroupNameSelect').value ;
			 $.ajax({
				url: "ajax/getip.php?groupname="+encodeURI(groupname),
				type: 'POST',
				dataType: 'text', // html
				timeout: 3000,
				data:"?rnd=" + Math.random,
				error: function(){
					 //alert("ok");
				},
				success: function(data){ 
					try
					{ 
						$('#ipaddress').attr('value',data);
						
					}
					catch (ex)
					{
						alert(ex.message);
					}
				}
			});  
	});	
});

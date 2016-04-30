window.fbAsyncInit = function() {
	FB.init({
	    appId      : '138411683228452',
	    cookie     : true,  // enable cookies to allow the server to access 
	                        // the session
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.2' // use version 2.2
	});
};
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/zh_HK/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function login(){
	$.ajax({
	  method: "POST",
	  url: "storetoken.php",
	  success:function(){
	  	location.reload();
	  }
	});
}
function addplayer(){
	$(".membercontainer").append('<div class="member2"><div class="memberpic">'+
		'<img src="noperson.png" style="width:100px;height:140px;"><div>點選更換照片<br>建議使用一吋照片</div></div>'+
		'<form class="ui form textinput"><div class="field"><label>姓名</label><input type="text"></div>'+
		'<div class="field"><label>學號</label><input type="text"></div><div class="field"><label>系級</label>'+
		'<input type="text"></div></form><form class="ui form starinput"><div class="inline field">'+
		'<div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden"><label>是否參加明星賽</label>'+
		'</div></div><div class="field" style="opacity: 0;"><label>明星賽守備位置</label><select class="ui dropdown disabled">'+
		'<option value="0">內野</option><option value="1">外野</option><option value="2">投手</option></select>'+
		'</div><div class="inline field"><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden">'+
		'<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label></div></div><div class="field" style="opacity: 0;">'+
		'<label>明日之星賽守備位置</label><select class="ui dropdown disabled"><option value="0">投手</option>'+
		'<option value="1">一壘手</option><option value="2">二壘手</option><option value="3">三壘手</option>'+
		'<option value="4">游擊手</option><option value="5">捕手</option><option value="6">外野手</option>'+
		'</select></div></form><i class="big remove icon"></i><input type="file" accept="image/*" style="display:none;"></div>');
	var newplayer = $(".membercontainer").children()[$(".membercontainer").children().length-1];
	$(newplayer).find(".checkbox").checkbox();
	$(newplayer).find(".dropdown").dropdown();
	$(newplayer).children("input")[0].onchange = function(){
		var reader  = new FileReader();
		reader.addEventListener("load", function () {
			$(newplayer).find(".memberpic img").attr("src",reader.result);
		}, false);
		reader.readAsDataURL($(newplayer).children("input")[0].files[0]);
	}
	$(newplayer).find(".memberpic img").click(function(){
		$(newplayer).children("input").click();
	});
	$($(newplayer).find(".checkbox")[0]).click(function(){
		if($(this).checkbox('is checked')){
			$($(newplayer).find(".ui.dropdown")[0]).removeClass("disabled");
			$($(newplayer).find(".starinput .field")[1]).css("opacity",1);
		}else{
			$($(newplayer).find(".ui.dropdown")[0]).addClass("disabled");
			$($(newplayer).find(".starinput .field")[1]).css("opacity",0);
		}
	});
	$($(newplayer).find(".checkbox")[1]).click(function(){
		if($(this).checkbox('is checked')){
			$($(newplayer).find(".ui.dropdown")[1]).removeClass("disabled");
			$($(newplayer).find(".starinput .field")[3]).css("opacity",1);
		}else{
			$($(newplayer).find(".ui.dropdown")[1]).addClass("disabled");
			$($(newplayer).find(".starinput .field")[3]).css("opacity",0);
		}
	});
	$(newplayer).mouseenter(function(){
		$(this).children("i.remove").animate({
			opacity:1
		},400);
	}).mouseleave(function(){
		$(this).children("i.remove").animate({
			opacity:0
		},400);
	});
	$(newplayer).children("i.remove").click(function(){
		$("#dialog-confirm").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+
			'是否要將該球員刪除?(若刪除將無法復原)'+
			'</p>');
		$( "#dialog-confirm" ).dialog({
	      resizable: false,
	      height:205,
	      modal: true,
	      buttons: {
	        "確定": function() {
	          $( this ).dialog( "close" );
		          	if($(newplay).data("id") == null){
		          		$(newplayer).animate({
							opacity:0
						},600,function(){
							$(newplayer).remove();
						});
		          	}else{
		          		$.ajax({
		                    url : "deletemember.php",
		                    type : "POST",
		                    data : {id:$(newplay).data("id")},
		                    success : function(data) {
		                        if(data == "success"){
		                            $(newplayer).animate({
										opacity:0
									},600,function(){
										$(newplayer).remove();
									});
		                        }
		                    },
		                    error : function(xhr,errmsg,err) {
		                        console.log(xhr.status + ": " + xhr.responseText);
		                    } 
		                });
		          	}
	        },
	        "取消": function() {
	          $( this ).dialog( "close" );
	        }
	      }
	    });
	});
}
function checkleader(data){
	data.leader = {};
	if($(".member .textinput input")[0].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">隊長姓名不可為空白</p>');
        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
        return false;
	}
	data.leader.name = $(".member .textinput input")[0].value;
	if($(".member .textinput input")[1].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">隊長學號不可為空白</p>');
        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
        return false;
	}
	data.leader.number = $(".member .textinput input")[1].value;
	if($(".member .textinput input")[2].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">隊長系級不可為空白</p>');
        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
        return false;
	}
	data.leader.departlevel = $(".member .textinput input")[2].value;
	if($($(".member .starinput .checkbox")[0]).checkbox("is checked")){
		data.leader.field1 = $(".member .starinput .ui.dropdown").dropdown('get value')[0];
	}else{
		data.leader.field1 = null;
	}
	if($($(".member .starinput .checkbox")[1]).checkbox("is checked")){
		data.leader.field2 = $(".member .starinput .ui.dropdown").dropdown('get value')[1];
	}else{
		data.leader.field2 = null;
	}
	data.leader.participate1 = $($(".member .starinput .checkbox")[0]).checkbox("is checked");
	data.leader.participate2 = $($(".member .starinput .checkbox")[1]).checkbox("is checked");
	data.contact = {};
	if(!$(".member .contact .checkbox").checkbox('is checked')){
		if($(".member .contact input:text")[0].value == ""){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">領隊姓名不可為空白</p>');
	        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	        return false;
		}
		if($(".member .contact input:text")[1].value == ""){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">領隊系級不可為空白</p>');
	        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	        return false;
		}
		data.contact.name = $(".member .contact input:text")[0].value;
		data.contact.departlevel = $(".member .contact input:text")[1].value;
	}else{
		data.contact.name = null;
		data.contact.departlevel = null;
	}
	data.contact.isleader = $(".member .contact .checkbox").checkbox('is checked');
	if($(".member .contact input:text")[2].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">FB帳號不可為空白</p>');
	    $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	    return false;
	}
	if($(".member .contact input:text")[3].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">手機號碼不可為空白</p>');
	    $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	    return false;
	}
	if($(".member .contact input:text")[4].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">電子郵件不可為空白</p>');
	    $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	    return false;
	}
	data.contact.fb = $(".member .contact input:text")[2].value;
	data.contact.mobile = $(".member .contact input:text")[3].value;
	data.contact.email = $(".member .contact input:text")[4].value;
	if($($(".member input:file")[0]).data("exist") == null){
		if($(".member input:file")[0].files.length == 0){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">請上傳隊長大頭照</p>');
		    $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
		    return false;
		}
		data.leaderpic = $(".member input:file")[0].files[0];
	}else{
		if($(".member input:file")[0].files.length == 0){
			data.leaderpic = 0;
		}else{
			data.leaderpic = $(".member input:file")[0].files[0];
		}
	}
	return true;
}
function checkmember(data){
	for(var i = 0;i < $(".member2").length;i++){
		var current = $(".member2")[i];
		var currentdata = {};
		data.leader.name = $(".member .textinput input")[0].value;
		if($(current).find(".textinput input")[0].value == ""){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">隊員'+(i+1)+'姓名不可為空白</p>');
	        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	        return false;
		}
		currentdata.name = $(current).find(".textinput input")[0].value;
		if($(current).find(".textinput input")[1].value == ""){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">隊員'+(i+1)+'學號不可為空白</p>');
	        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	        return false;
		}
		currentdata.number = $(current).find(".textinput input")[1].value;
		if($(current).find(".textinput input")[2].value == ""){
			$("#dialog").html('<p style="text-align:center;font-size:18px;">隊員'+(i+1)+'系級不可為空白</p>');
	        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
	        return false;
		}
		currentdata.departlevel = $(current).find(".textinput input")[2].value;
		if($($(current).find(".starinput .checkbox")[0]).checkbox("is checked")){
			currentdata.field1 = $(current).find(".starinput .ui.dropdown").dropdown('get value')[0];
		}else{
			currentdata.field1 = null;
		}
		if($($(current).find(".starinput .checkbox")[1]).checkbox("is checked")){
			currentdata.field2 = $(current).find(".starinput .ui.dropdown").dropdown('get value')[1];
		}else{
			currentdata.field2 = null;
		}
		currentdata.participate1 = $($(current).find(".starinput .checkbox")[0]).checkbox("is checked");
		currentdata.participate2 = $($(current).find(".starinput .checkbox")[1]).checkbox("is checked");
		if($(current).data("id") == null){
			currentdata.id = "null";
		}else{
			currentdata.id = $(current).data("id");
		}
		if($($(current).find("input:file")[0]).data("exist") == null){
			if($(current).find("input:file")[0].files.length == 0){
				$("#dialog").html('<p style="text-align:center;font-size:18px;">請上傳隊員'+(i+1)+'大頭照</p>');
			    $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
			    return false;
			}
			data.memberpic.push($(current).find("input:file")[0].files[0]);
		}else{
			if($(current).find("input:file")[0].files.length == 0){
				data.memberpic.push(0);
			}else{
				data.memberpic.push($(current).find("input:file")[0].files[0]);
			}
		}
		data.member.push(currentdata);
	}
	return true;
}
function saveall(){
	var button = $(this);
	$(this).html('<div class="ui active mini inline loader"></div>');
	$(this).unbind();
	var data = {};
	if($(".teamform input")[0].value == ""){
		$("#dialog").html('<p style="text-align:center;font-size:18px;">隊伍系所不可為空白</p>');
        $( "#dialog" ).dialog({modal: true,buttons: {"確認": function() {$( this ).dialog( "close" ); }}});
        $(this).html("送出");
        $(this).click(saveall);
	}else{
		if($(".teamform").data("id") == null){
			data.teamid = "null";
		}else{
			data.teamid = $(".teamform").data("id");
		}
		data.teamname = $(".teamform input")[0].value;
		data.member = [];
		data.memberpic = [];
		if(checkleader(data) && checkmember(data)){
        	var request = new XMLHttpRequest();
        	var formData = new FormData();
        	formData.append("fbid",$(".content").data("id"));
        	formData.append("teamid",data.teamid);
        	formData.append("teamname",data.teamname);
        	formData.append("leader",JSON.stringify(data.leader));
        	formData.append("leaderpic",data.leaderpic);
        	formData.append("contact",JSON.stringify(data.contact));
        	var membernum = data.member.length;
        	formData.append("memberamount",membernum);
        	for(var i = 0;i < membernum;i++){
        		formData.append("member"+(i+1),JSON.stringify(data.member[i]));
        		formData.append("memberpic"+(i+1),data.memberpic[i]);
        	}
        	request.onreadystatechange = function() {
	            if (request.readyState == 4) {
	                if(request.status == 200){
	                	console.log(request.responseText);
	                    var data = jQuery.parseJSON(request.responseText);
	                    console.log(data);
	                    if(data.status == "success"){
	                    	$(".teamform").data("id",data.teamid);
	                    	for(var i = 0;i < data.member.length;i++){
	                    		$($(".member2")[i]).data("id",data.member[i]);
	                    	}
		                    button.html("完成");
		                    button.mouseenter(function(){
		                    	button.html("存檔");
		                    }).mouseleave(function(){
		                    	button.html("完成");
		                    });
	        				button.click(saveall);
	        			}
	                }else{
	                    console.log("Error", request.statusText);  
	                }
	            }
	        };
	        request.open('post','submitteam.php');
       		request.send(formData);
		}else{
			$(this).html("送出");
        	$(this).click(saveall);
		}
	}
}
function activateleader(){
	$(".member").children("input")[0].onchange = function(){
		var reader  = new FileReader();
		reader.addEventListener("load", function () {
			$(".member").find(".memberpic img").attr("src",reader.result);
		}, false);
		reader.readAsDataURL($(".member").children("input")[0].files[0]);
	}
	$(".member .memberpic img").click(function(){
		$(".member").children("input").click();
	});
	$($(".member").find(".checkbox")[0]).click(function(){
		if($(this).checkbox('is checked')){
			$($(".member").find(".ui.dropdown")[0]).removeClass("disabled");
			$($(".member").find(".starinput .field")[1]).css("opacity",1);
		}else{
			$($(".member").find(".ui.dropdown")[0]).addClass("disabled");
			$($(".member").find(".starinput .field")[1]).css("opacity",0);
		}
	});
	$($(".member").find(".checkbox")[1]).click(function(){
		if($(this).checkbox('is checked')){
			$($(".member").find(".ui.dropdown")[1]).removeClass("disabled");
			$($(".member").find(".starinput .field")[3]).css("opacity",1);
		}else{
			$($(".member").find(".ui.dropdown")[1]).addClass("disabled");
			$($(".member").find(".starinput .field")[3]).css("opacity",0);
		}
	});
	$(".member").find(".contact .checkbox").click(function(){
		if($(this).checkbox('is checked')){
			$($(".member .contact .field")[1]).css("display","none");
			$($(".member .contact .field")[2]).css("display","none");
		}else{
			$($(".member .contact .field")[1]).css("display","block");
			$($(".member .contact .field")[2]).css("display","block");
		}
	});
}
function activatemember(){
	$(".member2").each(function(k,newplayer){
		$(newplayer).find(".checkbox").checkbox();
		$(newplayer).find(".dropdown").dropdown();
		$(newplayer).children("input")[0].onchange = function(){
			var reader  = new FileReader();
			reader.addEventListener("load", function () {
				$(newplayer).find(".memberpic img").attr("src",reader.result);
			}, false);
			reader.readAsDataURL($(newplayer).children("input")[0].files[0]);
		}
		$(newplayer).find(".memberpic img").click(function(){
			$(newplayer).children("input").click();
		});
		$($(newplayer).find(".checkbox")[0]).click(function(){
			if($(this).checkbox('is checked')){
				$($(newplayer).find(".ui.dropdown")[0]).removeClass("disabled");
				$($(newplayer).find(".starinput .field")[1]).css("opacity",1);
			}else{
				$($(newplayer).find(".ui.dropdown")[0]).addClass("disabled");
				$($(newplayer).find(".starinput .field")[1]).css("opacity",0);
			}
		});
		$($(newplayer).find(".checkbox")[1]).click(function(){
			if($(this).checkbox('is checked')){
				$($(newplayer).find(".ui.dropdown")[1]).removeClass("disabled");
				$($(newplayer).find(".starinput .field")[3]).css("opacity",1);
			}else{
				$($(newplayer).find(".ui.dropdown")[1]).addClass("disabled");
				$($(newplayer).find(".starinput .field")[3]).css("opacity",0);
			}
		});
		$(newplayer).mouseenter(function(){
			$(this).children("i.remove").animate({
				opacity:1
			},400);
		}).mouseleave(function(){
			$(this).children("i.remove").animate({
				opacity:0
			},400);
		});
		$(newplayer).children("i.remove").click(function(){
			$("#dialog-confirm").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+
				'是否要將該球員刪除?(若刪除將無法復原)'+
				'</p>');
			$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height:205,
		      modal: true,
		      buttons: {
		        "確定": function() {
		          	$( this ).dialog( "close" );
		          	if($(newplay).data("id") == null){
		          		$(newplayer).animate({
							opacity:0
						},600,function(){
							$(newplayer).remove();
						});
		          	}else{
		          		$.ajax({
		                    url : "deletemember.php",
		                    type : "POST",
		                    data : {id:$(newplay).data("id")},
		                    success : function(data) {
		                        if(data == "success"){
		                            $(newplayer).animate({
										opacity:0
									},600,function(){
										$(newplayer).remove();
									});
		                        }
		                    },
		                    error : function(xhr,errmsg,err) {
		                        console.log(xhr.status + ": " + xhr.responseText);
		                    } 
		                });
		          	}
		        },
		        "取消": function() {
		          $( this ).dialog( "close" );
		        }
		      }
		    });
		});
	});
}
$(document).ready(function(){
	$(".checkbox").checkbox();
	$(".dropdown").dropdown();
	if($("#registerbutton").length == 0){
		activateleader();
		activatemember();
		$("#addplayerbutton").click(addplayer);
		$(".savebutton").click(saveall);
	}
	$("#registerbutton").click(function(){
		var parent = $(this).parents('.content');
		parent.html('<div class="subtitle">隊伍資訊</div><form class="ui form teamform">'+
		'<div class="inline field"><label>系所名</label><input type="text"></div></form>'+
		'<div class="leader"><div class="leadertitle">隊長、領隊(領隊負責所有聯絡事項)</div><div class="member"><div class="memberpic">'+
		'<img src="noperson.png" style="width:100px;height:140px;"><div>點選更換照片<br>建議使用一吋照片</div>'+
		'</div><form class="ui form textinput"><div class="field"><label>姓名</label><input type="text">'+
		'</div><div class="field"><label>學號</label><input type="text"></div><div class="field">'+
		'<label>系級</label><input type="text"></div></form><form class="ui form starinput">'+
		'<div class="inline field"><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden">'+
		'<label>是否參加明星賽</label></div></div><div class="field" style="opacity: 0;">'+
		'<label>明星賽守備位置</label><select class="ui dropdown disabled"><option value="0">內野</option>'+
		'<option value="1">外野</option><option value="2">投手</option></select></div>'+
		'<div class="inline field"><div class="ui checkbox"><input type="checkbox" tabindex="0" class="hidden">'+
		'<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label></div></div><div class="field" style="opacity: 0;">'+
		'<label>明日之星賽守備位置</label><select class="ui dropdown disabled"><option value="0">投手</option>'+
		'<option value="1">一壘手</option><option value="2">二壘手</option><option value="3">三壘手</option>'+
		'<option value="4">游擊手</option><option value="5">捕手</option><option value="6">外野手</option>'+
		'</select></div></form><form class="ui form contact"><div class="inline field"><div class="ui checkbox">'+
		'<input type="checkbox" tabindex="0" class="hidden" checked><label>隊長是否兼職領隊</label></div>'+
		'</div><div class="field" style="display:none;"><label>領隊姓名</label><input type="text">'+
		'</div><div class="field" style="display:none;"><label>領隊系級</label><input type="text"></div>'+
		'<div class="field"><label>FB帳號</label><input type="text"></div><div class="field"><label>手機號碼</label>'+
		'<input type="text"></div><div class="field"><label>電子郵件</label><input type="text"></div>'+
		'</form><input type="file" accept="image/*" style="display:none;"></div></div><div class="subtitle">隊員</div><div class="membercontainer"></div>'+
		'<div class="buttonwrapper"><img id="addplayerbutton" src="addplayer.png"/><div class="savebutton">'+
		'送出</div></div>');
		parent.find(".checkbox").checkbox();
		parent.find(".dropdown").dropdown();
		activateleader();
		$("#addplayerbutton").click(addplayer);
		$(".savebutton").click(saveall);
	});
});
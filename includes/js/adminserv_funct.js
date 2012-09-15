/**
* Fonctions générales
*/
function getIncludesPath(){
	return $.trim( $("#path_includes").text() );
}
function getRessourcesPath(){
	return $.trim( $("#path_ressources").text() );
}
function getPath(){
	return $.trim( $(".path").text() );
}
function getMode(){
	return $.trim( $("#detailMode").data("statusmode") );
}
function getCurrentSort(){
	return $("#currentSort").val();
}
function setCurrentSort(sort){
	return $("#currentSort").val(sort);
}
function t(text){
	text = text.replace('from', $("#formUpload").data("from") );
	text = text.replace('Kb', $("#formUpload").data("kb") );
	text = text.replace('Mb', $("#formUpload").data("mb") );
	return text;
}
function scrollTop(){
	$("html, body").animate({  
		scrollTop: 0
	}, "slow");
}
function scrollBottom(){
	$("html, body").animate({  
		scrollTop: $(document).height() - $(window).height()
	}, "slow");
}
function getUrlParams(key){
	var params = window.location.search.substring(1);
	if(!key){
		return params;
	}
	else{
		var index = params.indexOf(key);
		if(index !== -1){
			return params.substring(index).split("&")[0].substring(2);
		}
	}
}


/**
* Affiche la valeur seconde -> minute
*
* @param int sec -> La valeur en seconde
*/
function secToMin(sec){
	if(sec == "" || sec == undefined || isNaN(sec) ){
		sec = 0;
	}
	return round( (parseInt(sec) / 60), 1);
}


/**
* Math.round avec précision
*
* @param int value     -> Valeur à arrondir
* @param int precision -> Nombre de caractère après la virgule
*/
function round(value, precision){
	power = Math.pow(10, precision);
	return (Math.ceil(value * power)) / power;
}


/**
* Affichage du texte d'erreur
*/
function error(text, hide){
	$("#error").fadeIn("fast");
	if( $("#error").attr("hidden") ){
		$("#error").removeAttr("hidden");
	}
	$("#error").html(text);
	
	if(hide){
		setTimeout(function(){
			$("#error").attr("hidden", true);
			$("#error").fadeOut("fast");
		}, 4000);
	}
}


/**
* Affichage du texte d'erreur
*/
function info(text, hide){
	$("#info").fadeIn("fast");
	if( $("#info").attr("hidden") ){
		$("#info").removeAttr("hidden");
	}
	$("#info").text(text);
	
	if(hide){
		setTimeout(function(){
			$("#info").attr("hidden", true);
			$("#info").fadeOut("fast");
		}, 4000);
	}
}


/**
* Administration rapide (restart, next, endround)
*
* @param string cmd -> Le nom de la méthode à utiliser
*/
function speedAdmin(cmd){
	$.post(getIncludesPath()+"ajax/speed_admin.php", {cmd: cmd}, function(response){
		if(response != "true"){
			error("Error: "+response);
		}
		setTimeout(function(){
			var sort = getCurrentSort();
			if( $("body").hasClass("section-index") ){
				getCurrentServerInfo("", sort);
			}
			else if( $("body").hasClass("section-maps-list") ){
				getMapList("", sort);
			}
			$(".speed-admin a.locked").removeClass("locked");
		}, 2000);
	});
}


/**
* Récupère la liste des niveaux admin suivant le serveur sélectionné
*/
function getServerAdminLevel(){
	var serverName = $("select#as_server").val();
	var adminLevelList = "";
	
	$.getJSON(getIncludesPath()+"ajax/get_server_adminlevel.php", {srv: serverName}, function(response){
		if(response != null){
			$.each(response.levels, function(i, n){
				if(response.last != null && response.last == n){ var selected = ' selected="selected"'; }
				else{ var selected = ""; }
				adminLevelList += '<option value="'+n+'"'+selected+'>'+n+'</option>';
			});
			
			// On met à jour la liste
			$("select#as_adminlevel").html(adminLevelList);
		}
		else{
			error("Aucun niveau admin configuré pour ce serveur.");
		}
	});
}


/**
* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
*/
function getCurrentServerInfo(mode, sort){
	var path_ressources = getRessourcesPath();
	if(!mode){
		mode = getMode();
	}
	if(sort){
		setCurrentSort(sort);
	}
	var isTeamGameMode = $("#isTeamGameMode").val();
	
	$.getJSON(getIncludesPath()+"ajax/get_current_serverinfo.php", {mode: mode, sort: sort}, function(data){
		if(data != null){
			// Map
			if(data.map != null){
				$("#map_name").html(data.map.name);
				$("#map_author").html(data.map.author);
				$("#map_enviro").html(data.map.enviro+'<img src="'+path_ressources+'images/env/'+data.map.enviro.toLowerCase()+'.png" alt="" />');
				$("#map_uid").html(data.map.uid);
				if(data.srv.gameModeScriptName != 'undefined'){
					var gameModeName = data.srv.gameModeName+' <span class="scriptName">('+data.srv.gameModeScriptName+')</span>';
				}else{
					var gameModeName = data.srv.gameModeName;
				}
				$("#map_gamemode").html(gameModeName).attr("class", "").addClass("value "+data.srv.gameModeName.toLowerCase() );
				if(data.map.thumb){
					$("#map_thumbnail").html('<img src="data:image/jpeg;base64,'+data.map.thumb+'" alt="'+$("#map_thumbnail").data("text-thumbnail")+'" />');
				}
				if(data.map.scores){
					$("#ScoreTeamBlue").val(data.map.scores.blue);
					$("#ScoreTeamRed").val(data.map.scores.red);
				}
			}
			
			// Server
			if(data.srv != null){
				$("#server_name").html(data.srv.name);
				$("#server_status").html(data.srv.status);
			}
			
			// Stats
			if(data.net != null){
				$("#network_uptime").html(data.net.uptime);
				$("#network_nbrconnection").html(data.net.nbrconnection);
				$("#network_meanconnectiontime").html(data.net.meanconnectiontime);
				$("#network_meannbrplayer").html(data.net.meannbrplayer);
				$("#network_recvnetrate").html(data.net.recvnetrate);
				$("#network_sendnetrate").html(data.net.sendnetrate);
				$("#network_totalreceivingsize").html(data.net.totalreceivingsize);
				$("#network_totalsendingsize").html(data.net.totalsendingsize);
			}
			
			// Players
			if(data.ply != null && !$("#playerlist").isChecked() ){
				var out = "";
				
				// Création du tableau
				out += '<tr class="table-separation"><td colspan="'; if(isTeamGameMode){ out += '6'; }else{ out += '5'; } out += '"></td></tr>';
				if( typeof(data.ply) == "object" && data.ply.length > 0 ){
					$.each(data.ply, function(i, player){
						out += '<tr class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } out += '">';
							if(isTeamGameMode && mode == "detail"){
								out += '<td class="detailModeTd imgleft"><span class="team_'+player.TeamId+'" title="'+player.TeamName+'"></span>'+player.TeamName+'</td>';
							}
							out += '<td class="imgleft"><img src="'+path_ressources+'images/16/solo.png" alt="" />'+player.NickName+'</td>';
							if( !isTeamGameMode && mode == "detail" ){
								out += '<td class="imgleft"><img src="'+path_ressources+'images/16/leagueladder.png" alt="" />'+player.LadderRanking+'</td>';
							}
							out += '<td>'+player.Login+'</td>'
							+'<td>'+player.PlayerStatus+'</td>'
							+'<td class="checkbox"><input type="checkbox" name="player[]" value="'+player.Login+'" /></td>'
						+'</tr>';
					});
					
					if( $("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", false);
					}
				}
				else{
					if( !$("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", true);
					}
					out += '<tr class="no-line"><td class="center" colspan="'; if(isTeamGameMode){ out += '6'; }else{ out += '5'; } out += '">'+data.ply+'</td></tr>';
				}
				
				// HTML
				$("#playerlist table tbody").html(out);
				if( $("#playerlist").hasClass("loading") ){
					$("#playerlist").removeClass("loading");
				}
			}
		}
	});
}


/**
* Récupère et affiche le nom du serveur et son commentaire
*
* @param string str  -> La chaine de caractère à transformer en HTML
* @param string dest -> Selecteur Jquery pour afficher les données
*/
function getPreviewSrvOpts(str, dest){
	$.getJSON(getIncludesPath()+"ajax/preview_srvopts.php", {t: str}, function(data){
		if(data != null){
			$(dest).html('['+data.str+']');
		}
	});
}


/**
* Récupère la configuration du gameMode sélectionné
*/
function getCurrentGameModeConfig(){
	var gameMode = $("select#NextGameMode option:selected").text();
	var selector = $("#gameMode-"+gameMode.toLowerCase() );
	
	// Fermeture de tous les modes par défaut
	if( $("body").hasClass("section-maps-creatematchset") ){
		var section = ".section-maps-creatematchset";
	}else{
		var section = ".section-gameinfos";
	}
	$.each( $(section+" .content.gameinfos fieldset"), function(i, n){
		if( !$(this).hasClass("gameinfos_general") && !$(this).hasClass("gameinfos_teaminfos") ){
			if( $(this).attr("hidden") ){
				$(this).hide();
			}
			else{
				$(this).hide();
				$(this).attr("hidden", true);
			}
		}
	});
	
	// Affichage du mode de jeu sélectionné
	if( selector.attr("hidden") ){
		selector.slideDown("fast");
		selector.removeAttr("hidden");
	}
}


/**
*
*/
function getScriptSettings(){
	$.post(getIncludesPath()+"ajax/script_settings.php", {method: "get"}, function(data){
		if(data != null){
			// Script info
			$("#dialogScriptInfoName").html(data.Name+" ("+data.Version+")");
			$("#dialogScriptInfoCompatibleMapTypes").html(data.CompatibleMapTypes);
			if(data.Description != ""){
				$(".dialogScriptInfoDesc").attr("hidden", false);
				$("#dialogScriptInfoDesc").html(data.Description);
			}
			
			// Paramètres
			paramsList = "";
			if( typeof(data.ParamDescs) == "object" && data.ParamDescs.length > 0 ){
				$.each(data.ParamDescs, function(i, param){
					var paramValueField = "";
					if(param.Type == "boolean"){
						var isChecked = "";
						if(param.Default){
							isChecked = ' checked="checked"';
						}
						paramValueField = '<input class="text '+param.Type+'" type="checkbox" name="" id="" value="'+param.Default+'"'+isChecked+' />';
					}
					else if(param.Type == "int" || param.Type == "float"){
						paramValueField = '<input class="text '+param.Type+'" type="text" name="" id="" value="'+param.Default+'" />';
					}
					
					paramsList += '<tr>'
						+ '<td class="first">'+param.Name+'</td>'
						+ '<td class="middle">'+paramValueField+'</td>'
						+ '<td class="last">'+param.Desc+'</td>'
					+ '</tr>';
				});
				
				$("#dialogScriptSettings table tbody").html(paramsList);
			}
			
			// Dialog
			$("#getScriptSettingsDialog").dialog({
				title: $("#getScriptSettingsDialog").data("title"),
				modal: true,
				minWidth: 650,
				minHeight: 400,
				buttons: [
					{
						text: $("#getScriptSettingsDialog").data("cancel"),
						click: function(){
							$(this).dialog("close");
						}
					},
					{
						text: $("#getScriptSettingsDialog").data("save"),
						click: function(){
							setScriptSettings();
						}
					}
				]
			});
		}
	}, "json");
}
function setScriptSettings(){
	var structParams = [];
	var structValues = [];
	$.each( $("#dialogScriptSettings table tbody tr"), function(i, param){
		var key = param.children[0].textContent;
		var type = param.children[1].children[0].className;
		
		if(type.indexOf("boolean") !== -1){
			var val = param.children[1].children[0].checked;
		}
		else if(type.indexOf("int") !== -1 || type.indexOf("float") !== -1){
			var val = param.children[1].children[0].value;
		}
		
		structParams.push(key);
		structValues.push(val);
	});
	
	$.post(getIncludesPath()+"ajax/script_settings.php", {method: "set", key: structParams, val: structValues}, function(response){
		if(response != "true"){
			error(response);
			scrollTop();
		}
	});
}


/**
* Récupère les lignes du chat du serveur
*
* @param bool hideServerLines -> Afficher ou non les lignes provenant d'un gestionnaire de serveur
* @return string html
*/
function getChatServerLines(hideServerLines){
	$.getJSON(getIncludesPath()+"ajax/get_chatserverlines.php", {s: hideServerLines}, function(data){
		if(data != null){
			$("#chat").html(data);
		}
	});
}


/**
* Ajoute une ligne (pseudo + message) dans le chat du serveur
*/
function addChatServerLine(){
	var nickname = $("#chatNickname").val();
	if( nickname == $("#chatNickname").data("default-value") ){
		nickname = "";
	}
	var color = $("#chatColor").val();
	var message = $("#chatMessage").val();
	var destination = $("#chatDestination").val();
	var hideServerLines = $("#checkServerLines").data("val");
	
	$.post(getIncludesPath()+"ajax/add_chatserverline.php", {nic: nickname, clr: color, msg: message, dst: destination}, function(response){
		if(response != null){
			getChatServerLines(hideServerLines);
			$("#chatMessage").val("");
		}
	});
}


/**
* Initialisation de l'uploader Ajax
*/
function initializeUploader(){
	uploader = new qq.FileUploader({
		element: $("#formUpload")[0],
		action: getIncludesPath()+'ajax/upload.php',
		maxConnections: 2,
		params: {
			path: getPath(),
			type: $(".transferMode li.selected input").val(),
			mset: ( $("#SaveCurrentMatchSettings").attr("checked") ) ? true : false,
			gtlm: ( $("#GotoListMaps").attr("checked") ) ? true : false
		},
		template:
		'<div class="qq-uploader">' + 
			'<div class="qq-upload-drop-area"><span>'+ $("#formUpload").data('dropfiles') +'</span></div>' +
			'<div class="qq-upload-button">'+ $("#formUpload").data('uploadfile') +'</div>' +
			'<ul class="qq-upload-list"></ul>' + 
		'</div>',
		fileTemplate:
		'<li>' +
			'<span class="qq-upload-file"></span>' +
			'<span class="qq-upload-spinner"><span class="qq-upload-bar"></span></span>' +
			'<span class="qq-upload-size"></span>' +
			'<a class="qq-upload-cancel" href="./">'+ $("#formUpload").data('cancel') +'</a>' +
			'<span class="qq-upload-failed-text">'+ $("#formUpload").data('failed') +'</span>' +
		'</li>',
		onProgress: function(id, fileName, loaded, total){
			window.onbeforeunload = function(){
				return $("#formUpload").data('uploadnotfinished');
			}
			$.each( $(".qq-upload-list li"), function(key, value){
				// Récupèration des données
				var text = $(this).children(".qq-upload-size").text();
				var newtext = t(text);
				var lastpos = text.indexOf("%");
				var pourcent = text.substring(0, lastpos);
				
				// Modification des données
				$(this).children(".qq-upload-size").text(newtext);
				$(this).children(".qq-upload-spinner").children(".qq-upload-bar").css("width", pourcent+"px");
			});
		},
		onComplete: function(id, fileName, responseJSON){
			window.onbeforeunload = function(){}
			if(responseJSON.success == true){
				if(uploader._options.params.gtlm){
					location.href = "?p="+$("#formUpload").data("mapspagename");
				}
			}
		},
		onCancel: function(id, fileName){
			window.onbeforeunload = function(){}
		},
		messages: {
			typeError: $("#formUpload").data("type-error"),
			sizeError: $("#formUpload").data("size-error"),
			minSizeError: $("#formUpload").data("minsize-error"),
			emptyError: $("#formUpload").data("empty-error"),
			onLeave: $("#formUpload").data("onleave")
		},
		showMessage: function(message){
			error(message);
		}
	});
}


/**
* Récupère la liste des maps du serveur
*/
function getMapList(mode, sort){
	var path_ressources = getRessourcesPath();
	if(!mode){
		mode = getMode();
	}
	if(sort){
		setCurrentSort(sort);
	}
	
	$.getJSON(getIncludesPath()+"ajax/get_maplist.php", {mode: mode, sort: sort}, function(data){
		if(data != null){
			if(data.lst != null && !$("#maplist").isChecked() ){
				var out = "";
				
				// Création du tableau
				out += '<tr class="table-separation"><td colspan="6"></td></tr>';
				if( typeof(data.lst) == "object" && data.lst.length > 0 ){
					$.each(data.lst, function(i, map){
						out += '<tr'; if(data.cid == i){ out += ' id="currentMap"'; } out += ' class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } if(data.cid == i){ out += ' current'; } out += '">'
							+'<td class="imgleft"><img src="'+path_ressources+'images/16/map.png" alt="" />'
								+'<span title="'+map.FileName+'">'+map.Name+'</span>'
								if(mode == "detail"){
									out += '<span class="detailModeTd">'+map.UId+'</span>';
								}
							out += '</td>'
							+'<td class="imgcenter"><img src="'+path_ressources+'images/env/'+map.Environnement.toLowerCase()+'.png" alt="" />'+map.Environnement+'</td>'
							+'<td>'+map.Author+'</td>';
							if(mode == "detail"){
								out += '<td>'+map.GoldTime+'</td>'
								+'<td>'+map.CopperPrice+'</td>';
							}
							out += '<td class="checkbox">'; if(data.cid != i){ out += '<input type="checkbox" name="map[]" value="'+map.FileName+'" />'; } out += '</td>'
						+'</tr>';
					});
					
					if( $("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", false);
					}
				}
				else{
					if( !$("input#checkAll").attr("disabled") ){
						$("input#checkAll").attr("disabled", true);
					}
					out += '<tr class="no-line"><td class="center" colspan="6">'+data.lst+'</td></tr>';
				}
				
				// HTML
				$("#maplist table tbody").html(out);
				if( $("#maplist").hasClass("loading") ){
					$("#maplist").removeClass("loading");
				}
			}
		}
	});
}


/**
* Récupère la liste des fichiers map pour les renommer
*/
(function($){
	$.fn.getMapRenameList = function(){
		var out = "";
		var formSelector = $("#form-rename-map");
		var list = $(this);
		if( list.length > 0 ){
			out += '<ul>';
			$.each(list, function(i, n){
				if(n.value.length > 36){
					var title = ' title="'+n.value+'"';
				}else{
					var title = "";
				}
				out += '<li>'
					+ '<span class="rename-map-name"'+title+'>'+n.value+'</span>'
					+ '<span class="rename-map-arrow">&nbsp;</span>'
					+ '<input class="text width3" type="text" id="renameMapList" name="renameMapList[]" value="'+n.value+'" />'
				+ '</li>';
			});
			out += '</ul>';
		}
		
		// HTML
		out += '<div class="form-input-submit">'
			+ '<input class="button dark" type="button" id="renameMapCancel" name="renameMapCancel" value="'+formSelector.data("cancel")+'" />'+"\n"
			+ '<input class="button dark" type="submit" id="renameMapValid" name="renameAutoValid" value="'+formSelector.data("autorename")+'" />'
			+ '<input class="button dark" type="submit" id="renameMapValid" name="renameMapValid" value="'+formSelector.data("rename")+'" />'
		+ '</div>';
		formSelector.html(out);
	};
})(jQuery);


/**
* Récupère l'arboréscence des dossiers pour déplacer
*/
function getMoveFolderList(nbFiles){
	$.getJSON(getIncludesPath()+"ajax/get_directory_list.php", function(data){
		if(data != null){
			var out = "";
			var formSelector = $("#form-move-map");
			if( nbFiles > 1 ){
				var nbName = nbFiles + ' maps';
			}else{
				var nbName = '1 map';
			}
			out += '<label for="moveDirectoryList">'+formSelector.data("move")+' '+nbName+' '+formSelector.data("inthefolder")+'</label>'
			+ '<select name="moveDirectoryList" id="moveDirectoryList">'
				+ '<option value=".">'+formSelector.data("root")+'</option>';
				$.each(data, function(i, n){
					out += '<option value="'+n.path+'">'+n.level+n.name+'</option>';
				});
			out += '</select>'
			+ '<div class="form-input-submit">'
				+ '<input class="button dark" type="button" id="moveMapCancel" name="moveMapCancel" value="'+formSelector.data("cancel")+'" />'+"\n"
				+ '<input class="button dark" type="submit" id="moveMapValid" name="moveMapValid" value="'+formSelector.data("move")+'" />'
			+ '</div>';
			
			// HTML
			formSelector.html(out);
		}
	});
}

/**
* Fonctions d'affichage des formulaires
*/
function slideDownRenameForm(){
	$("#form-rename-map").slideDown("fast");
	$("#renameMap").addClass("active");
	$(".options").addClass("form");
	$(".options .selected-files-label").addClass("optHover");
	$("#maplist table tbody tr.selected td.checkbox input").getMapRenameList();
}
function slideUpRenameForm(){
	$("#form-rename-map").slideUp("fast");
	$("#renameMap").removeClass("active");
	$(".options").removeClass("form");
	$(".options .selected-files-label").removeClass("optHover");
}
function slideDownMoveForm(){
	$("#form-move-map").slideDown("fast");
	$("#moveMap").addClass("active");
	$(".options").addClass("form");
	$(".options .selected-files-label").addClass("optHover");
	if( $("#form-move-map").text() == "" ){
		getMoveFolderList( $("#maplist table tbody tr.selected td.checkbox input").length );
	}
}
function slideUpMoveForm(){
	$("#form-move-map").slideUp("fast");
	$("#moveMap").removeClass("active");
	$(".options").removeClass("form");
	$(".options .selected-files-label").removeClass("optHover");
}
function slideDownNewFolderForm(){
	$("#form-new-folder").animate({
		height: "25px",
		marginTop: "6px",
		marginBottom: "6px"
	}, "fast");
	$("#form-new-folder").removeAttr("hidden");
	$("#newfolder").text( $("#newfolder").data("cancel") );
	$("#newFolderName").select();
}
function slideUpNewFolderForm(){
	$("#form-new-folder").animate({
		height: "0",
		marginTop: "0",
		marginBottom: "0"
	}, "fast", function(){
		$("#form-new-folder").attr("hidden", true);
		$("#newfolder").text( $("#newfolder").data("new") );
	});
}


/**
* Fait un tri sur la liste des maps pour la page "maps-order"
*/
function setMapsOrderSort(sort, order){
	var path_ressources = getRessourcesPath();
	var list = $("#jsonlist").val();
	
	$.post(getIncludesPath()+"ajax/mapsorder_sort.php", {srt: sort, ord: order, lst: list}, function(data){
		if(data != null){
			var out = "";
			if( typeof(data.lst) == "object" && data.lst.length > 0 ){
				$.each(data.lst, function(id, map){
					if(data.cid != id){
						out += '<li class="ui-state-default">'
							+'<div class="ui-icon ui-icon-arrowthick-2-n-s"></div>'
							+'<div class="order-map-name" title="'+map.FileName+'">'+map.Name+'</div>'
							+'<div class="order-map-env"><img src="'+path_ressources+'images/env/'+map.Environnement.toLowerCase()+'.png" alt="" />'+map.Environnement+'</div>'
							+'<div class="order-map-author"><img src="'+path_ressources+'images/16/mapauthor.png" alt="" />'+map.Author+'</div>'
						+'</li>';
					}
				});
			}
			
			// HTML
			$("#sortableMapList").html(out);
			if( $("#sortableMapList").hasClass("loading") ){
				$("#sortableMapList").removeClass("loading");
			}
		}
	}, "json");
}


/**
* Gestion du mode détail de la page general
*/
function setGeneralDetailMode(){
	var sort = getCurrentSort();
	if( $("#detailMode").text() == $("#detailMode").data("textdetail") ){
		getCurrentServerInfo("detail", sort);
		$("#detailMode").text( $("#detailMode").data("textsimple") );
		$("#playerlist table th.detailModeTh").attr("hidden", false);
		$("#playerlist table th.firstTh").removeClass("thleft");
		$("#playerlist").addClass("loading");
		$("#detailMode").data("statusmode", "detail");
	}
	else{
		getCurrentServerInfo("simple", sort);
		$("#detailMode").text( $("#detailMode").data("textdetail") );
		$("#playerlist table th.detailModeTh").attr("hidden", true);
		$("#playerlist table th.firstTh").addClass("thleft");
		$("#playerlist").addClass("loading");
		$("#detailMode").data("statusmode", "simple");
	}
}


/**
* Gestion du mode détail de la page maps-list
*/
function setMapslistDetailMode(){
	var sort = getCurrentSort();
	if( $("#detailMode").text() == $("#detailMode").data("textdetail") ){
		getMapList("detail", sort);
		$("#detailMode").text( $("#detailMode").data("textsimple") );
		$("#maplist table th.detailModeTh").attr("hidden", false);
		$("#maplist").addClass("loading");
		$("#detailMode").data("statusmode", "detail");
	}
	else{
		getMapList("simple", sort);
		$("#detailMode").text( $("#detailMode").data("textdetail") );
		$("#maplist table th.detailModeTh").attr("hidden", true);
		$("#maplist").addClass("loading");
		$("#detailMode").data("statusmode", "simple");
	}
}


/**
* Coche toutes les checkbox d'un selecteur
*
* @param bool isChecked -> Si la checkbox "checkAll" est cochée
*/
(function($){
	$.fn.checkAll = function(isChecked){
		var lineSelector = $(this).find("tbody tr[class!=table-separation]");
		var checkboxSelector = $(this).find("tbody td.checkbox input[type=checkbox]");
		if(isChecked){
			checkboxSelector.attr("checked", true);
			lineSelector.addClass("selected");
		}
		else{
			checkboxSelector.attr("checked", false);
			lineSelector.removeClass("selected");
		}
	};
})(jQuery);

/**
* Met à jour la checkbox "checkAll" si toutes les lignes sont cochées ou non
*
* @param string checkAllSelector -> Selecteur de l'input "checkAll"
*/
(function($){
	$.fn.updateCheckAll = function(checkAllSelector){
		// On récupère le nombre d'élements sélectionnés
		var nbSelected = $(this).find("tbody tr.selected").length;
		var nbAll = $(this).find("tbody tr[class!=table-separation]").length;
		if( $("body").hasClass("section-maps-list") ){
			nbAll--;
		}
		
		if(nbSelected == nbAll){
			checkAllSelector.attr("checked", true);
		}
		else if(nbSelected == 0){
			if( checkAllSelector.attr("checked") ){
				checkAllSelector.attr("checked", false);
			}
		}
	};
})(jQuery);


/**
* Met à jour le nombre de fichiers sélectionnés
*/
(function($){
	$.fn.updateNbSelectedLines = function(){
		// On récupère le nombre d'élements sélectionnés
		var nb = $(this).find("tbody tr.selected").length;
		
		// Mise à jour
		if(nb > 0){
			$(this).find(".selected-files-label").removeClass("locked");
		}else{
			$(this).find(".selected-files-label").addClass("locked");
		}
		$(this).find(".selected-files-count").text("("+nb+")");
	};
})(jQuery);


/**
* Détermine si il y a au moins une ligne sélectionnée
*/
(function($){
	$.fn.isChecked = function(){
		// On récupère le nombre d'élements sélectionnés;
		var nb = $(this).find("tbody tr.selected").length;
		if(nb > 0){
			return true;
		}
		else{
			return false;
		}
	};
})(jQuery);


/**
* Récupère les valeurs des lignes sélectionnées
*
* @return array JSON
*/
function getJsonSelectedLines(){
	var selectedFiles = $(".cadre table tbody tr.selected");
	var files = "[";
	
	if(selectedFiles.length > 1){
		$.each(selectedFiles, function(i, n){
			if(i == selectedFiles.length-1){
				files += '"'+n.children[3].children[0].value+'"';
			}
			else{
				files += '"'+n.children[3].children[0].value+'",';
			}
		});
	}
	else{
		files += '"'+selectedFiles.find(".checkbox input").val()+'"';
	}
	files += "]";
	
	return files;
}


/**
* MATCHSETTINGS
*/

/**
* Vérifie si le nom du MatchSettings n'existe pas déjà
*
* @param string filename -> Le nom du fichier
*/
function matchset_getFileExists(filename){
	$.getJSON(getIncludesPath()+"ajax/get_matchset_fileexists.php", {path: getPath(), name: filename}, function(response){
		if(response){
			$("#matchSettingNameExists").attr("hidden", false);
			$("#matchSettingNameExists").hide();
			$("#matchSettingNameExists").fadeIn("fast");
		}
		else{
			$("#matchSettingNameExists").attr("hidden", true);
			$("#matchSettingNameExists").fadeOut("fast");
		}
	});
}

/**
* Récupère la liste des maps en local pour la création d'un matchSettings
*/
function matchset_mapImport(){
	var path = $("#mapsDirectoryList").val();
	$.getJSON(getIncludesPath()+"ajax/get_matchset_mapimport.php", {path: path}, function(data){
		if(data != null){
			matchset_setNbMapSelection(data.nbm.count);
			$(".creatematchset .maps").removeClass("loading");
		}
	});
}
function matchset_mapImportSelection(){
	var path = $("#mapsDirectoryList").val();
	var path_includes = getIncludesPath();
	var path_ressources = getRessourcesPath();
	$.getJSON(path_includes+"ajax/get_matchset_mapimport.php", {path: path, op: "getSelection"}, function(data){
		if(data != null){
			var out = "";
			
			// Création du tableau
			out += '<tr class="table-separation"><td colspan="4"></td></tr>';
			if( typeof(data.lst) == "object" && data.lst.length > 0 ){
				$.each(data.lst, function(i, map){
					out += '<tr class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } out += '">'
						+'<td class="imgleft"><img src="'+path_ressources+'images/16/map.png" alt="" />'
							+'<span title="'+map.FileName+'">'+map.Name+'</span>'
						out += '</td>'
						+'<td class="imgcenter"><img src="'+path_ressources+'images/env/'+map.Environnement.toLowerCase()+'.png" alt="" />'+map.Environnement+'</td>'
						+'<td>'+map.Author+'</td>';
						out += '<td class="checkbox"><input type="checkbox" name="map[]" value="'+map.FileName+'" /></td>'
					+'</tr>';
				});
				
				if( $("input#checkAllMapImport").attr("disabled") ){
					$("input#checkAllMapImport").attr("disabled", false);
				}
			}
			else{
				if( !$("input#checkAllMapImport").attr("disabled") ){
					$("input#checkAllMapImport").attr("disabled", true);
				}
				out += '<tr class="no-line"><td class="center" colspan="4">'+data.lst+'</td></tr>';
			}
			
			// HTML
			$("#mapImportSelectionDialog").removeAttr("hidden");
			$("#mapImportSelectionDialog table tbody").html(out);
			$(".creatematchset .maps").removeClass("loading");
			$("#mapImportSelectionDialog").dialog({
				title: $("#mapImportSelectionDialog").data("title"),
				modal: true,
				minWidth: 650,
				minHeight: 400,
				buttons: [{
					text: $("#mapImportSelectionDialog").data("select"),
					click: function(){
						var listSelection = $("#mapImportSelectionDialog tbody tr.selected");
						var listSelectionId = [];
						if( listSelection.length > 0 ){
							$.each(listSelection, function(i, n){
								if( n.className.indexOf("selected") !== -1 ){
									listSelectionId.push( parseInt(n.sectionRowIndex - 1) );
								}
							});
						}
						$.getJSON(path_includes+"ajax/get_matchset_mapimport.php", {path: path, op: "setSelection", select: listSelectionId}, function(data){
							if(data != null){
								matchset_setNbMapSelection(data.nbm.count);
								$("#mapImportSelectionDialog").dialog("close");
							}
						});
					}
				}]
			});
		}
	});
}


/**
* Met à jour le nombre de maps sélectionnées
*
* @param int nb
*/
function matchset_setNbMapSelection(nb){
	$("#nbMapSelected").text(nb);
}
/**
* Récupère et affiche la sélection du MatchSettings
*/
function matchset_mapSelection(removeId){
	var path_ressources = getRessourcesPath();
	if(removeId){
		var params = {remove: parseInt(removeId - 1)};
	}else{
		var params = "";
	}
	$.getJSON(getIncludesPath()+"ajax/get_matchset_mapselection.php", params, function(data){
		if(data != null){
			var out = "";
			
			// Création du tableau
			out += '<tr class="table-separation"><td colspan="4"></td></tr>';
			if( typeof(data.lst) == "object" && data.lst.length > 0 ){
				$.each(data.lst, function(i, map){
					out += '<tr class="'; if(i%2){ out += 'even'; }else{ out += 'odd'; } out += '">'
						+'<td class="imgleft"><img src="'+path_ressources+'images/16/map.png" alt="" />'
							+'<span title="'+map.FileName+'">'+map.Name+'</span>'
						out += '</td>'
						+'<td class="imgcenter"><img src="'+path_ressources+'images/env/'+map.Environnement.toLowerCase()+'.png" alt="" />'+map.Environnement+'</td>'
						+'<td>'+map.Author+'</td>';
						out += '<td class="checkbox imgcenter"><a href="." title="'+$("#mapSelectionDialog").data("remove")+'"><img src="'+path_ressources+'images/16/delete.png" alt="" /></a></td>'
					+'</tr>';
				});
			}
			else{
				out += '<tr class="no-line"><td class="center" colspan="4">'+data.lst+'</td></tr>';
			}
			
			// HTML
			matchset_setNbMapSelection(data.nbm.count);
			$("#mapSelectionDialog").removeAttr("hidden");
			$("#mapSelectionDialog table tbody").html(out);
			$(".creatematchset .maps").removeClass("loading");
			$("#mapSelectionDialog").dialog({
				title: $("#mapSelectionDialog").data("title"),
				modal: true,
				minWidth: 650,
				minHeight: 400,
				buttons: [{
					text: $("#mapSelectionDialog").data("close"),
					click: function(){
						$(this).dialog("close");
					}
				}]
			});
		}
	});
}


/* Folders */
function getRenameFolderForm(){
	$("#renameFolderForm").removeAttr("hidden");
	$("#renameFolderForm").dialog({
		title: $("#renameFolderForm").data("title"),
		modal: true,
		resizable: false,
		minWidth: 406,
		buttons: [
			{
				text: $("#renameFolderForm").data("cancel"),
				click: function(){
					$(this).dialog("close");
				}
			},
			{
				text: $("#renameFolderForm").data("rename"),
				click: function(){
					$("#optionFolderHiddenFieldValue").val( $("#renameFolderNewName").val() );
					$("#optionFolderForm").submit();
				}
			},
			
		]
	});
}
function getMoveFolderForm(){
	$("#moveFolderForm").removeAttr("hidden");
	$.getJSON(getIncludesPath()+"ajax/get_directory_list.php", function(data){
		if(data != null){
			var out = "";
			var formSelector = $("#moveFolderForm");
			out += '<label for="moveFormDirectoryList">'+formSelector.data("movethefolder")+'</label>'
			+ '<select name="moveFormDirectoryList" id="moveFormDirectoryList">'
				+ '<option value=".">'+formSelector.data("root")+'</option>';
				$.each(data, function(i, n){
					out += '<option value="'+n.path+'">'+n.level+n.name+'</option>';
				});
			out += '</select>';
			
			// HTML
			formSelector.html(out);
		}
	});
	$("#moveFolderForm").dialog({
		title: $("#moveFolderForm").data("title"),
		modal: true,
		resizable: false,
		minWidth: 406,
		buttons: [
			{
				text: $("#moveFolderForm").data("cancel"),
				click: function(){
					$(this).dialog("close");
				}
			},
			{
				text: $("#moveFolderForm").data("move"),
				click: function(){
					$("#optionFolderHiddenFieldValue").val( $("#moveFormDirectoryList").val() );
					$("#optionFolderForm").submit();
				}
			},
			
		]
	});
}
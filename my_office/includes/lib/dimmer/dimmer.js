$(document).ready(function() {
    var A = "";
    var B = null;
    showMessage = function(E, Q, M, F, H) {
        var L = null;
        if (B != null) {
            $(B).html(A);
            $("#dimmerMessage").remove()
        } else {
            iframeLayer = '<iframe id="iframeLayer" style="z-index:99;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0;filter: alpha(opacity=0);" src="javascript:\'\'"></iframe>';
            $('<div id="dimmer"></div>').appendTo(document.body)
        }
        B = E;
        A = $(E).html();
        $(E).empty();
        var O = '<a class="dimmerBtnClose clearLink" href="#">Close</a>';
        $('<div id="dimmerMessage"><table class="shadowBox"><tr><td class="shtl"></td><td class="shtm"></td><td class="shtr"></td></tr><tr><td class="shml"></td><td class="shmm">' + O + A + '</td><td class="shmr"></td></tr><tr><td class="shbl"></td><td class="shbm"></td><td class="shbr"></td></tr></table></div>').appendTo(document.body);
        $(".dimmerBtnClose").click(function() {
            hideMessage();
            return false
        });
        hideFlashObjects();
        var I = 0;
        if (typeof document.body.style.maxHeight === "undefined") {
            I = document.documentElement.scrollTop;
            if (L != null) {
                $(iframeLayer).appendTo(document.body)
            }
        }
        if (Q) {
            var J = Q;
            $("#dimmerMessage").width(Q + "px")
        } else {
            var J = $("#dimmerMessage").width()
        }
        var N = (J / 2) - J;
        $("#dimmerMessage").css("marginLeft", N);
        var G = $("#dimmerMessage").height();
        var R = (G / 2) - G + I;
        $("#dimmerMessage").css("marginTop", R);
        if ($.browser.opera) {
            $("#flashItem").css("visibility", "hidden");
            $("#dimmer, #dimmerMessage").show();
            $("#flashItem").css("visibility", "visible")
        } else {
            $("#dimmer").fadeIn("fast",
            function() {
                $("#dimmerMessage").fadeIn("fast",
                function() {
                    if (M) {
                        $("#dimmerMessage").find(".btnOnlinePlain").attr("href", M)
                    }
                    if (F) {
                        $("#screenShotsWrapper .carousel-container li").each(function(S) {
                            imgURL = "url(/content/screenshots/" + F + "_billboard_" + (S + 1) + ".jpg)";
                            $(this).css("background", imgURL)
                        })
                    }
                })
            })
        }
        if (H) {
            var P = $("#dimmerMessage").height();
            var C = $(window).height();
            var D = $("html").scrollTop();
            var K = P > C ? D + 10 : D + (C / 2 - P / 2);
            $("#dimmerMessage").css("position", "absolute").css("margin-top", 0).css("top", K)
        }
    };
    hideMessage = function(C) {
        if ($.browser.msie) {
            $("#dimmer, #dimmerMessage").remove();
            $("#iframeLayer").remove()
        } else {
            if (typeof C != "undefined" && C == true) {
                $("#dimmer, #dimmerMessage").remove()
            } else {
                $("#dimmer, #dimmerMessage").fadeOut("fast",
                function() {
                    $("#dimmer, #dimmerMessage").remove()
                })
            }
        }
        showFlashObjects();
        $(B).html(A);
        B = null
    }
	
	$('a[rel="dimmer"]').each(function(){
		
		$(this).click(function(){
		 var href = $(this).attr('href');		
		 if(href.substr(0,1)=='#')
		 	showMessage(href, "450",false, "", "", "");
		 else
			 showAjaxMessage(href, "#dimmerBox", "450",false, "", "", "");
		 return false;
		});			
	});
	
});
hideFlashObjects=function(){$(".adWrap").find("*").css("visibility","hidden");$(".ad300x250").css("visibility","hidden");$("object#preplayMovie").attr("style","");$("object#preplayMovie").css("visibility","hidden");$("object#adLoader_div").attr("style","");$("object#adLoader_div").css("visibility","hidden");$("#adWrap").css("visibility","hidden");$("object#movieSwf").attr("style","");$("object#movieSwf").css("visibility","hidden")};
showFlashObjects=function(){$(".adWrap").find("*").css("visibility","visible");$(".ad300x250").css("visibility","visible");$("object#preplayMovie").css("visibility","visible");$("object#adLoader_div").css("visibility","visible");$("#adWrap").css("visibility","visible");$("object#movieSwf").css("visibility","visible")};
showAjaxMessage = function(E, H, F, D, G, A, C, B) {
    if ($(H).length == 0 || F) {
        $(H).remove();
        $.post(E,
        function(I) {
			
            $("#dynamicContent").append(I);
            showMessage(H, D, G, A, C);
            if (typeof B != "undefined") {
                B()
            }
        })
    } else {
        showMessage(H, D, G, A, C);
        if (typeof B != "undefined") {
            B()
        }
    }};

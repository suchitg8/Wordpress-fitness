jQuery( function( $ ){
    var tabs = $("#wp_mega_messages_tabs"),
        submit = $("#submit"),
        patterns = $("[data-pattern]"),
        control = $("#wp_mega_messages_control"),
        customize_tab = $("#wp_mega_messages_customize_tabs"),
        background_opacity = $("#bg_opacity"),
        position_box = $("#wp_mega_messages_position"),
        draggable_positon = $("#wp_mega_messages_drag"),
        customize_control = $("#wp_mega_messages_customize_tabs_control");
        
        pos = {
            top : $("#position_top"),
            left : $("#position_left")
        },
        
        // Get message box content value
        getTextareaValue = function() {
            return $("#wp_message_box_content_ifr").contents().find("[data-id='wp_message_box_content']").html();
        }
    
    tabs.find("ul li[data-tab]").click( function(){
        var select_class = "wp_mega_messages_tabs_select",
            select_control = "wp_mega_messages_tabs_block",
            
            data_tab = $(this).data("tab");
        
        if ( $(this).hasClass( select_class ) )
            return;
        
        // Change control
        control.find("div[data-tab-id]").removeClass( select_control );
        control.find("div[data-tab-id='" + data_tab + "']").addClass( select_control );
        
        // Change tab
        tabs.find("li").removeClass( select_class );
        $(this).addClass( select_class );
        
        // Copy value from textarea content
        if ( data_tab == "customize" ) {
            var copy = getTextareaValue();
            
            if ( $(copy).text() != "" ) {
                $("[data-tab-id='customize']").find(".wp_mega_message_box").html( copy );
            }
        }
        
        // Location hash
        if ( location.hash.indexOf( data_tab ) === -1 )
            window.location = '#' + data_tab;
    });
    
    // Trigger message
    message();
    
    // Trigget tabs
    var tabs_hash = location.hash.replace("#", "").replace(/\-(.*)$/, ""),
        customize_hash = location.hash.replace("#", "").replace(/^(.*)\-/, "");

    if ( /^(settings|effects|customize|position)$/.test( tabs_hash ) )
        tabs.find("ul li[data-tab='" + tabs_hash + "']").trigger("click");

    // Customize tabs
    if (0) {
        customize_tab.find("[data-tab]").click( function(){
            var select_class = "wp_mega_messages_customize_tabs_select",
                select_control = "wp_mega_messages_tabs_block",
                
                data_tab = $(this).data("tab");
            
            // Change control
            customize_control.find("div[data-custom-tab-id]").removeClass( select_control );
            customize_control.find("div[data-custom-tab-id='" + data_tab + "']").addClass( select_control );
            
            // Change tab
            customize_tab.find("li").removeClass( select_class );
            $(this).addClass( select_class );
            
            // Create hash location
            window.location = '#' + tabs_hash + "-" + data_tab;
        });
        
        if ( /^(design|custom)$/.test( customize_hash ) )
            customize_tab.find("[data-tab='" + customize_hash + "']").trigger("click");
    }
    
    // Patterns of the designs
    patterns.click( function(){
        var pattern = $(this).data("pattern"),
            radio = $( "#" + $(this).data("radio") ),
            value = radio.val(),
            
            preview = radio.parent().parent().next().find("div.wp_mega_message_box"),
            old = $(this).parent().find("[data-pattern].select").data("pattern");
        
        // if pattern selected, ignore
        if( $(this).hasClass("select") )
            return;

        // Switch preview class
        preview.removeClass("wp_mega_message_box_" + old);
        preview.addClass("wp_mega_message_box_" + pattern);
        
        // Switch pattern select
        $(this).parent().find("[data-pattern]").removeClass("select");
        $(this).addClass("select");
        
        // Update value of radio button
        value = value.replace(/\|.*$/, "");
        radio.val( value + "|" + pattern );
        
        // Trigger radio click
        if ( ! radio.is(":checked") )
            radio.trigger("click");
    });
    
    // Clear opacity function
    var clearOpacity = null;
    
    // Opacity slider
    $( "#slider-range" ).slider({
        min: 0,
        max: 100,
        value : background_opacity.val(),
        step: 1,
        slide: function( event, ui ) {
            if ( clearOpacity )
                clearTimeout( clearOpacity );
            
            var opacity_bg = $("#wp_mega_messages_opacity"),
                label_color = ui.value > 50 ? "#fff" : "23282D";
            
            if ( opacity_bg.length === 0 ) {
                $("body").append("<div id='wp_mega_messages_opacity'></div>");
                opacity_bg = $("#wp_mega_messages_opacity");
            }
            
            control.find("label").css("color", "#" + label_color);
            opacity_bg.css("background-color", "rgba(0,0,0," + ui.value / 100 + ")" );
            opacity_bg.show();
            
            background_opacity.val( ui.value );
            
            // Remove opacity in 2 seconds
            clearOpacity = setTimeout( function(){
                opacity_bg.fadeOut(300, function(){
                    $(this).remove();
                    control.find("label").css("color", "#23282D");
                })
            }, 1500);
        }
    });
    
    // Show custom frequency
    $("#wp_message_box_frequency").change( function(){
        var custom_frequency = $("#custom_frequency_display");
         
        custom_frequency.hide();
         
        if ( parseInt( $(this).val() ) == 5 ) {
            custom_frequency.show();
            custom_frequency.css({
               "background-color" : "#C0C0C0"
            });
            
            setTimeout( function(){
                custom_frequency.css({
                    "background-color" : "transparent",
                    "transition" : "1s"
                });
            }, 500);
        }
    })
    
    // Set the size of the box and create minimize screen
    position_box.css({"width" : screen.width / 1.2,
                      "height" : screen.height / 2});
    
    // Set the draggable box position
    draggable_positon.css({
       top : ( ( pos.top.val() * screen.height ) / 100 ) / 2,
       left : ( ( pos.left.val() * screen.width ) / 100 ) / 1.2
    });
    
    // Draggable position
    draggable_positon.draggable({
        containment : "parent",
        start : function() {
            $(this).find("div[data-position]").fadeOut("fast", function(){
                $(this).remove(); 
            });
        },
        stop : function(){
            var position = $(this).position(),
                left = ( position.left * 1.2 ),
                top = ( position.top * 2 );
                
            // Get Percentage
            left = ( left / screen.width ) * 100;
            top = ( top / screen.height ) * 100;

            pos.top.val( top );
            pos.left.val( left );
            
            $(this).append("<div data-position='top' style='color: #" + ( top <= 3 ? "000" : "fff" ) + "'>" + Math.round( top, 2 ) + "%</div> \
                            <div data-position='left' style='color: #" + ( left <= 3 ? "000" : "fff" ) + "'>" + Math.round( left, 2 ) + "%</div>");
        }
    });
        
    // Show wrapper
    $("#wp_mega_messages_wrapper").fadeIn(500);
    
    // Submit form check
    function submitForm(){
        var contentMessage = $("#wp-wp_message_box_content-editor-container"),
            boxWidth = $("#box_size_width"),
            boxWidthType = $("[name='box_size_width_type']"),
            boxHeight = $("#box_size_height");
        
        contentMessage.removeClass("wp_mega_messages_error");
        boxWidth.removeClass("wp_mega_messages_error");
        boxHeight.removeClass("wp_mega_messages_error");
        
        // Content Message Value
        var textarea_value = getTextareaValue();
        
        if ( typeof textarea_value !== "undefined" && textarea_value == "" ) {
            contentMessage.addClass("wp_mega_messages_error");
            $("[data-tab=\"settings\"]").trigger("click");
            alert("Message Content Required");
            return false;
        }
        
        // Box Width
        if ( ! /^\d*$/.test( boxWidth.val() ) ) {
            boxWidth.addClass("wp_mega_messages_error");
            $("[data-tab=\"customize\"]").trigger("click");
            alert("Box width must be integers");
            return false;
        }
    
        if ( parseInt( boxWidthType.val() ) == 1 && parseInt( boxWidth.val() ) > 100 ) {
            boxWidth.addClass("wp_mega_messages_error");
            $("[data-tab=\"customize\"]").trigger("click");
            alert("Box width in percentages must be under 100");
            return false;
        }
        
        // Box Height
        if ( ! /^\d*$/.test( boxHeight.val() ) ) {
            boxHeight.addClass("wp_mega_messages_error");
            $("[data-tab=\"customize\"]").trigger("click");
            alert("Box height must be integers");
            return false;
        }
    
        return true;
    }
    
    function message() {
        if ( typeof msg_box_init !== "undefined" ) {
            if ( msg_box_init.hasOwnProperty("show_message") && msg_box_init.show_message == 1 && msg_box_init.hasOwnProperty("content") ) {
                var message_box = "",
                    design = "",
                    pattern = "",
                    fontfamily = [ "cursive", "fantasy", "monospace", "sans-serif", "serif" ];
    
                // Design coustomize build 
                if ( msg_box_init.hasOwnProperty("design") ) {
                    design = "wp_mega_message_box_" + msg_box_init.design;
                    
                    if ( msg_box_init.hasOwnProperty("pattern") )
                        pattern = "wp_mega_message_box_" + msg_box_init.pattern;
                }
                  
                // Build message box
                message_box += "<div class='wp_mega_message_box " + design + " " + pattern + "' data-role='main'>";
                message_box += msg_box_init.content.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
                message_box += msg_box_init.close_button == 1 ? "<i class=\"fa fa-times wp_mega_message_box_hide " + ( msg_box_init.box_width.size > 0 ? "wp_mega_message_hide_absolute" : "") + "\"></i>" : "";
                message_box += "</div>";
                
                // Opacity background
                if ( msg_box_init.hasOwnProperty("opacity") )
                    message_box += "<div id='wp_mega_messages_opacity' style='background-color: rgba(0,0,0," + msg_box_init.opacity + ")'></div>";
                
                // Append message to the body
                $("body").append( message_box );
                
                var messagebox = $(".wp_mega_message_box[data-role='main']"),
                    close_message = messagebox.find(".wp_mega_message_box_hide"),
                    opacityBg = $("#wp_mega_messages_opacity"),
                    opacityCondition = function() {
                        return msg_box_init.hasOwnProperty("opacity_fade") && msg_box_init.opacity_fade == 1 && opacityBg.length === 1;
                    },
                    boxFadingCondition = function() {
                        return msg_box_init.hasOwnProperty("box_fading") && msg_box_init.box_fading == 1;
                    },
                    getType = function( type ) {
                        var types = [ "px", "%", "cm" ];
                        return type <= types.length ? types[ type ] : types[0];
                    },
                    closeMessage = function() {
                        // Message box hide effect
                        if ( boxFadingCondition() ) {
                            messagebox.fadeOut("slow", function(){
                                $(this).remove();
                            });
                        }
                        else {
                            messagebox.remove();
                        }
        
                        // Hide background opacity
                        if ( opacityCondition() )
                            opacityBg.fadeOut("fast");
                        else
                            opacityBg.hide();
                    }
                
                // Init font family
                messagebox.css("font-family", fontfamily[ msg_box_init.font.family ]);
                
                // Init font size
                messagebox.css("font-size", msg_box_init.font.size);
                
                // Init sizing of the message box
                if ( msg_box_init.box_width.size > 0 )
                    messagebox.css( "width", msg_box_init.box_width.size + getType( msg_box_init.box_width.type ) );
                    
                if ( msg_box_init.box_height.size > 0 )
                    messagebox.css( "height", msg_box_init.box_height.size + getType( msg_box_init.box_height.type ) );
                
                // Init position of the message box
                messagebox.css( "top", msg_box_init.position.top + "%" );
                messagebox.css( "left", msg_box_init.position.left + "%" );
                
                // Opacity background effect
                if ( opacityCondition() )
                    opacityBg.fadeIn("fast");
                else
                    opacityBg.show();
                    
                // Message box show effect
                if ( boxFadingCondition() )
                    messagebox.fadeIn("slow");
                else
                    messagebox.show();
                    
                // Close the message box
                close_message.click( function(){
                    closeMessage();
                });
                
                // Draggable box
                if ( msg_box_init.draggable == 1 )
                    messagebox.draggable();
                
                // Auto closing box
                if ( msg_box_init.auto_closing > 0 ) {
                    setTimeout( function(){
                        closeMessage();
                    }, msg_box_init.auto_closing * 1000);
                }
                
                // Ignore cookie
                if ( msg_box_init.hasOwnProperty("cookie") && msg_box_init.cookie == false )
                    return;
    
                // Cookies due date
                if ( msg_box_init.frequency > 0 && messagebox.length === 1 ) {
                    var date = new Date(),
                        freq = 1,
                        custom = null;
    
                    switch( msg_box_init.frequency ) {
                        case 2 :
                            freq = 7;
                            break;
                        case 3 :
                            freq = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
                            break;
                        case 4 :
                            freq = 365;
                            break;
                        case 5 :
                            if ( msg_box_init.hasOwnProperty("custom_frequency") ) {
                                var custom_frequency = msg_box_init.custom_frequency;
                                    custom = custom_frequency.num;
                                
                                if ( custom_frequency.num > 0 ) {
                                    switch( custom_frequency.type ) {
                                        case 1 :
                                            custom *= 1000; 
                                            break;
                                        case 2 :
                                            custom *= 60 * 1000;
                                            break;
                                        case 3 :
                                            custom *= 60 * 60 * 1000;
                                            break;
                                        case 4 :
                                            custom *= 24 * 60 * 60 * 1000;
                                            break;
                                    }
                                }
                            }
                            
                            break;
                    }
    
                    date.setTime( date.getTime() + ( ! custom ? ( freq * 24 * 60 * 60 * 1000 ) : custom ) );
                    document.cookie = "mega_message_cookie=true;expires=" + date.toUTCString();
                }
            }
        }
    }
});

jQuery.fn.autoscroll = function(params){
    params = jQuery.extend({
        direction: 'vertical',
        speed: 10,
        pauseDelay: 500
    }, params);

    jQuery(this).each(function(i,elm){
        var conteneur = jQuery(elm);
        conteneur.wrap("<div class='autoscroll-wrapper themeAdjust'></div>");          
        var wrapper = conteneur.parent('.autoscroll-wrapper');
        jQuery(["top","right","bottom","left"]).each(function(i,dir){
          wrapper.css("margin-"+dir,conteneur.css("margin-"+dir));
          conteneur.css("margin-"+dir,0);
      });
        var posAttr=["scrollTop","scrollLeft"], dimAttr=["height","width"], sdimAttr=["scrollHeight","scrollWidth"];
        var s, d, o, t, dt = Math.round(500/params.speed);

        function scrollLoop(){
            if(s+o<d+1){                    
                conteneur[posAttr[0]](s);
                t = setTimeout(scrollLoop, dt);
                s++;
            } else {
                t = setTimeout(function(){
                    s=0;
                    t = setTimeout(scrollLoop, params.pauseDelay);                     
                }, params.pauseDelay);
            }
        }

        function initAutoscroll(){
            d = conteneur.prop(sdimAttr[0]);
            o = conteneur[dimAttr[0]]();                
            wrapper[dimAttr[1]](conteneur.prop(sdimAttr[1]));                
            s=0;
            if(t!=null){ clearTimeout(t); }
            if(o < d){ scrollLoop(); }
        }

        jQuery(window).resize(initAutoscroll);
        initAutoscroll();
        conteneur.on("DOMNodeRemovedFromDocument", function(){ if(t!=null){ clearTimeout(t); } });
    });

return this;
};

jQuery(".conteneur").autoscroll();


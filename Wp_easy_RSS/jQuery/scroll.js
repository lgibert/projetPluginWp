
jQuery.fn.autoscroll = function(params){
    params = jQuery.extend({
        direction: 'vertical',
        speed: 10,
        pauseDelay: 500
    }, params);

    jQuery(this).each(function(i,elm){
        var container = jQuery(elm);
        container.wrap("<div class='autoscroll-wrapper' style='overflow: hidden'></div>");          
        var wrapper = container.parent('.autoscroll-wrapper');
        jQuery(["top","right","bottom","left"]).each(function(i,dir){
          wrapper.css("margin-"+dir,container.css("margin-"+dir));
          container.css("margin-"+dir,0);
      });
        var posAttr=["scrollTop","scrollLeft"], dimAttr=["height","width"], sdimAttr=["scrollHeight","scrollWidth"];
        var s, d, o, t, dt = Math.round(500/params.speed);

        function scrollLoop(){
            if(s+o<d+1){                    
                container[posAttr[0]](s);
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
            d = container.prop(sdimAttr[0]);
            o = container[dimAttr[0]]();                
            wrapper[dimAttr[1]](container.prop(sdimAttr[1]));                
            s=0;
            if(t!=null){ clearTimeout(t); }
            if(o < d){ scrollLoop(); }
        }

        jQuery(window).resize(initAutoscroll);
        initAutoscroll();
        container.on("DOMNodeRemovedFromDocument", function(){ if(t!=null){ clearTimeout(t); } });
    });

return this;
};

jQuery(".container").autoscroll();

